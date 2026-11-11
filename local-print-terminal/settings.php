<?php
require __DIR__ . '/bootstrap.php';
$message = ''; $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach (array('kitchen_ip', 'cashier_ip') as $key) {
        $value = trim((string) ($_POST[$key] ?? ''));
        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) $error = 'Please enter valid IPv4 addresses.';
    }
    if ($error === '') {
        $stmt = $pdo->prepare("INSERT INTO settings(setting_key,setting_value,updated_at) VALUES(?,?,datetime('now')) ON CONFLICT(setting_key) DO UPDATE SET setting_value=excluded.setting_value, updated_at=datetime('now')");
        foreach (array('kitchen_ip', 'cashier_ip') as $key) $stmt->execute(array($key, trim((string) ($_POST[$key] ?? ''))));
        $message = 'Printer settings saved.';
    }
}
$kitchen = setting_value($pdo, 'kitchen_ip'); $cashier = setting_value($pdo, 'cashier_ip');
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Local Printer Settings</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"><style>body{background:#f4f6f9;padding:24px}.settings{max-width:650px;margin:auto;background:white;padding:25px;border-radius:9px;box-shadow:0 2px 12px rgba(0,0,0,.06)}</style></head><body><div class="settings"><div class="d-flex justify-content-between align-items-center mb-4"><h3 class="mb-0">Printer Settings</h3><a href="index.php" class="btn btn-outline-secondary btn-sm">Back to Terminal</a></div>
<?php if($message):?><div class="alert alert-success"><?=htmlspecialchars($message,ENT_QUOTES,'UTF-8')?></div><?php endif;?><?php if($error):?><div class="alert alert-danger"><?=htmlspecialchars($error,ENT_QUOTES,'UTF-8')?></div><?php endif;?>
<form method="post"><div class="form-group"><label for="kitchen_ip">Kitchen Printer IP</label><input class="form-control" id="kitchen_ip" name="kitchen_ip" value="<?=htmlspecialchars($kitchen,ENT_QUOTES,'UTF-8')?>" placeholder="192.168.1.12"></div><div class="form-group"><label for="cashier_ip">Cashier Printer IP</label><input class="form-control" id="cashier_ip" name="cashier_ip" value="<?=htmlspecialchars($cashier,ENT_QUOTES,'UTF-8')?>" placeholder="192.168.1.12"></div><button class="btn btn-primary" type="submit">Save Settings</button></form><hr><small class="text-muted">These settings are stored in the local terminal database, not in the online POS database. Printer connection uses TCP port <?= (int)$config['printer_port']?>.</small></div></body></html>
