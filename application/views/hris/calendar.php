<!doctype html><html><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>HRIS Attendance</title><link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>"><style>body{font-family:Arial;background:#f4f5f7;padding:24px}.panel{background:white;padding:22px;border-radius:12px;box-shadow:0 5px 20px #0001}.top{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap}.calendar{display:grid;grid-template-columns:repeat(7,1fr);gap:8px}.day{min-height:95px;background:#fff;border:1px solid #ddd;border-radius:8px;padding:8px;font-size:12px}.head{font-weight:bold;text-align:center;background:#e9ecef;padding:8px;border-radius:6px}.mark{display:block;margin-top:5px;color:#227447;font-weight:bold}.muted{color:#999}.logout-countdown{font-size:13px;font-weight:bold;color:#a94442;margin-top:8px;text-align:right}.checkin-modal-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:1040}.checkin-modal{display:none;position:fixed;z-index:1050;left:50%;top:50%;transform:translate(-50%,-50%);width:min(440px,92vw);background:#fff;padding:24px;border-radius:12px;box-shadow:0 10px 35px #0003}.checkin-modal h3{margin-top:0}.modal-actions{display:flex;justify-content:flex-end;gap:10px;margin-top:20px}</style></head><body><div class="container panel"><div class="top"><div><h2>Attendance Calendar</h2><p>Welcome <?= html_escape($user->urName.' '.$user->urLname) ?></p></div><div style="text-align:right"><a class="btn btn-default" href="<?= site_url('hris/employee_logout?id=' . rawurlencode($calendar_id)) ?>">Log out</a><?php if (!empty($auto_logout_at)): ?><div class="logout-countdown" id="autoLogoutTimer" data-expires="<?= (int)$auto_logout_at ?>">Logout in: 05:00</div><?php endif; ?></div></div>
<?php foreach(array('hris_error'=>'danger','hris_success'=>'success') as $key=>$cls): if($m=$this->session->flashdata($key)): ?><div class="alert alert-<?= $cls ?>"><?= html_escape($m) ?></div><?php endif; endforeach; ?>
<?php if($today): ?><div class="alert alert-info">Today's time-in: <?= html_escape($today->userTimeIn) ?> · Check-out: <?= html_escape($today->userTimeOut?:'Not checked out') ?></div><?php else: ?><div class="alert alert-info">You have not recorded your time-in today.</div><?php endif; ?>
<?php if (!$today): ?>
<div class="checkin-modal-backdrop" id="checkinModalBackdrop"></div>
<div class="checkin-modal" id="checkinModal" role="dialog" aria-modal="true" aria-labelledby="checkinModalTitle">
  <h3 id="checkinModalTitle">Would you like to time in?</h3>
  <p>Confirm to record your time-in for today.</p>
  <div class="modal-actions">
    <button type="button" class="btn btn-default" id="dismissCheckinModal">Not now</button>
    <form method="post" action="<?= site_url('hris/punch') ?>" style="display:inline">
      <input type="hidden" name="id" value="<?= html_escape($calendar_id) ?>">
      <button type="submit" name="action" value="checkin" class="btn btn-success">Yes, time in</button>
    </form>
  </div>
</div>
<?php endif; ?>
<form method="post" action="<?= site_url('hris/punch') ?>" style="display:flex;gap:10px;margin-bottom:24px"><input type="hidden" name="id" value="<?= html_escape($calendar_id) ?>"><button name="action" value="checkin" class="btn btn-success" <?= !$can_checkin?'disabled':'' ?>>Time In</button><button name="action" value="checkout" class="btn btn-warning" <?= !$can_checkout?'disabled':'' ?>>Check Out</button></form>
<form method="get" action="<?= site_url('hris/calendar') ?>" class="form-inline" style="margin-bottom:15px"><input type="hidden" name="id" value="<?= html_escape($calendar_id) ?>"><label>Month</label> <input class="form-control" type="month" name="month" value="<?= html_escape($month) ?>"> <button class="btn btn-primary">View</button></form>
<?php $first=strtotime($month.'-01');$days=(int)date('t',$first);$offset=(int)date('N',$first)-1;$byDay=array();foreach($records as $r)$byDay[date('j',strtotime($r->userTimeIn))]=$r; ?><div class="calendar"><?php foreach(array('Mon','Tue','Wed','Thu','Fri','Sat','Sun') as $d): ?><div class="head"><?= $d ?></div><?php endforeach; ?><?php for($i=0;$i<$offset;$i++): ?><div class="day muted"></div><?php endfor; for($d=1;$d<=$days;$d++): ?><div class="day"><strong><?= $d ?></strong><?php if(isset($byDay[$d])): ?><span class="mark">In: <?= date('g:i A',strtotime($byDay[$d]->userTimeIn)) ?></span><span class="mark">Out: <?= $byDay[$d]->userTimeOut?date('g:i A',strtotime($byDay[$d]->userTimeOut)):'Pending' ?></span><?php else: ?><span class="muted">No attendance</span><?php endif; ?></div><?php endfor; ?></div><p style="margin-top:15px;color:#666">Attendance records are read-only. Time-in and time-out restrictions are temporarily disabled for testing.</p></div><?php if (!empty($auto_logout_at)): ?>
<script>
(function(){
  var timer = document.getElementById('autoLogoutTimer');
  if (!timer) return;
  var expires = parseInt(timer.getAttribute('data-expires'), 10) * 1000;
  var logoutUrl = <?= json_encode(site_url('hris/employee_logout?id=' . rawurlencode($calendar_id))) ?>;
  function tick(){
    var remaining = Math.max(0, Math.ceil((expires - Date.now()) / 1000));
    var minutes = String(Math.floor(remaining / 60)).padStart(2, '0');
    var seconds = String(remaining % 60).padStart(2, '0');
    timer.textContent = 'Logout in: ' + minutes + ':' + seconds;
    if (remaining <= 0) { window.location.replace(logoutUrl); return; }
    window.setTimeout(tick, 250);
  }
  tick();
})();
</script>
<?php endif; ?>
<?php if (!$today): ?>
<script>
(function(){
  var modal = document.getElementById('checkinModal');
  var backdrop = document.getElementById('checkinModalBackdrop');
  var dismiss = document.getElementById('dismissCheckinModal');
  function closeModal(){ modal.style.display='none'; backdrop.style.display='none'; }
  modal.style.display='block'; backdrop.style.display='block';
  dismiss.addEventListener('click', closeModal);
  backdrop.addEventListener('click', closeModal);
})();
</script>
<?php endif; ?></body></html>
