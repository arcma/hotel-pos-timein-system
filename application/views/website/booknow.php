<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Book Now</title>
<link rel="stylesheet" href="<?=base_url('assets/css/website.css')?>">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
.booking-wrap{max-width:900px;margin:45px auto;padding:24px;background:#fff;border:1px solid #ddd;border-radius:12px}
.booking-grid{display:grid;grid-template-columns:1fr 1fr;gap:18px}
.booking-wrap label{display:block;font-weight:600;margin:12px 0 5px}
.booking-wrap input,.booking-wrap select,.booking-wrap textarea{width:100%;padding:11px;border:1px solid #ccc;border-radius:5px;box-sizing:border-box}
.booking-wrap button{margin-top:18px;padding:12px 20px;background:#182b28;color:#fff;border:0;border-radius:5px;cursor:pointer}\n.booking-wrap button:disabled{background:#999;cursor:not-allowed;opacity:.7}
.booking-summary{border:1px solid #ddd;border-radius:9px;padding:18px;margin:18px 0;background:#fafafa}
.summary-row{display:flex;justify-content:space-between;gap:16px;padding:8px 0}
.summary-total{border-top:1px solid #ccc;margin-top:8px;padding-top:14px;font-size:20px;font-weight:700}
.booking-message{margin:12px 0;padding:12px;border-radius:6px;background:#fff2df}
.date-help{font-size:12px;color:#666;margin-top:5px}
.flatpickr-day.reserved-day,.flatpickr-day.flatpickr-disabled.reserved-day{background:#f7dada!important;border-color:#e9baba!important;color:#9d3333!important;text-decoration:line-through;cursor:not-allowed}
@media(max-width:650px){.booking-grid{grid-template-columns:1fr}.booking-wrap{margin:20px 12px;padding:18px}}
</style>
</head>
<body>
<?php $this->load->view('website/partials/navigation'); ?>
<?php $values=isset($form_values)&&is_array($form_values)?$form_values:array(); ?>
<main class="booking-wrap">
 <p class="eyebrow">RESERVATIONS</p>
 <h1>Book Your Stay</h1>
 <?php if(!empty($message)):?><div class="booking-message"><?=html_escape($message)?></div><?php endif;?>
 <form id="bookingForm" method="post" action="<?=base_url('booknow/submit')?>">
  <div class="booking-grid">
   <div>
    <label for="roomID">Room</label>
    <select name="roomID" id="roomID" required>
     <option value="">Select a room</option>
     <?php foreach($rooms as $r):?>
      <option value="<?=$r->rmID?>" data-price="<?=html_escape((string)$r->rmPrice)?>" data-capacity="<?=html_escape((string)$r->rmCapacity)?>" <?=((int)($values['roomID']??$selected_room)===(int)$r->rmID)?'selected':''?>>
       <?=html_escape($r->rmTitle)?> — ₱<?=number_format((float)$r->rmPrice,2)?> / day
      </option>
     <?php endforeach;?>
    </select>
   </div>
   <div>
    <label for="dateRange">Booking date / date range</label>
    <input type="text" id="dateRange" placeholder="Select date or date range" readonly required>
    <input type="hidden" name="checkin" id="checkin" value="<?=html_escape($values['checkin']??'')?>">
    <input type="hidden" name="checkout" id="checkout" value="<?=html_escape($values['checkout']??'')?>">
    <div class="date-help">You may select one date for a one-day booking or a range. Reserved dates are disabled.</div>
   </div>
  </div>
  <section class="booking-summary" aria-live="polite">
   <h3>Booking Overview</h3>
   <div class="summary-row"><span>Room capacity</span><strong id="capacityValue">—</strong></div>
   <div class="summary-row"><span>Price per day</span><strong id="rateValue">—</strong></div>
   <div class="summary-row"><span>Number of days</span><strong id="daysValue">0</strong></div>
   <div class="summary-row summary-total"><span>Total</span><strong id="totalValue">₱0.00</strong></div>
  </section>
  <div class="booking-grid">
   <div><label for="guestName">Guest name</label><input id="guestName" name="guestName" value="<?=html_escape($values['guestName']??'')?>" required></div>
   <div><label for="guestEmail">Email</label><input type="email" id="guestEmail" name="guestEmail" value="<?=html_escape($values['guestEmail']??'')?>" required></div>
   <div><label for="guestPhone">Phone</label><input id="guestPhone" name="guestPhone" value="<?=html_escape($values['guestPhone']??'')?>"></div>
  </div>
  <label for="message">Message</label>
  <textarea id="message" name="message" rows="4"><?=html_escape($values['message']??'')?></textarea>
  <button type="submit" id="requestBookingButton" disabled>Request Booking</button>
 </form>
</main>
<?php $this->load->view('website/partials/footer'); ?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function(){
 const form=document.getElementById('bookingForm');
 const room=document.getElementById('roomID');
 const range=document.getElementById('dateRange');
 const ci=document.getElementById('checkin');
 const co=document.getElementById('checkout');
 const capacity=document.getElementById('capacityValue');
 const rate=document.getElementById('rateValue');
 const daysEl=document.getElementById('daysValue');
 const total=document.getElementById('totalValue');
 const requestButton=document.getElementById('requestBookingButton');
 const guestName=document.getElementById('guestName');
 const guestEmail=document.getElementById('guestEmail');
 let reserved=[],calendar=null;
 const money=n=>'₱'+Number(n||0).toLocaleString('en-PH',{minimumFractionDigits:2,maximumFractionDigits:2});
 function selectedRoom(){return room.options[room.selectedIndex]||null;}
 function updateOverview(){
  const opt=selectedRoom(),price=opt&&opt.value?Number(opt.dataset.price||0):0;
  const cap=opt&&opt.value?opt.dataset.capacity:'';
  const days=ci.value?(co.value?Math.max(1,Math.round((new Date(co.value+'T00:00:00')-new Date(ci.value+'T00:00:00'))/86400000)+1):1):0;
  capacity.textContent=cap?cap+' guests':'—';
  rate.textContent=opt&&opt.value?money(price)+' / day':'—';
  daysEl.textContent=String(days);
  total.textContent=money(price*days);
  updateSubmitState();
 }
 function updateSubmitState(){
  const emailValid=guestEmail.value.trim()!=='' && guestEmail.validity.valid;
  const requiredFieldsValid=!!room.value && !!ci.value && guestName.value.trim()!=='' && emailValid;
  requestButton.disabled=!requiredFieldsValid;
 }
 function loadReserved(){
  reserved=[];if(!room.value){if(calendar)calendar.set('disable',[]);updateOverview();return;}
  fetch('<?=base_url('booknow/reserved-dates/')?>'+encodeURIComponent(room.value))
   .then(r=>r.json()).then(data=>{
    reserved=data.success&&Array.isArray(data.dates)?data.dates:[];
    if(calendar)calendar.set('disable',[...reserved,function(date){return date<new Date(new Date().setHours(0,0,0,0));}]);
   }).catch(()=>{reserved=[];});
  updateOverview();
 }
 if(window.flatpickr){
  calendar=flatpickr(range,{
   mode:'range',dateFormat:'Y-m-d',minDate:'today',
   onChange:function(selected){
    ci.value=selected.length?calendar.formatDate(selected[0],'Y-m-d'):'';
    co.value=selected.length>1?calendar.formatDate(selected[selected.length-1],'Y-m-d'):'';
    updateOverview();
    updateSubmitState();
   },
   onDayCreate:function(dObj,dStr,fp,dayElem){
    const key=fp.formatDate(dayElem.dateObj,'Y-m-d');
    if(reserved.indexOf(key)!==-1)dayElem.classList.add('reserved-day');
   }
  });
  const initialIn=ci.value,initialOut=co.value;
  if(initialIn){
   const dates=[calendar.parseDate(initialIn,'Y-m-d')];
   if(initialOut)dates.push(calendar.parseDate(initialOut,'Y-m-d'));
   calendar.setDate(dates,false);
  }
 }
 room.addEventListener('change',function(){
  ci.value='';co.value='';if(calendar)calendar.clear();loadReserved();updateSubmitState();
 });
 [guestName,guestEmail].forEach(function(field){
  field.addEventListener('input',updateSubmitState);
  field.addEventListener('change',updateSubmitState);
 });
 if(room.value)loadReserved();
 updateOverview();
 updateSubmitState();
 form.addEventListener('submit',function(e){
  updateSubmitState();
  if(requestButton.disabled){e.preventDefault();guestEmail.reportValidity();return;}
 });
})();
</script>
</body>
</html>
