<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid" style="background:#fff;border:1px solid #ccc;border-radius:10px;padding:20px">
  <h2>HRIS — Employee Attendance</h2>
  <form method="get" class="form-inline" style="margin:15px 0;display:flex;gap:8px;align-items:center;flex-wrap:wrap">
    <label for="hris_date_range">Date range</label>
    <input class="form-control" style="min-width:230px" type="text" id="hris_date_range" name="range" value="<?= html_escape($range) ?>" autocomplete="off" readonly>
    <label for="hris_employee">Employee</label>
    <select class="form-control" id="hris_employee" name="user"><option value="">All employees</option><?php foreach($employees as $e): ?><option value="<?= (int)$e->urID ?>" <?= $selected_user===(int)$e->urID?'selected':'' ?>><?= html_escape($e->urName.' '.$e->urLname) ?></option><?php endforeach; ?></select>
    <button class="btn btn-primary">Filter</button>
  </form>
  <div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Employee</th><th>Date</th><th>Check-in</th><th>Check-out</th><th>Hours</th></tr></thead><tbody><?php foreach($records as $r): $mins=$r->userTimeOut?max(0,strtotime($r->userTimeOut)-strtotime($r->userTimeIn)):null; ?><tr><td><?= html_escape(trim(($r->urName??'').' '.($r->urLname??''))) ?></td><td><?= date('Y-m-d',strtotime($r->userTimeIn)) ?></td><td><?= date('g:i A',strtotime($r->userTimeIn)) ?></td><td><?= $r->userTimeOut?date('g:i A',strtotime($r->userTimeOut)):'—' ?></td><td><?= $mins===null?'In progress':number_format($mins/3600,2).' h' ?></td></tr><?php endforeach; if(!$records): ?><tr><td colspan="5">No attendance records for this filter.</td></tr><?php endif; ?></tbody></table></div>
</div>
<script>
$(function () {
  var defaultDate = moment().format('MM/DD/YYYY');
  $('#hris_date_range').daterangepicker({
    startDate: moment(),
    endDate: moment(),
    autoApply: true,
    opens: 'left',
    locale: { format: 'MM/DD/YYYY', separator: ' - ', applyLabel: 'Apply', cancelLabel: 'Cancel' }
  });
});
</script>
