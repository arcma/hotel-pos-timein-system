<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Terminal extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->config->load('print_api');
        $this->load->model('Kitchen_model');
        $this->load->model('Cart_model');
        $this->load->model('Orders_model');
        $this->load->model('Tables_model');
        $this->load->model('Products_model');
        $this->load->model('Settings_model');
        $this->load->model('Prints_model');
        date_default_timezone_set('Asia/Manila');
    }

    private function requirePrintAccess()
    {
        $login = $this->session->userdata('login');
        if (!$login || !in_array((int) $login->urRoleID, array(1, 4), true)) {
            redirect('login');
            return false;
        }
        return true;
    }

    /** Unified printer terminal for cashier (types 1,2) and kitchen (type 3). */
    public function prints()
    {
        if (!$this->requirePrintAccess()) return;
        $data['jobs'] = $this->Prints_model->getRecent(100);
        $data['cashier_ip'] = trim((string) $this->Settings_model->get_setting('cashier_ip'));
        $data['kitchen_ip'] = trim((string) $this->Settings_model->get_setting('kitchen_ip'));
        $this->load->view('terminal/prints', $data);
    }

    // Backward compatibility for bookmarks: old terminal URL now opens unified queue.
    public function kitchen()
    {
        redirect('terminal/prints');
    }

    public function poll()
    {
        if (!$this->requirePrintAccess()) {
            $this->output->set_status_header(401)->set_content_type('application/json')
                ->set_output(json_encode(array('ok' => false, 'message' => 'Authentication required.')));
            return;
        }

        $printed = array();
        $failed = array();
        foreach ($this->Prints_model->getPending(50) as $job) {
            $this->db->trans_begin();
            $locked = $this->db->query(
                'SELECT prtID, prtType, prtCartID, prtKitchenID, prtData FROM tbl_prints WHERE prtID = ? AND prtStatus = 0 FOR UPDATE',
                array($job->prtID)
            )->row();
            if (!$locked) {
                $this->db->trans_rollback();
                continue;
            }

            $ip = ((int) $locked->prtType === 3)
                ? trim((string) $this->Settings_model->get_setting('kitchen_ip'))
                : trim((string) $this->Settings_model->get_setting('cashier_ip'));
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                $this->db->trans_rollback();
                $failed[] = array('id' => (int) $locked->prtID, 'type' => (int) $locked->prtType, 'reason' => 'Printer IP is missing or invalid in POS Settings.');
                continue;
            }

            $payload = $this->buildPrintPayload($locked);
            if ($payload === false || !$this->sendRawToPrinter($ip, $payload)) {
                $this->db->trans_rollback();
                $failed[] = array('id' => (int) $locked->prtID, 'type' => (int) $locked->prtType, 'reason' => 'Could not generate/send print job to ' . $ip . ':9100.');
                continue;
            }

            $this->Prints_model->markPrinted($locked->prtID);
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                $failed[] = array('id' => (int) $locked->prtID, 'type' => (int) $locked->prtType, 'reason' => 'Could not update print status.');
            } else {
                $this->db->trans_commit();
                $printed[] = array('id' => (int) $locked->prtID, 'type' => (int) $locked->prtType, 'printer_ip' => $ip);
            }
        }

        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'ok' => empty($failed), 'printed' => $printed, 'failed' => $failed,
            'jobs' => $this->Prints_model->getRecent(100)
        )));
    }



    /**
     * Machine-to-machine API used by the separate local print terminal.
     * Authentication uses a bearer token; no browser CORS access is required.
     */
    private function requirePrintApiToken()
    {
        $configured = (string) $this->config->item('print_api_token');
        $header = $this->input->get_request_header('Authorization', TRUE);
        $provided = '';
        if (is_string($header) && stripos($header, 'Bearer ') === 0) {
            $provided = trim(substr($header, 7));
        }
        if ($configured === '' || $provided === '' || !hash_equals($configured, $provided)) {
            $this->output->set_status_header(401)->set_content_type('application/json')
                ->set_output(json_encode(array('ok' => false, 'message' => 'Unauthorized')));
            return false;
        }
        return true;
    }

    /** GET /api/prints/pending - returns receipt payloads ready for local ESC/POS printing. */
    public function api_pending()
    {
        if (!$this->requirePrintApiToken()) return;
        $result = array();
        foreach ($this->Prints_model->getPending(50) as $job) {
            $payload = $this->buildPrintPayload($job);
            if ($payload === false) {
                $result[] = array(
                    'prtID' => (int) $job->prtID,
                    'prtType' => (int) $job->prtType,
                    'error' => 'Could not build print payload from the current order data.'
                );
                continue;
            }
            $result[] = array(
                'prtID' => (int) $job->prtID,
                'prtType' => (int) $job->prtType,
                'prtCartID' => $job->prtCartID === null ? null : (int) $job->prtCartID,
                'prtKitchenID' => $job->prtKitchenID === null ? null : (int) $job->prtKitchenID,
                'prtDateCreated' => $job->prtDateCreated,
                'payload_base64' => base64_encode($payload)
            );
        }
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'ok' => true,
            'jobs' => $result,
            'queue' => $this->Prints_model->getRecent(100)
        )));
    }

    /** POST /api/prints/update-status - local terminal reports the result. */
    public function api_update_status()
    {
        if (!$this->requirePrintApiToken()) return;
        if ($this->input->method(TRUE) !== 'POST') {
            $this->output->set_status_header(405)->set_content_type('application/json')
                ->set_output(json_encode(array('ok' => false, 'message' => 'POST required')));
            return;
        }
        $body = json_decode($this->input->raw_input_stream, true);
        $id = isset($body['prtID']) ? (int) $body['prtID'] : 0;
        $status = isset($body['prtStatus']) ? (int) $body['prtStatus'] : -1;
        if ($id < 1 || !in_array($status, array(0, 1), true)) {
            $this->output->set_status_header(400)->set_content_type('application/json')
                ->set_output(json_encode(array('ok' => false, 'message' => 'Invalid prtID or prtStatus')));
            return;
        }
        $job = $this->db->select('prtID, prtStatus')->from('tbl_prints')->where('prtID', $id)->limit(1)->get()->row();
        if (!$job) {
            $this->output->set_status_header(404)->set_content_type('application/json')
                ->set_output(json_encode(array('ok' => false, 'message' => 'Print job not found')));
            return;
        }
        $update = array('prtStatus' => $status);
        $update['prtDatePrinted'] = $status === 1 ? date('Y-m-d H:i:s') : null;
        $this->db->where('prtID', $id)->update('tbl_prints', $update);
        $this->output->set_content_type('application/json')->set_output(json_encode(array(
            'ok' => $this->db->affected_rows() >= 0,
            'prtID' => $id,
            'prtStatus' => $status
        )));
    }

    private function buildPrintPayload($job)
    {
        switch ((int) $job->prtType) {
            case 3:
                $item = $this->Kitchen_model->getPrinterItem($job->prtKitchenID);
                return $item ? $this->buildKitchenTicket($item) : false;
            case 2:
                // Type 2 stores a snapshot so checkout/deleting the live cart cannot lose a queued preview.
                $snapshot = !empty($job->prtData) ? json_decode($job->prtData) : null;
                $cart = ($snapshot && isset($snapshot->cart)) ? $snapshot->cart : $this->Cart_model->getCartByTable($job->prtCartID);
                if (empty($cart)) return false;
                return $this->buildCartReceipt($cart, 'ORDER PREVIEW', array('table_name' => ($snapshot && isset($snapshot->table_id)) ? $this->tableName($snapshot->table_id) : (isset($cart[0]->tbName) ? $cart[0]->tbName : 'N/A')));
            case 1:
                // For type 1, prtCartID stores the completed tbl_orders.odrID.
                $order = $this->Orders_model->get($job->prtCartID);
                if (!$order) return false;
                $cart = json_decode($order->odrJsonCartItems);
                if (!is_array($cart) || empty($cart)) return false;
                $meta = array(
                    'table_name' => isset($cart[0]->tbName) ? $cart[0]->tbName : 'N/A',
                    'sales' => (float) $order->odrPrice,
                    'discount' => (float) $order->odrDiscount,
                    'total' => (float) $order->odrPrice - (float) $order->odrDiscount,
                    'other_charges' => (float) $order->odrOtherCharges,
                    'payment' => (float) $order->odrPayment,
                    'change' => (float) $order->odrChange,
                    'date' => $order->odrDate_created
                );
                return $this->buildCartReceipt($cart, 'PAYMENT RECEIPT', $meta);
        }
        return false;
    }

    private function tableName($tableId)
    {
        $table = $this->Tables_model->get((int) $tableId);
        return $table && isset($table->tbName) ? $table->tbName : 'N/A';
    }

    private function printerGeometry()
    {
        // Calibrated to the user's Epson printer; preserve the agreed 550-dot width.
        $leftMarginDots = 8;
        $printAreaDots = 550;
        $fontCharWidthDots = 12;
        $columns = max(1, (int) floor($printAreaDots / $fontCharWidthDots));
        return array($leftMarginDots, $printAreaDots, $columns);
    }

    private function escText($text)
    {
        $text = (string) $text;
        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'CP437//TRANSLIT//IGNORE', $text);
            if ($converted !== false) $text = $converted;
        }
        return $text;
    }

    private function wrapText($text, $width)
    {
        $text = trim($this->escText($text));
        if ($text === '') return array('');
        return explode("\n", wordwrap($text, max(1, (int) $width), "\n", true));
    }

    private function rowText($left, $right, $width)
    {
        $right = $this->escText($right);
        $left = $this->escText($left);
        $rightWidth = min(9, max(6, strlen($right)));
        $leftWidth = max(1, $width - $rightWidth);
        $lines = $this->wrapText($left, $leftWidth);
        $out = sprintf('%-' . $leftWidth . 's%' . $rightWidth . 's', array_shift($lines), $right) . "\n";
        foreach ($lines as $line) $out .= $line . "\n";
        return $out;
    }

    private function ticketStart($heading, $subheading = '', $showBusinessName = false)
    {
        list($left, $printWidth, $columns) = $this->printerGeometry();
        $ESC = "\x1B"; $GS = "\x1D";
        $ticket = $ESC . "@";
        $ticket .= $ESC . "j" . chr(18); // Existing calibrated top feed adjustment.
        $ticket .= $GS . "L" . pack('v', $left) . $GS . "W" . pack('v', $printWidth);
        $ticket .= $ESC . "a" . "\x01";
        if ($showBusinessName) {
            // Payment receipts start with a prominent, centered business name.
            $ticket .= $ESC . "E" . "\x01" . $GS . "!" . "\x11";
            $ticket .= $this->escText('ARSENIOS SILAY') . "\n";
            $ticket .= $GS . "!" . "\x00" . $ESC . "E" . "\x00";
        }
        $ticket .= $ESC . "E" . "\x01";
        $ticket .= $this->escText($heading) . "\n" . $ESC . "E" . "\x00";
        if ($subheading !== '') $ticket .= $this->escText($subheading) . "\n";
        $ticket .= date('M d, Y | h:i A') . "\n";
        $ticket .= $ESC . "a" . "\x00";
        $ticket .= str_repeat('-', $columns) . "\n";
        return array($ticket, $columns, str_repeat('-', $columns));
    }

    private function ticketEnd($ticket)
    {
        $ESC = "\x1B"; $GS = "\x1D";
        $ticket .= $ESC . "d" . chr(5); // Agreed bottom margin.
        $ticket .= $GS . "V" . "\x00";
        return $ticket;
    }

    private function buildKitchenTicket($item)
    {
        list($ticket, $columns, $rule) = $this->ticketStart('PRINT ORDER', strtoupper($item->tbName ? $item->tbName : 'N/A'));
        $qtyWidth = 7; $nameWidth = max(1, $columns - $qtyWidth);
        $ticket .= sprintf('%-' . $nameWidth . 's%' . $qtyWidth . 's', 'Name', 'Qty') . "\n" . $rule . "\n";
        $lines = $this->wrapText($item->prodName ? $item->prodName : 'Unknown product', $nameWidth);
        foreach ($lines as $i => $line) {
            $ticket .= $i === 0
                ? sprintf('%-' . $nameWidth . 's%' . $qtyWidth . 's', $line, 'x' . (int) $item->kitQty) . "\n"
                : $line . "\n";
        }
        $ticket .= $rule . "\n";
        return $this->ticketEnd($ticket);
    }

    private function buildCartReceipt($cart, $heading, $meta)
    {
        $tableName = isset($meta['table_name']) ? $meta['table_name'] : (isset($cart[0]->tbName) ? $cart[0]->tbName : 'N/A');
        list($ticket, $columns, $rule) = $this->ticketStart($heading, strtoupper($tableName), isset($meta['total']));
        $ticket .= $this->rowText('Item(s)', (string) count($cart), $columns);
        $ticket .= $rule . "\n";
        foreach ($cart as $item) {
            $qty = isset($item->ctQty) ? (int) $item->ctQty : 1;
            $price = isset($item->ctPrice) ? (float) $item->ctPrice : 0;
            $name = isset($item->prodName) ? $item->prodName : 'Item';
            $ticket .= $this->rowText('(x' . $qty . ') ' . $name, 'P' . number_format($price, 2), $columns);
        }
        $ticket .= $rule . "\n";
        if (isset($meta['total'])) {
            $ticket .= $this->rowText('Sales', 'P' . number_format($meta['sales'], 2), $columns);
            if (!empty($meta['other_charges'])) $ticket .= $this->rowText('Other charges', 'P' . number_format($meta['other_charges'], 2), $columns);
            $ticket .= $this->rowText('Discount', 'P' . number_format($meta['discount'], 2), $columns);
            $ticket .= $rule . "\n";
            $ticket .= $this->rowText('AMOUNT DUE', 'P' . number_format($meta['total'], 2), $columns);
            $ticket .= $this->rowText('Payment', 'P' . number_format($meta['payment'], 2), $columns);
            $ticket .= $this->rowText('Change', 'P' . number_format($meta['change'], 2), $columns);
            $ticket .= $rule . "\n";
            $ESC = "\x1B";
            $ticket .= $ESC . "a" . "\x01" . 'NOT AN OFFICIAL RECEIPT' . "\n" . $ESC . "a" . "\x00";
        } else {
            $ticket .= "\n";
            $ESC = "\x1B";
            $ticket .= $ESC . "a" . "\x01" . 'ORDER PREVIEW - NOT A PAYMENT' . "\n" . $ESC . "a" . "\x00";
        }
        return $this->ticketEnd($ticket);
    }

    private function sendRawToPrinter($ip, $payload)
    {
        $errno = 0; $errstr = '';
        $socket = @fsockopen($ip, 9100, $errno, $errstr, 3);
        if (!$socket) return false;
        stream_set_timeout($socket, 5);
        $offset = 0; $length = strlen($payload);
        while ($offset < $length) {
            $written = @fwrite($socket, substr($payload, $offset));
            if ($written === false || $written === 0) { fclose($socket); return false; }
            $offset += $written;
        }
        @fflush($socket);
        $meta = stream_get_meta_data($socket);
        fclose($socket);
        return empty($meta['timed_out']);
    }
}
