<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Server-side ESC/POS receipt and kitchen-ticket templates.
 *
 * This library runs on the online POS server. The local print agent receives
 * the finished ESC/POS payload from the API and does not need template code.
 */
class Print_template
{
    /** Build a kitchen ticket. Kept for compatibility with older single-item jobs. */
    public function buildKitchenTicket($item)
    {
        return $this->buildKitchenBatchTicket(array($item));
    }

    /** Build one kitchen ticket containing every newly ordered item. */
    public function buildKitchenBatchTicket($items)
    {
        if (empty($items)) {
            return false;
        }

        $firstItem = reset($items);
        $tableName = !empty($firstItem->tbName) ? $firstItem->tbName : 'N/A';

        // Add approximately 10px worth of extra top feed while keeping the kitchen heading centered.
        list($ticket, $columns, $rule) = $this->ticketStart(
            'PRINT ORDER',
            strtoupper($tableName),
            false,
            44
        );

        $qtyWidth = 7;
        $nameWidth = max(1, $columns - $qtyWidth);

        $ticket .= sprintf('%-' . $nameWidth . 's%' . $qtyWidth . 's', 'Name', 'Qty') . "\n";
        $ticket .= $rule . "\n";

        foreach ($items as $item) {
            $productName = !empty($item->prodName) ? $item->prodName : 'Unknown product';
            $lines = $this->wrapText($productName, $nameWidth);

            foreach ($lines as $index => $line) {
                if ($index === 0) {
                    $ticket .= sprintf(
                        '%-' . $nameWidth . 's%' . $qtyWidth . 's',
                        $line,
                        'x' . (int) $item->kitQty
                    ) . "\n";
                } else {
                    $ticket .= $line . "\n";
                }
            }
        }

        $ticket .= $rule . "\n";

        return $this->ticketEnd($ticket);
    }

    /** Build an ESC/POS order preview or completed-payment receipt. */
    public function buildCartReceipt($cart, $heading, $meta)
    {
        $tableName = isset($meta['table_name'])
            ? $meta['table_name']
            : (isset($cart[0]->tbName) ? $cart[0]->tbName : 'N/A');

        list($ticket, $columns, $rule) = $this->ticketStart(
            $heading,
            strtoupper($tableName),
            isset($meta['total'])
        );

        $ticket .= $this->rowText('Item(s)', (string) count($cart), $columns);
        $ticket .= $rule . "\n";

        foreach ($cart as $item) {
            $quantity = isset($item->ctQty) ? (int) $item->ctQty : 1;
            $price = isset($item->ctPrice) ? (float) $item->ctPrice : 0;
            $name = isset($item->prodName) ? $item->prodName : 'Item';

            $ticket .= $this->rowText(
                '(x' . $quantity . ') ' . $name,
                'P' . number_format($price, 2),
                $columns
            );
        }

        $ticket .= $rule . "\n";

        if (isset($meta['total'])) {
            $ticket .= $this->rowText('Sales', 'P' . number_format($meta['sales'], 2), $columns);

            if (!empty($meta['other_charges'])) {
                $ticket .= $this->rowText(
                    'Other charges',
                    'P' . number_format($meta['other_charges'], 2),
                    $columns
                );
            }

            $ticket .= $this->rowText('Discount', 'P' . number_format($meta['discount'], 2), $columns);
            $ticket .= $rule . "\n";
            $ticket .= $this->rowText('AMOUNT DUE', 'P' . number_format($meta['total'], 2), $columns);
            $ticket .= $this->rowText('Payment', 'P' . number_format($meta['payment'], 2), $columns);
            $ticket .= $this->rowText('Change', 'P' . number_format($meta['change'], 2), $columns);
            $ticket .= $rule . "\n";

            $ESC = "\x1B";
            $ticket .= $ESC . "a" . "\x01" . 'NOT AN OFFICIAL RECEIPT' . "\n";
            $ticket .= $ESC . "a" . "\x00";
        } else {
            $ticket .= "\n";

            $ESC = "\x1B";
            $ticket .= $ESC . "a" . "\x01" . 'ORDER PREVIEW - NOT A PAYMENT' . "\n";
            $ticket .= $ESC . "a" . "\x00";
        }

        return $this->ticketEnd($ticket);
    }

    /** Printer width and margin values calibrated for the current printer. */
    private function printerGeometry()
    {
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
            if ($converted !== false) {
                $text = $converted;
            }
        }

        return $text;
    }

    private function wrapText($text, $width)
    {
        $text = trim($this->escText($text));

        if ($text === '') {
            return array('');
        }

        return explode("\n", wordwrap($text, max(1, (int) $width), "\n", true));
    }

    private function rowText($left, $right, $width)
    {
        $right = $this->escText($right);
        $left = $this->escText($left);
        $rightWidth = min(9, max(6, strlen($right)));
        $leftWidth = max(1, $width - $rightWidth);
        $lines = $this->wrapText($left, $leftWidth);

        $output = sprintf(
            '%-' . $leftWidth . 's%' . $rightWidth . 's',
            array_shift($lines),
            $right
        ) . "\n";

        foreach ($lines as $line) {
            $output .= $line . "\n";
        }

        return $output;
    }

    private function ticketStart($heading, $subheading = '', $showBusinessName = false, $topFeedDots = 18)
    {
        list($left, $printWidth, $columns) = $this->printerGeometry();

        $ESC = "\x1B";
        $GS = "\x1D";
        $ticket = $ESC . "@";
        $ticket .= $ESC . "j" . chr(max(0, min(255, (int) $topFeedDots)));
        $ticket .= $GS . "L" . pack('v', $left) . $GS . "W" . pack('v', $printWidth);
        $ticket .= $ESC . "a" . "\x01";

        if ($showBusinessName) {
            $ticket .= $ESC . "E" . "\x01" . $GS . "!" . "\x11";
            $ticket .= $this->escText('ARSENIOS SILAY') . "\n";
            $ticket .= $GS . "!" . "\x00" . $ESC . "E" . "\x00";
        }

        $ticket .= $ESC . "a" . "\x01" . $ESC . "E" . "\x01";
        $ticket .= $this->escText($heading) . "\n" . $ESC . "E" . "\x00";

        if ($subheading !== '') {
            $ticket .= $ESC . "a" . "\x01" . $this->escText($subheading) . "\n";
        }

        $ticket .= $ESC . "a" . "\x01" . date('M d, Y | h:i A') . "\n";
        $ticket .= $ESC . "a" . "\x00";
        $ticket .= str_repeat('-', $columns) . "\n";

        return array($ticket, $columns, str_repeat('-', $columns));
    }

    private function ticketEnd($ticket)
    {
        $ESC = "\x1B";
        $GS = "\x1D";

        $ticket .= $ESC . "d" . chr(5);
        $ticket .= $GS . "V" . "\x00";

        return $ticket;
    }
}
