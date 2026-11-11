<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kitchen Printer Terminal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>
body{background:#f4f6f9;padding:22px}.terminal-wrap{max-width:1200px;margin:auto}.queue{background:#fff;border-radius:8px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.06)}.table td,.table th{vertical-align:middle}
</style>
</head>
<body>
<div class="terminal-wrap">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <div>
            <h2 class="mb-1">Kitchen Printer Terminal</h2>
            <div class="text-muted">Network printer: <strong><?=html_escape($printer_ip ?: 'Not configured')?></strong> · TCP port 9100 · Direct ESC/POS printing</div>
        </div>
        <strong><span style="color:#28a745">●</span> Terminal active</strong>
    </div>

    <div id="notice" class="alert alert-info">Waiting for kitchen orders…</div>

    <div class="queue">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Kitchen Print Queue</h4>
            <small class="text-muted">Auto-check every 3 seconds</small>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered mb-0">
                <thead class="thead-dark"><tr><th>Table Name</th><th>Product Name</th><th>Quantity</th><th>Time Ordered</th><th>Time Printed</th><th>Print Status</th></tr></thead>
                <tbody id="queue-body">
                <?php if (empty($items)): ?>
                    <tr><td colspan="6" class="text-center text-muted">No kitchen orders yet.</td></tr>
                <?php else: foreach ($items as $item): ?>
                    <tr>
                        <td><?=html_escape($item->tbName ?: 'N/A')?></td>
                        <td><?=html_escape($item->prodName ?: 'Unknown product')?></td>
                        <td><?= (int) $item->kitQty ?></td>
                        <td><?=html_escape($item->kitTimeOrdered)?></td>
                        <td><?=html_escape($item->kitTimePrinted ?: '—')?></td>
                        <td><?php if ((int) $item->kitPrinted === 1): ?><span class="badge badge-success">Printed</span><?php else: ?><span class="badge badge-warning">Queued</span><?php endif; ?></td>
                    </tr>
                <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
(function () {
    var busy = false;
    var pollUrl = <?=json_encode(site_url('terminal/kitchen/poll'))?>;
    var body = document.getElementById('queue-body');
    var notice = document.getElementById('notice');

    function esc(value) {
        return String(value == null ? '' : value).replace(/[&<>"']/g, function (c) {
            return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c];
        });
    }
    function render(items) {
        if (!items || !items.length) {
            body.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No kitchen orders yet.</td></tr>';
            return;
        }
        body.innerHTML = items.map(function (item) {
            var printed = Number(item.kitPrinted) === 1;
            return '<tr><td>' + esc(item.tbName || 'N/A') + '</td>' +
                '<td>' + esc(item.prodName || 'Unknown product') + '</td>' +
                '<td>' + esc(item.kitQty) + '</td>' +
                '<td>' + esc(item.kitTimeOrdered) + '</td>' +
                '<td>' + esc(item.kitTimePrinted || '—') + '</td>' +
                '<td>' + (printed ? '<span class="badge badge-success">Printed</span>' : '<span class="badge badge-warning">Queued</span>') + '</td></tr>';
        }).join('');
    }
    function poll() {
        if (busy) return;
        busy = true;
        fetch(pollUrl, {method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}})
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP ' + response.status);
                return response.json();
            })
            .then(function (data) {
                if (data.items) render(data.items);
                if (!data.ok && data.message) {
                    notice.className = 'alert alert-warning';
                    notice.textContent = data.message;
                } else if (data.failed && data.failed.length) {
                    notice.className = 'alert alert-danger';
                    notice.textContent = 'Printer send failed for order(s): ' + data.failed.map(function (x) { return x.id; }).join(', ') + '. Check printer IP, power and network.';
                } else if (data.printed && data.printed.length) {
                    notice.className = 'alert alert-success';
                    notice.textContent = 'Sent ' + data.printed.length + ' order item(s) directly to ' + data.printer_ip + ':9100.';
                } else {
                    notice.className = 'alert alert-info';
                    notice.textContent = 'Terminal active. Waiting for kitchen orders…';
                }
            })
            .catch(function (error) {
                notice.className = 'alert alert-danger';
                notice.textContent = 'Terminal polling error: ' + error.message;
            })
            .finally(function () { busy = false; });
    }
    poll();
    setInterval(poll, 3000);
})();
</script>
</body>
</html>
