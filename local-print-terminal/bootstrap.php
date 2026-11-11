<?php
$config = require __DIR__ . '/config.php';
if (!extension_loaded('pdo_sqlite')) { http_response_code(500); exit('PHP pdo_sqlite extension is required.'); }
$dbDir = dirname($config['database_path']);
if (!is_dir($dbDir)) mkdir($dbDir, 0775, true);
$pdo = new PDO('sqlite:' . $config['database_path']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec("CREATE TABLE IF NOT EXISTS settings (setting_key TEXT PRIMARY KEY, setting_value TEXT NOT NULL DEFAULT '', updated_at TEXT NOT NULL)");
foreach (array('kitchen_ip' => '', 'cashier_ip' => '') as $key => $value) {
    $stmt = $pdo->prepare("INSERT OR IGNORE INTO settings(setting_key, setting_value, updated_at) VALUES (?, ?, datetime('now'))");
    $stmt->execute(array($key, $value));
}
function setting_value($pdo, $key) {
    $stmt = $pdo->prepare('SELECT setting_value FROM settings WHERE setting_key = ?');
    $stmt->execute(array($key));
    return (string) $stmt->fetchColumn();
}
function json_response($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}
function api_request($config, $path, $method = 'GET', $body = null) {
    if (!function_exists('curl_init')) throw new Exception('PHP cURL extension is required.');
    $url = rtrim($config['api_base_url'], '/') . '/' . ltrim($path, '/');
    $ch = curl_init($url);
    $headers = array('Accept: application/json', 'Authorization: Bearer ' . $config['api_token']);
    curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT => 25,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
    ));
    if ($body !== null) {
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
    }
    $response = curl_exec($ch);
    $error = curl_error($ch);
    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if ($response === false) throw new Exception('API connection failed: ' . $error);
    $decoded = json_decode($response, true);
    if ($status < 200 || $status >= 300 || !is_array($decoded)) {
        $message = is_array($decoded) && isset($decoded['message']) ? $decoded['message'] : substr((string) $response, 0, 250);
        throw new Exception('Online API returned HTTP ' . $status . ': ' . $message);
    }
    return $decoded;
}
function send_to_printer($ip, $port, $payload) {
    if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) throw new Exception('Printer IP is missing or invalid.');
    $errno = 0; $errstr = '';
    $socket = @fsockopen($ip, (int) $port, $errno, $errstr, 4);
    if (!$socket) throw new Exception('Printer connection failed: ' . $errstr . ' (' . $errno . ')');
    stream_set_timeout($socket, 8);
    $offset = 0; $length = strlen($payload);
    while ($offset < $length) {
        $written = @fwrite($socket, substr($payload, $offset));
        if ($written === false || $written === 0) { fclose($socket); throw new Exception('Printer connection closed before all data was sent.'); }
        $offset += $written;
    }
    @fflush($socket);
    $meta = stream_get_meta_data($socket);
    fclose($socket);
    if (!empty($meta['timed_out'])) throw new Exception('Printer connection timed out.');
    return true;
}
