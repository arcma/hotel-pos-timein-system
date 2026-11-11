<?php defined('BASEPATH') OR exit('No direct script access allowed');
$selectedDate = isset($selected_date) ? $selected_date : date('Y-m-d');
$today = isset($today) ? $today : date('Y-m-d');
$isToday = $selectedDate === $today;
$isPast = $selectedDate < $today;
$dateTitle = ($isToday ? 'Today · ' : '') . date('l, F j, Y', strtotime($selectedDate));
$e = function($v) { return html_escape((string)$v); };
?>
<style>
.booking-page{background:#fff;border:1px solid #ddd;border-radius:10px;padding:20px;margin-bottom:20px}
.booking-datebar{display:flex;align-items:center;justify-content:space-between;gap:15px;flex-wrap:wrap;margin-bottom:20px}
.booking-date-title{font-size:25px;font-weight:650;color:#263445}
.booking-date-controls{display:flex;align-items:center;gap:7px}
.booking-date-controls input{height:36px;border:1px solid #ccc;border-radius:4px;padding:5px 9px}
.booking-date-controls button{height:36px;min-width:36px;border:1px solid #ccc;border-radius:4px;background:#fff;font-size:22px;line-height:1}
.booking-section{margin-top:22px}.booking-section h3{font-size:18px;font-weight:650;margin:0 0 12px;color:#263445}
.booking-table{width:100%;border-collapse:collapse;background:#fff}.booking-table th,.booking-table td{padding:11px 13px;border:1px solid #e2e6ea;text-align:left;vertical-align:middle}.booking-table th{background:#f4f6f8;font-weight:650}.booking-room-link{color:#2367a6;font-weight:600;cursor:pointer;text-decoration:underline}.booking-reserved-row{background:#fffaf0}.booking-status-booked{background:#d9f0df;color:#216b37}.booking-status{display:inline-block;padding:4px 8px;border-radius:4px;background:#fff0c2;color:#7b5700;font-size:12px;font-weight:650}.booking-empty{color:#777;text-align:center!important;padding:20px!important}
#bookingRoomModal .modal-body{line-height:1.65}.booking-room-photo{width:100%;max-height:260px;object-fit:cover;border-radius:7px;background:#f2f2f2;min-height:150px}.booking-action-cell{min-width:150px}
@media(max-width:700px){.booking-page{padding:12px}.booking-date-title{font-size:20px}.booking-table th,.booking-table td{padding:8px;font-size:13px}.booking-table-wrap{overflow-x:auto}}
.flatpickr-day.reserved-day,.flatpickr-day.flatpickr-disabled.reserved-day{background:#f7dada!important;border-color:#e9baba!important;color:#9d3333!important;text-decoration:line-through;cursor:not-allowed}
</style>
<div class="booking-page container-fluid">
  <div class="booking-datebar">
    <div class="booking-date-title"><?= $e($dateTitle) ?></div>
    <form method="get" action="<?= site_url('pos/checkin') ?>" id="booking-date-form" class="booking-date-controls">
      <button type="button" id="booking-prev" aria-label="Previous date">‹</button>
      <input type="date" id="booking-date" name="date" value="<?= $e($selectedDate) ?>" aria-label="Select booking date">
      <button type="button" id="booking-next" aria-label="Next date">›</button>
    </form>
  </div>

  <?php foreach(array('success'=>'success','error'=>'danger') as $key=>$cls): if($m=$this->session->flashdata($key)): ?>
    <div class="alert alert-<?= $cls ?>"><?= $e($m) ?></div>
  <?php endif; endforeach; ?>

  <section class="booking-section">
    <h3><?= $isPast ? 'Vacant Rooms · Historical Date' : ($isToday ? 'Vacant Rooms Available for Check-in' : 'Vacant Rooms Available to Reserve') ?></h3>
    <div class="booking-table-wrap"><table class="booking-table">
      <thead><tr><th>Room Name</th><th>Room Capacity</th><th>Room Price</th><th>Booking Dates</th><th>Action</th></tr></thead>
      <tbody>
      <?php $vacantCount=0; foreach($rooms as $room):
        $stay=isset($room->active_stay)?$room->active_stay:null;
        $res=isset($room->reservation)?$room->reservation:null;
        $bookedForDate=isset($room->booking_for_date)?$room->booking_for_date:null;
        // Do not list a room as vacant when a check-in or reservation already exists for this date.
        $occupiedForDate = $isToday && $stay;
        if($occupiedForDate || $bookedForDate) continue;
        $vacantCount++;
      ?>
        <tr>
          <td><a href="#" class="booking-room-link" data-toggle="modal" data-target="#bookingRoomModal"
            data-name="<?= $e($room->rmTitle) ?>" data-desc="<?= $e($room->rmDesc ?? '') ?>" data-info="<?= $e($room->rmInfo ?? '') ?>"
            data-capacity="<?= $e($room->rmCapacity ?? '') ?>" data-price="<?= number_format((float)$room->rmPrice,2,'.','') ?>"
            data-image="<?= $e($room->rmImage ?? $room->rmImg ?? '') ?>" data-images="<?= $e(json_encode(array_map(function($im){ return base_url($im->rmiPath); }, $room->images ?? array()))) ?>"><?= $e($room->rmTitle) ?></a></td>
          <td><?= $e($room->rmCapacity ?? '—') ?> guests</td>
          <td>₱<?= number_format((float)$room->rmPrice,2) ?></td>
          <td><input type="text" class="form-control room-date-range" data-room-id="<?= (int)$room->rmID ?>" value="<?= $e($selectedDate) ?>" placeholder="Select date range" style="min-width:190px"></td>
          <td class="booking-action-cell">
            <?php if($isToday): ?><a class="btn btn-success btn-sm checkin-room-link" data-room-id="<?= (int)$room->rmID ?>" href="<?= site_url('pos/checkin/start/'.$room->rmID) ?>"><?= $res ? 'Check In Reservation' : 'Check In' ?></a>
            <?php elseif(!$isPast): ?><a class="btn btn-primary btn-sm reserve-room-link" data-room-id="<?= (int)$room->rmID ?>" href="<?= site_url('pos/reservations/book/'.$room->rmID.'?date='.rawurlencode($selectedDate)) ?>">Reserve</a>
            <?php else: ?>&nbsp;<?php endif; ?>
          </td>
        </tr>
      <?php endforeach; if(!$vacantCount): ?><tr><td colspan="5" class="booking-empty">No vacant rooms for this date.</td></tr><?php endif; ?>
      </tbody>
    </table></div>
  </section>

  <section class="booking-section">
    <h3>Check-ins on <?= $e(date('F j, Y',strtotime($selectedDate))) ?></h3>
    <div class="booking-table-wrap"><table class="booking-table">
      <thead><tr><th>Room Name</th><th>Room Capacity</th><th>Room Price</th><th>Check-in Date &amp; Time</th></tr></thead>
      <tbody>
      <?php if(!empty($date_stays)): foreach($date_stays as $stay): ?>
        <tr>
          <td><a href="#" class="booking-room-link" data-toggle="modal" data-target="#bookingRoomModal"
            data-name="<?= $e($stay->rmTitle ?? 'Room') ?>" data-desc="" data-info="Guest: <?= $e($stay->stayGuestName ?? '') ?>"
            data-capacity="<?= $e($stay->rmCapacity ?? '') ?>" data-price="<?= number_format((float)($stay->rmPrice ?? $stay->stayRoomPrice ?? 0),2,'.','') ?>" data-image="" data-images="<?= $e(json_encode(array_map(function($im){ return base_url($im->rmiPath); }, $stay->images ?? array()))) ?>"><?= $e($stay->rmTitle ?? 'Room') ?></a><br><span class="booking-status booking-status-booked">Booked</span></td>
          <td><?= $e($stay->rmCapacity ?? '—') ?> guests</td>
          <td>₱<?= number_format((float)($stay->stayRoomPrice ?? $stay->rmPrice ?? 0),2) ?></td>
          <td><?= $e(date('M j, Y · g:i A',strtotime($stay->stayTimeIn))) ?></td>
        </tr>
      <?php endforeach; endif; ?>
      <?php
        $checkedReservationIDs = array();
        if (!empty($date_stays)) foreach ($date_stays as $checkedStay) if (!empty($checkedStay->stayReservationID)) $checkedReservationIDs[] = (int)$checkedStay->stayReservationID;
        $reservationRows = 0;
        if (!empty($date_reservations)) foreach ($date_reservations as $reservedRoom):
          if (in_array((int)$reservedRoom->resID, $checkedReservationIDs, true)) continue;
          $reservationRows++;
      ?>
        <tr class="booking-reserved-row">
          <td><a href="#" class="booking-room-link" data-toggle="modal" data-target="#bookingRoomModal"
            data-name="<?= $e($reservedRoom->rmTitle ?? 'Room') ?>" data-desc="" data-info="Reserved for <?= $e($reservedRoom->resGuestName ?? 'Guest') ?>"
            data-capacity="<?= $e($reservedRoom->rmCapacity ?? '') ?>" data-price="<?= number_format((float)($reservedRoom->resPrice ?? $reservedRoom->rmPrice ?? 0),2,'.','') ?>" data-image="" data-images="<?= $e(json_encode(array_map(function($im){ return base_url($im->rmiPath); }, $reservedRoom->images ?? array()))) ?>"><?= $e($reservedRoom->rmTitle ?? 'Room') ?></a></td>
          <td><?= $e($reservedRoom->rmCapacity ?? '—') ?> guests</td>
          <td>₱<?= number_format((float)($reservedRoom->resPrice ?? $reservedRoom->rmPrice ?? 0),2) ?></td>
          <td><span class="booking-status">Reserved</span><?php if (!empty($reservedRoom->resGuestName)): ?><br><small><?= $e($reservedRoom->resGuestName) ?></small><?php endif; ?><?php if ($isToday): ?><br><a class="btn btn-success btn-sm" style="margin-top:6px" href="<?= site_url('pos/checkin/start/'.$reservedRoom->resRoomID) ?>">Check In Reservation</a><?php endif; ?></td>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($date_stays) && $reservationRows === 0): ?><tr><td colspan="4" class="booking-empty">No check-ins or reservations recorded for this date.</td></tr><?php endif; ?>
      </tbody>
    </table></div>
  </section>
</div>

<div class="modal fade" id="bookingRoomModal" tabindex="-1" role="dialog" aria-labelledby="bookingRoomModalTitle">
 <div class="modal-dialog modal-md" role="document"><div class="modal-content">
  <div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title" id="bookingRoomModalTitle">Room Information</h4></div>
  <div class="modal-body"><div id="booking-room-gallery"></div><img id="booking-room-image" class="booking-room-photo" alt="Room photo" style="display:none"><div id="booking-room-no-image" class="booking-room-photo text-center" style="padding:45px 10px;color:#888">No room image uploaded</div><h3 id="booking-room-name"></h3><p id="booking-room-desc"></p><p><strong>Capacity:</strong> <span id="booking-room-capacity"></span></p><p><strong>Price:</strong> ₱<span id="booking-room-price"></span></p><p><strong>Information:</strong><br><span id="booking-room-info"></span></p></div>
  <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
 </div></div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
(function(){
 var form=document.getElementById('booking-date-form'), picker=document.getElementById('booking-date');
 function move(days){if(!picker.value)return;var p=picker.value.split('-').map(Number),d=new Date(p[0],p[1]-1,p[2],12);d.setDate(d.getDate()+days);picker.value=[d.getFullYear(),String(d.getMonth()+1).padStart(2,'0'),String(d.getDate()).padStart(2,'0')].join('-');form.submit();}
 document.getElementById('booking-prev').addEventListener('click',function(){move(-1)});document.getElementById('booking-next').addEventListener('click',function(){move(1)});picker.addEventListener('change',function(){if(picker.value)form.submit()});
 document.querySelectorAll('.booking-room-link').forEach(function(link){link.addEventListener('click',function(){var d=this.dataset;document.getElementById('booking-room-name').textContent=d.name||'Room';document.getElementById('booking-room-desc').textContent=d.desc||'';document.getElementById('booking-room-info').textContent=d.info||'No additional information';document.getElementById('booking-room-capacity').textContent=d.capacity||'Not specified';document.getElementById('booking-room-price').textContent=d.price||'0.00';var img=document.getElementById('booking-room-image'),empty=document.getElementById('booking-room-no-image'),gallery=document.getElementById('booking-room-gallery'),images=[];gallery.innerHTML='';try{images=JSON.parse(d.images||'[]')}catch(e){}if(!images.length&&d.image)images=[d.image];if(images.length){img.src=images[0];img.style.display='block';empty.style.display='none';if(images.length>1){images.forEach(function(src,index){var thumb=document.createElement('img');thumb.src=src;thumb.alt='Room image '+(index+1);thumb.style.cssText='width:64px;height:48px;object-fit:cover;margin:8px 6px 0 0;border-radius:4px;cursor:pointer;border:1px solid #ddd';thumb.addEventListener('click',function(){img.src=src});gallery.appendChild(thumb)})}}else{img.style.display='none';empty.style.display='block'}})});
})();
</script>

<script>
(function(){
 if(!window.flatpickr)return;
 var selectedDate=<?= json_encode($selectedDate) ?>;
 document.querySelectorAll('.room-date-range').forEach(function(input){
   var roomID=input.getAttribute('data-room-id');
   var reserveLink=document.querySelector('.reserve-room-link[data-room-id="'+roomID+'"]');
   var checkinLink=document.querySelector('.checkin-room-link[data-room-id="'+roomID+'"]');
   fetch('<?= site_url('pos/checkin/reserved_dates/') ?>'+roomID).then(function(r){return r.json();}).then(function(data){
     var reserved=(data&&data.dates)||[];
     flatpickr(input,{mode:'range',dateFormat:'Y-m-d',minDate:selectedDate,defaultDate:[selectedDate],disable:reserved,onDayCreate:function(dObj,dStr,fp,day){
       var d=day.dateObj,key=d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
       if(reserved.indexOf(key)!==-1)day.classList.add('reserved-day');
     },onChange:function(selected){
       [reserveLink,checkinLink].filter(Boolean).forEach(function(link){
         if(selected.length===1){link.href=link.href.split('?')[0]+'?date='+encodeURIComponent(fmt(selected[0]));}
         else if(selected.length===2){var from=fmt(selected[0]),to=fmt(selected[1]);link.href=link.href.split('?')[0]+'?date='+encodeURIComponent(from)+'&range='+encodeURIComponent(from+' to '+to);}
       });
     }});
   }).catch(function(){});
 });
 function fmt(d){return d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');}
 document.querySelectorAll('.reserve-room-link,.checkin-room-link').forEach(function(link){
   link.addEventListener('click',function(e){
     var input=document.querySelector('.room-date-range[data-room-id="'+link.getAttribute('data-room-id')+'"]');
     if(input&&input.value){var parts=input.value.split(' to '),from=parts[0],to=parts[1]||from;link.href=link.href.split('?')[0]+'?date='+encodeURIComponent(from)+'&range='+encodeURIComponent(from+(to===from?'':' to '+to));}
   });
 });
})();
</script>
