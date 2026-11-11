<?php
require __DIR__ . '/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_response(array('ok' => false, 'message' => 'POST required'), 405);
$lock = fopen(__DIR__ . '/data/agent.lock', 'c');
if (!$lock || !flock($lock, LOCK_EX | LOCK_NB)) json_response(array('ok' => true, 'busy' => true, 'printed' => array(), 'failed' => array()));
$printed = array(); $failed = array();
try {
    $data = api_request($config, 'pending');
    if (empty($data['ok']) || !isset($data['jobs']) || !is_array($data['jobs'])) throw new Exception('Invalid pending-jobs response from online API.');
    foreach ($data['jobs'] as $job) {
        $id = isset($job['prtID']) ? (int) $job['prtID'] : 0;
        if (!$id) continue;
        if (!empty($job['error'])) { $failed[] = array('prtID' => $id, 'message' => $job['error']); continue; }
        try {
            $type = (int) $job['prtType'];
            $ip = setting_value($pdo, $type === 3 ? 'kitchen_ip' : 'cashier_ip');
            $payload = base64_decode((string) ($job['payload_base64'] ?? ''), true);
            if ($payload === false || $payload === '') throw new Exception('Invalid or empty print payload.');
            send_to_printer($ip, $config['printer_port'], $payload);
            // Update the online queue only after the local printer accepted the full payload.
            $update = api_request($config, 'update-status', 'POST', array('prtID' => $id, 'prtStatus' => 1));
            if (empty($update['ok'])) throw new Exception('Print sent, but online status update was not confirmed.');
            $printed[] = array('prtID' => $id, 'prtType' => $type, 'printer_ip' => $ip);
        } catch (Throwable $e) {
            $failed[] = array('prtID' => $id, 'message' => $e->getMessage());
        }
    }
    // Refresh queue metadata after status updates so the terminal shows recent history.
    $latest = api_request($config, 'pending');
    json_response(array('ok' => empty($failed), 'printed' => $printed, 'failed' => $failed, 'queue' => isset($latest['queue']) ? $latest['queue'] : array()));
} catch (Throwable $e) {
    json_response(array('ok' => false, 'message' => $e->getMessage(), 'printed' => $printed, 'failed' => $failed), 502);
} finally {
    flock($lock, LOCK_UN); fclose($lock);
}
