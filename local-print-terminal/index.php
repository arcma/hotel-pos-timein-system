<?php
require __DIR__ . '/bootstrap.php';
$cashier = setting_value($pdo, 'cashier_ip');
$kitchen = setting_value($pdo, 'kitchen_ip');
$interval = max(2, (int) $config['poll_interval_seconds']);
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Local Print Terminal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>body{background:#f4f6f9;padding:22px}.terminal-wrap{max-width:1200px;margin:auto}.queue{background:#fff;border-radius:8px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.06)}.table td,.table th{vertical-align:middle}</style></head><body><div class="terminal-wrap">
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"><div><h2 class="mb-1">Print Terminal</h2><div class="text-muted">Cashier: <strong><?=htmlspecialchars($cashier ?: 'Not configured', ENT_QUOTES, 'UTF-8')?></strong> · Kitchen: <strong><?=htmlspecialchars($kitchen ?: 'Not configured', ENT_QUOTES, 'UTF-8')?></strong> · TCP <?= (int)$config['printer_port'] ?> · Direct ESC/POS</div></div><div><a class="btn btn-outline-secondary btn-sm mr-2" href="settings.php">Printer Settings</a><strong class="text-success">● Local terminal</strong></div></div>
<div id="notice" class="alert alert-info">Connecting to online print queue…</div><div class="queue"><div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Print Queue</h4><small class="text-muted">Auto-check every <?=$interval?> seconds</small></div>
<div class="table-responsive"><table class="table table-striped table-bordered mb-0"><thead class="thead-dark"><tr><th>Queue ID</th><th>Print Type</th><th>Reference</th><th>Created</th><th>Result</th></tr></thead><tbody id="queue-body"><tr><td colspan="5" class="text-center text-muted">Waiting for first poll…</td></tr></tbody></table></div></div></div>
<script>
(function(){var busy=false, body=document.getElementById('queue-body'), notice=document.getElementById('notice');
function esc(v){return String(v==null?'':v).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c];});}
function typeName(t){return Number(t)===1?'Payment receipt':(Number(t)===2?'Order preview':'Kitchen order');}
function renderQueue(jobs){if(!jobs||!jobs.length){body.innerHTML='<tr><td colspan="6" class="text-center text-muted">No print jobs yet.</td></tr>';return;}body.innerHTML=jobs.map(function(j){var ref=Number(j.prtType)===3?'Kitchen #'+j.prtKitchenID:'Reference #'+j.prtCartID;var printed=Number(j.prtStatus)===1;return '<tr><td>'+esc(j.prtID)+'</td><td>'+esc(typeName(j.prtType))+'</td><td>'+esc(ref)+'</td><td>'+esc(j.prtDateCreated)+'</td><td>'+esc(j.prtDatePrinted||'—')+'</td><td>'+(printed?'<span class="badge badge-success">Printed</span>':'<span class="badge badge-warning">Queued</span>')+'</td></tr>';}).join('');}
function run(){if(busy)return;busy=true;fetch('agent.php',{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(r){return r.json().then(function(d){if(!r.ok)throw new Error(d.message||('HTTP '+r.status));return d;});}).then(function(d){renderQueue(d.queue||[]);if(d.failed&&d.failed.length){notice.className='alert alert-danger';notice.textContent='Some jobs need attention. Failed jobs remain pending and will be retried.';}else{notice.className='alert alert-success';notice.textContent=d.printed&&d.printed.length?'Printed '+d.printed.length+' job(s). Online status updated.':'Connected. Waiting for print jobs…';}}).catch(function(e){notice.className='alert alert-danger';notice.textContent='Terminal/API error: '+e.message;}).finally(function(){busy=false;});}
run();setInterval(run,<?=$interval*1000?>);
})();
</script></body></html>
