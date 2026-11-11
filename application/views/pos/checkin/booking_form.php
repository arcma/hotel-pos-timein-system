<?php defined('BASEPATH') OR exit('No direct script access allowed');
$isReservation = !empty($form_mode) && $form_mode === 'reservation';
$existing = isset($reservation) ? $reservation : null;
$roomPrice = (float)$room->rmPrice;
$days = $existing ? max(1,(int)($existing->ckDays ?? 1)) : 1;
if (!$existing && !empty($this->input->get('range',true))) {
    $rangeParts = preg_split('/\s+to\s+/i', trim((string)$this->input->get('range',true)));
    if (count($rangeParts) === 2) {
        $rangeStart = DateTime::createFromFormat('!Y-m-d', $rangeParts[0]);
        $rangeEnd = DateTime::createFromFormat('!Y-m-d', $rangeParts[1]);
        if ($rangeStart && $rangeEnd && $rangeEnd >= $rangeStart) $days = (int)$rangeStart->diff($rangeEnd)->days + 1;
    }
}
$other = $existing ? (float)($existing->ckOtherCharges ?? 0) : 0;
$fixDiscount = $existing ? (float)($existing->ckFixDiscount ?? 0) : 0;
$discountID = $existing ? (int)($existing->ckRoomDiscountID ?? 0) : 0;
$discountAmount = $existing ? max(0, (float)($existing->ckDiscountAmount ?? 0) - (float)($existing->ckFixDiscount ?? 0)) : 0;
$total = $existing ? (float)($existing->ckPrice ?? ($roomPrice*$days - $discountAmount + $other - $fixDiscount)) : $roomPrice*$days;
$selectedDate = isset($date) ? $date : date('Y-m-d');
$readonly = !empty($read_only);
$reservedDates = isset($reserved_dates) ? $reserved_dates : array();
$formValues = isset($form_values) && is_array($form_values) ? $form_values : array();
$formValue = function($key, $fallback='') use ($formValues) { return array_key_exists($key,$formValues) ? $formValues[$key] : $fallback; };
$rangeValue = $existing ? ($existing->ckDate.' to '.date('Y-m-d',strtotime('+'.$days.' day',strtotime($existing->ckDate)))) : trim((string)$formValue('booking_range', $this->input->get('range',true) ?: ''));
if ($rangeValue === '' && !$isReservation) $rangeValue = $selectedDate;
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.booking-form-shell{width:100%;max-width:none;margin:0;background:#fff;border:1px solid #ddd;border-radius:10px;padding:22px;box-sizing:border-box}
.booking-form-grid{display:grid;grid-template-columns:minmax(0,1.35fr) minmax(280px,1fr);gap:24px}
.booking-form-shell h2{margin-top:0;margin-bottom:20px}.booking-form-shell .form-group{margin-bottom:15px}
.booking-preview{background:#f6f8fa;border:1px solid #e2e6ea;border-radius:8px;padding:18px;height:fit-content}
.booking-preview h3{margin-top:0}.booking-preview-line{display:flex;justify-content:space-between;gap:16px;padding:8px 0;border-bottom:1px solid #e1e5e9}.booking-preview-total{font-size:20px;font-weight:700;padding-top:14px}.change-negative{color:#dc3545!important;font-weight:700}.change-positive{color:#198754!important;font-weight:700}
.flatpickr-day.reserved-day,.flatpickr-day.flatpickr-disabled.reserved-day{background:#f7dada!important;border-color:#e9baba!important;color:#9d3333!important;text-decoration:line-through;cursor:not-allowed}
@media(max-width:760px){.booking-form-grid{grid-template-columns:1fr}.booking-form-shell{padding:14px}}
</style>
<div class="container-fluid booking-form-shell">
  <h2><?= $isReservation ? ($readonly ? 'Reservation Details' : 'New Reservation') : 'Check-in' ?> — <?= html_escape($room->rmTitle) ?></h2>
  <?php foreach(array('success'=>'success','error'=>'danger') as $key=>$cls): if($m=$this->session->flashdata($key)): ?><div class="alert alert-<?= $cls ?>"><?= html_escape($m) ?></div><?php endif; endforeach; ?>
  <?php if (!empty($stay)): ?>
    <div class="alert alert-warning">This room is already occupied. <a href="<?= site_url('pos/checkin/orders/'.$stay->stayID) ?>">Open room orders</a></div>
  <?php else: ?>
  <form method="post" action="<?= site_url($isReservation ? 'pos/checkin/do_book/'.$room->rmID : 'pos/checkin/do_start/'.$room->rmID) ?>" id="booking-form">
    <input type="hidden" name="booking_date" value="<?= html_escape($formValue('booking_date',$selectedDate)) ?>">
    <div class="booking-form-grid">
      <div>
        <?php if(true): ?>
          <div class="form-group"><label for="booking-range">Check-in / Check-out</label><input type="text" class="form-control" id="booking-range" name="booking_range" placeholder="Select check-in and check-out" value="<?= html_escape($rangeValue) ?>" <?= $readonly?'readonly':'required' ?>><small class="text-muted">Reserved dates are disabled. Pricing is calculated per day (night).</small></div>
          <?php endif; ?>
        <div class="form-group"><label for="guest-name">Guest name <span class="text-danger">* Required</span></label><input class="form-control" id="guest-name" name="guest_name" value="<?= html_escape($formValue('guest_name',$existing->ckGuestName ?? '')) ?>" <?= $readonly ? 'readonly' : 'required' ?>></div>
        <div class="form-group"><label for="guest-email">Guest email</label><input class="form-control" type="email" id="guest-email" name="guest_email" value="<?= html_escape($formValue('guest_email',$existing->ckGuestEmail ?? '')) ?>" <?= $readonly?'readonly':'' ?>></div>
        <div class="form-group"><label for="guest-phone">Guest phone</label><input class="form-control" type="tel" id="guest-phone" name="guest_phone" value="<?= html_escape($formValue('guest_phone',$existing->ckGuestPhone ?? '')) ?>" <?= $readonly?'readonly':'' ?>></div>
        <div class="form-group"><label for="guest-message">Message</label><textarea class="form-control" id="guest-message" name="message" rows="3" <?= $readonly?'readonly':'' ?>><?= html_escape($formValue('message',$existing->ckMessage ?? '')) ?></textarea></div>
        <div class="form-group"><label>Room discount options</label>
          <?php if ($readonly): ?><input class="form-control" value="<?= $discountID ? 'Discount applied ('.number_format($discountAmount,2).')' : 'No room discount' ?>" readonly>
          <?php else: ?><select class="form-control" name="room_discount_id" id="room-discount"><option value="">No discount</option><?php foreach(($discounts ?? array()) as $d): ?><option value="<?= (int)$d->disrID ?>" data-percent="<?= (float)$d->disrPercent ?>" <?= (int)$formValue('room_discount_id',$discountID) === (int)$d->disrID ? 'selected' : '' ?>><?= html_escape($d->disrName) ?> (<?= (float)$d->disrPercent ?>%)</option><?php endforeach; ?></select><?php endif; ?>
        </div>
        <div class="form-group"><label>Fix Discount</label><input class="form-control" type="number" min="0" step="0.01" name="fix_discount" id="fix-discount" value="<?= html_escape($formValue('fix_discount',number_format($fixDiscount,2,'.',''))) ?>" <?= $readonly ? 'readonly' : '' ?>></div>
        <div class="form-group"><label>Other charges</label><input class="form-control" type="number" min="0" step="0.01" name="other_charges" id="other-charges" value="<?= html_escape($formValue('other_charges',number_format($other,2,'.',''))) ?>" <?= $readonly ? 'readonly' : 'required' ?>></div>
        <div class="form-group"><label>Payment type</label><select class="form-control" name="payment_type" id="payment-type" <?= $readonly?'disabled':'' ?>><option value="cash" <?= $formValue('payment_type','cash')==='cash' ? 'selected' : '' ?>>Cash</option><option value="card" <?= $formValue('payment_type','cash')==='card' ? 'selected' : '' ?>>Card</option></select></div>
        <div class="form-group"><label>Payment</label><input class="form-control" type="number" name="payment" id="payment" min="0" step="0.01" required <?= $readonly?'readonly':'' ?>></div>
        <div class="form-group"><label for="change-amount">Change</label><input class="form-control change-negative" type="text" id="change-amount" value="-<?= number_format($total, 2, '.', '') ?>" readonly></div>
      </div>
      <aside class="booking-preview" aria-live="polite">
        <h3>Preview</h3>
        <div class="booking-preview-line"><span>Room rate × <span id="preview-days"><?= $days ?></span> day(s)</span><strong>₱<span id="preview-price"><?= number_format($roomPrice*$days,2) ?></span></strong></div>
        <div class="booking-preview-line"><span>Room discount</span><strong>− ₱<span id="preview-room-discount"><?= number_format($discountAmount,2) ?></span></strong></div>
        <div class="booking-preview-line"><span>Fix discount</span><strong>− ₱<span id="preview-fix-discount"><?= number_format($fixDiscount,2) ?></span></strong></div>
        <div class="booking-preview-line"><span>Other charges</span><strong>+ ₱<span id="preview-other"><?= number_format($other,2) ?></span></strong></div>
        <div class="booking-preview-total">Total: ₱<span id="preview-total"><?= number_format($total,2) ?></span></div>
      </aside>
    </div>
    <?php if (!$readonly): ?><button type="submit" class="btn btn-primary" id="confirm-checkin" disabled><?= $isReservation ? 'Save Reservation' : 'Confirm Check-in' ?></button><?php endif; ?>
    <a class="btn btn-default" href="<?= site_url('pos/checkin?date='.rawurlencode($selectedDate)) ?>">Back to Booking</a>
  </form>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function(){
 var isReservation=<?= $isReservation?'true':'false' ?>, roomRate=<?= json_encode($roomPrice) ?>;
 var reserved=<?= json_encode(array_values($reservedDates)) ?>;
 var range=document.getElementById('booking-range'), daysField=document.getElementById('booking-days');
 var discount=document.getElementById('room-discount'), fix=document.getElementById('fix-discount'), other=document.getElementById('other-charges');
 var payment=document.getElementById('payment'), changeField=document.getElementById('change-amount'), submit=document.getElementById('confirm-checkin'), guest=document.getElementById('guest-name');
 var days=<?= (int)$days ?>;
 function money(n){return (Math.round((n+Number.EPSILON)*100)/100).toFixed(2);}
 function totalCalc(){
   var base=roomRate*days,pct=discount&&discount.selectedOptions.length?(parseFloat(discount.selectedOptions[0].dataset.percent)||0):0;
   var roomDisc=Math.round(base*pct)/100;roomDisc=Math.round(roomDisc*100)/100;
   var fixed=Math.min(Math.max(0,parseFloat(fix&&fix.value)||0),Math.max(0,base-roomDisc));
   var charges=Math.max(0,parseFloat(other&&other.value)||0),total=Math.max(0,base-roomDisc-fixed+charges);
   document.getElementById('preview-days').textContent=days;
   document.getElementById('preview-price').textContent=money(base);
   document.getElementById('preview-room-discount').textContent=money(roomDisc);
   document.getElementById('preview-fix-discount').textContent=money(fixed);
   document.getElementById('preview-other').textContent=money(charges);
   document.getElementById('preview-total').textContent=money(total);
   return total;
 }
 function update(){
   var total=totalCalc(),paid=Math.max(0,parseFloat(payment&&payment.value)||0),change=paid-total;
   if(changeField){changeField.value=money(change);changeField.classList.toggle('change-positive',change>=0);changeField.classList.toggle('change-negative',change<0);}
   if(submit)submit.disabled=!(change>=0&&guest&&guest.value.trim()&&days>0);
 }
 [discount,fix,other,payment,guest].forEach(function(el){if(el){el.addEventListener('input',update);el.addEventListener('change',update);}});
 if(range&&window.flatpickr&&!range.readOnly){
   flatpickr(range,{mode:'range',dateFormat:'Y-m-d',minDate:<?= json_encode($selectedDate) ?>,defaultDate:<?= json_encode($rangeValue) ?>,allowInput:true,disable:reserved,onDayCreate:function(dObj,dStr,fp,day){var key=day.dateObj.getFullYear()+'-'+String(day.dateObj.getMonth()+1).padStart(2,'0')+'-'+String(day.dateObj.getDate()).padStart(2,'0');if(reserved.indexOf(key)!==-1)day.classList.add('reserved-day');},onChange:function(sel,str){
     days=sel.length===2?Math.round((sel[1]-sel[0])/86400000)+1:(sel.length===1?1:0);
     if(daysField)daysField.value=days;var hidden=document.querySelector('input[name=booking_date]');if(hidden&&sel.length)hidden.value=sel[0].getFullYear()+'-'+String(sel[0].getMonth()+1).padStart(2,'0')+'-'+String(sel[0].getDate()).padStart(2,'0');
     update();
   }});
 }
 update();
})();
</script>
