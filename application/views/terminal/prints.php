<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Print Terminal</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
<style>body{background:#f4f6f9;padding:22px}.terminal-wrap{max-width:1200px;margin:auto}.queue{background:#fff;border-radius:8px;padding:20px;box-shadow:0 2px 12px rgba(0,0,0,.06)}.table td,.table th{vertical-align:middle}</style>
</head><body><div class="terminal-wrap">
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap"><div><h2 class="mb-1">Print Terminal</h2><div class="text-muted">Cashier: <strong><?=html_escape($cashier_ip ?: 'Not configured')?></strong> · Kitchen: <strong><?=html_escape($kitchen_ip ?: 'Not configured')?></strong> · TCP 9100 · Direct ESC/POS</div></div><strong class="text-success">● Terminal active</strong></div>
<div id="notice" class="alert alert-info">Checking print queue…</div>
<div class="queue"><div class="d-flex justify-content-between align-items-center mb-3"><h4 class="mb-0">Print Queue</h4><small class="text-muted">Auto-check every 3 seconds</small></div>
<div class="table-responsive"><table class="table table-striped table-bordered mb-0"><thead class="thead-dark"><tr><th>Queue ID</th><th>Print Type</th><th>Reference</th><th>Created</th><th>Printed</th><th>Status</th></tr></thead><tbody id="queue-body">
<?php if (empty($jobs)): ?><tr><td colspan="6" class="text-center text-muted">No print jobs yet.</td></tr><?php else: foreach ($jobs as $job): ?><tr><td><?= (int)$job->prtID ?></td><td><?= ((int)$job->prtType===1?'Payment receipt':((int)$job->prtType===2?'Order preview':'Kitchen order')) ?></td><td><?=html_escape($job->prtType==3 ? 'Kitchen #' . $job->prtKitchenID : 'Reference #' . $job->prtCartID)?></td><td><?=html_escape($job->prtDateCreated)?></td><td><?=html_escape($job->prtDatePrinted ?: '—')?></td><td><?php if ((int)$job->prtStatus===1): ?><span class="badge badge-success">Printed</span><?php else: ?><span class="badge badge-warning">Queued</span><?php endif; ?></td></tr><?php endforeach; endif; ?>
</tbody></table></div></div></div>
<script>
(function(){var busy=false, url=<?=json_encode(site_url('terminal/poll'))?>, body=document.getElementById('queue-body'), notice=document.getElementById('notice');
function esc(v){return String(v==null?'':v).replace(/[&<>"']/g,function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c];});}
function typeName(t){return Number(t)===1?'Payment receipt':(Number(t)===2?'Order preview':'Kitchen order');}
function render(jobs){if(!jobs||!jobs.length){body.innerHTML='<tr><td colspan="6" class="text-center text-muted">No print jobs yet.</td></tr>';return;}body.innerHTML=jobs.map(function(j){var ref=Number(j.prtType)===3?'Kitchen #'+j.prtKitchenID:'Reference #'+j.prtCartID;return '<tr><td>'+esc(j.prtID)+'</td><td>'+esc(typeName(j.prtType))+'</td><td>'+esc(ref)+'</td><td>'+esc(j.prtDateCreated)+'</td><td>'+esc(j.prtDatePrinted||'—')+'</td><td>'+(Number(j.prtStatus)===1?'<span class="badge badge-success">Printed</span>':'<span class="badge badge-warning">Queued</span>')+'</td></tr>';}).join('');}
function poll(){if(busy)return;busy=true;fetch(url,{method:'POST',headers:{'X-Requested-With':'XMLHttpRequest'}}).then(function(r){if(!r.ok)throw new Error('HTTP '+r.status);return r.json();}).then(function(d){if(d.jobs)render(d.jobs);if(d.failed&&d.failed.length){notice.className='alert alert-danger';notice.textContent='Some print jobs failed: '+d.failed.map(function(x){return '#'+x.id+' '+x.reason;}).join(' | ');}else if(d.printed&&d.printed.length){notice.className='alert alert-success';notice.textContent='Printed '+d.printed.length+' queued job(s).';}else{notice.className='alert alert-info';notice.textContent='Terminal active. Waiting for print jobs…';}}).catch(function(e){notice.className='alert alert-danger';notice.textContent='Terminal polling error: '+e.message;}).finally(function(){busy=false;});}
poll();setInterval(poll,3000);})();
</script></body></html>
