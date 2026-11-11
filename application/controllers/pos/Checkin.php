<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Checkin extends MY_Controller {
 public function __construct(){parent::__construct();$this->load->model('Rooms_model');$this->load->model('Room_stays_model');$this->load->model('Room_image_model');date_default_timezone_set('Asia/Manila');}
 public function index(){
  $date=$this->input->get('date',true);$dt=$date?DateTime::createFromFormat('!Y-m-d',$date):false;if(!$dt||$dt->format('Y-m-d')!==$date)$date=date('Y-m-d');
  $data['rooms']=$this->Room_stays_model->getRoomsForCheckin($date);foreach($data['rooms'] as $room)$room->images=$this->Room_image_model->getByRoom($room->rmID,false);
  $data['selected_date']=$date;$data['title']='booking';$data['today']=date('Y-m-d');
  $data['date_stays']=$this->Room_stays_model->getStaysCheckedInOn($date);
  $shownRoomIDs=array();
  foreach($data['date_stays'] as $stay){$stay->images=$this->Room_image_model->getByRoom($stay->stayRoomID,false);$shownRoomIDs[]=(int)$stay->stayRoomID;}
  // Include check-ins saved in the unified tbl_checkin table (types 1 and 3).
  $unifiedCheckins=$this->db->select('c.ckID AS stayID,c.ckRoomID AS stayRoomID,c.ckDateCreated AS stayTimeIn,c.ckGuestName AS stayGuestName,c.ckPrice AS stayRoomPrice,r.rmTitle,r.rmCapacity,r.rmPrice')
      ->from('tbl_checkin c')->join('tbl_rooms r','r.rmID=c.ckRoomID','left')->where('c.ckDate <=',$date)->where("DATE_ADD(c.ckDate, INTERVAL IFNULL(NULLIF(c.ckDays,0),1) DAY) > ".$this->db->escape($date),null,false)->where_in('c.ckCheckInType',array(1,3))->order_by('c.ckID','DESC')->get()->result();
  foreach($unifiedCheckins as $unifiedStay){
      if(in_array((int)$unifiedStay->stayRoomID,$shownRoomIDs,true)) continue;
      $unifiedStay->images=$this->Room_image_model->getByRoom($unifiedStay->stayRoomID,false);
      $data['date_stays'][]=$unifiedStay;$shownRoomIDs[]=(int)$unifiedStay->stayRoomID;
  }
  $data['date_reservations']=$this->db->select('c.ckID AS resID,c.ckRoomID AS resRoomID,c.ckDate AS resDate,c.ckGuestName AS resGuestName,c.ckOtherCharges AS resOtherCharges,c.ckRoomDiscountID AS resRoomDiscountID,c.ckPrice AS resPrice,c.ckDiscountAmount AS resDiscountAmount,c.ckPaymentType AS resPaymentType,r.rmTitle,r.rmCapacity,r.rmPrice')->from('tbl_checkin c')->join('tbl_rooms r','r.rmID=c.ckRoomID','left')->where('c.ckDate <=',$date)->where("DATE_ADD(c.ckDate, INTERVAL IFNULL(NULLIF(c.ckDays,0),1) DAY) > ".$this->db->escape($date),null,false)->where('c.ckCheckInType',2)->order_by('c.ckID','DESC')->get()->result();foreach($data['date_reservations'] as $reservedRoom)$reservedRoom->images=$this->Room_image_model->getByRoom($reservedRoom->resRoomID,false);
  $this->load->view('templates/top',$data);$this->load->view('pos/checkin/list',$data);$this->load->view('templates/bottom');
 }
 public function start($roomID){
  $room=$this->Rooms_model->get((int)$roomID);if(!$room)show_404();$date=$this->input->get('date',true) ?: date('Y-m-d');$reservation=$this->db->where('ckRoomID',(int)$roomID)->where('ckDate',$date)->where('ckCheckInType',2)->get('tbl_checkin')->row();$stay=$this->Room_stays_model->getActiveByRoom($roomID);
  $data=array('room'=>$room,'reservation'=>$reservation,'stay'=>$stay,'date'=>$this->input->get('date',true) ?: $date,'reserved_dates'=>$this->reservedDateArray($roomID),'discounts'=>$this->db->where('disrStatus',1)->order_by('disrName')->get('tbl_room_discounts')->result(),'title'=>'booking','form_mode'=>'checkin','read_only'=>false,'form_values'=>$this->session->flashdata('checkin_form_values') ?: array());
  $this->load->view('templates/top',$data);$this->load->view('pos/checkin/booking_form',$data);$this->load->view('templates/bottom');
 }
 public function do_start($roomID){
  if($this->input->method(true)!=='POST')show_error('Invalid request method',405);
  $room=$this->Rooms_model->get((int)$roomID);if(!$room)show_404();
  $range=trim((string)$this->input->post('booking_range',true));
  $parts=preg_split('/\s+to\s+/i',$range);
  $date=$parts[0]??(string)$this->input->post('booking_date',true);
  $checkout=$parts[1]??'';
  $a=DateTime::createFromFormat('!Y-m-d',$date);
  $b=$checkout!==''?DateTime::createFromFormat('!Y-m-d',$checkout):false;
  $days=($a&&$b&&$b>=$a)?((int)$a->diff($b)->days+1):1;
  if(!$a||$date<date('Y-m-d')||($checkout!==''&&(!$b||$b<$a))){$this->session->set_flashdata('error','Select a valid booking date range.');redirect('pos/checkin?date='.rawurlencode(date('Y-m-d')));return;}
  $guest=trim((string)$this->input->post('guest_name',true));
  $email=trim((string)$this->input->post('guest_email',true));
  $formValues=array('booking_range'=>$range,'booking_date'=>$date,'guest_name'=>$guest,'guest_email'=>$email,'guest_phone'=>trim((string)$this->input->post('guest_phone',true)),'message'=>trim((string)$this->input->post('message',true)),'room_discount_id'=>(string)$this->input->post('room_discount_id',true),'fix_discount'=>(string)$this->input->post('fix_discount',true),'other_charges'=>(string)$this->input->post('other_charges',true),'payment'=>(string)$this->input->post('payment',true),'payment_type'=>(string)$this->input->post('payment_type',true));
  if($email!=='' && !filter_var($email,FILTER_VALIDATE_EMAIL)){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Please enter a valid email address (for example, name@example.com).');redirect('pos/checkin/start/'.$roomID.'?date='.rawurlencode($date).'&range='.rawurlencode($range));return;}
  if($guest===''){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Guest name is required.');redirect('pos/checkin/start/'.$roomID.'?date='.rawurlencode($date).'&range='.rawurlencode($range));return;}
  $price=(float)$room->rmPrice*$days;
  $other=max(0,(float)$this->input->post('other_charges'));
  $fixDiscount=max(0,(float)$this->input->post('fix_discount'));
  $payment=max(0,(float)$this->input->post('payment'));
  $paymentType=$this->input->post('payment_type',true)==='card'?'card':'cash';
  $discountID=(int)$this->input->post('room_discount_id');$discountAmount=0;$savedDiscount=null;
  if($discountID){
   $d=$this->db->where('disrID',$discountID)->where('disrStatus',1)->get('tbl_room_discounts')->row();
   if(!$d){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Invalid room discount.');redirect('pos/checkin/start/'.$roomID.'?date='.rawurlencode($date).'&range='.rawurlencode($range));return;}
   $discountAmount=round($price*(float)$d->disrPercent/100,2);$savedDiscount=(int)$d->disrID;
  }
  $fixDiscount=min($fixDiscount,max(0,$price-$discountAmount));
  $total=max(0,round($price-$discountAmount-$fixDiscount+$other,2));
  if($payment<$total){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Payment must cover the amount due (₱'.number_format($total,2).').');redirect('pos/checkin/start/'.$roomID.'?date='.rawurlencode($date).'&range='.rawurlencode($range));return;}
  $now=date('Y-m-d H:i:s');
  // If this room already has a reservation for today, convert that record into a check-in.
  $reservation=$this->db->where('ckRoomID',(int)$roomID)->where('ckDate',$date)->where('ckCheckInType',2)->get('tbl_checkin')->row();
  $record=array(
   'ckRoomID'=>(int)$roomID,'ckDate'=>$date,'ckDays'=>$days,'ckDateCreated'=>$now,'ckPaymentStatus'=>null,
   'ckGuestName'=>$guest,'ckGuestEmail'=>$email,'ckGuestPhone'=>$formValues['guest_phone'],'ckMessage'=>$formValues['message'],'ckOtherCharges'=>$other,'ckFixDiscount'=>$fixDiscount,
   'ckRoomDiscountID'=>$savedDiscount,'ckPrice'=>$total,'ckDiscountAmount'=>round($discountAmount+$fixDiscount,2),
   'ckCheckInType'=>1,'ckReservationDate'=>($reservation && !empty($reservation->ckReservationDate) ? $reservation->ckReservationDate : null),'ckPaymentType'=>$paymentType
  );
  if($reservation){$this->db->where('ckID',(int)$reservation->ckID)->update('tbl_checkin',$record);$ok=$this->db->affected_rows()>=0;}
  else{$this->db->insert('tbl_checkin',$record);$ok=$this->db->affected_rows()>0;}
  if(!$ok){$this->session->set_flashdata('error','Unable to save check-in.');redirect('pos/checkin?date='.rawurlencode($date));return;}
  $this->session->set_flashdata('success',$room->rmTitle.' has been checked in.');
  redirect('pos/checkin?date='.rawurlencode($date));
 }

 private function reservedDateArray($roomID){
  $dates=array();
  $rows=$this->db->select('ckDate,ckDays')->from('tbl_checkin')->where('ckRoomID',(int)$roomID)->where_in('ckCheckInType',array(1,2))->get()->result();
  foreach($rows as $r){$start=strtotime($r->ckDate);$days=max(1,(int)($r->ckDays??1));for($i=0;$i<$days;$i++)$dates[]=date('Y-m-d',strtotime('+'.$i.' day',$start));}
  if($this->db->table_exists('tbl_website_bookings')){$web=$this->db->select('bookingCheckIn,bookingCheckOut')->from('tbl_website_bookings')->where('bookingRoomID',(int)$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))->get()->result();foreach($web as $w){$d=strtotime($w->bookingCheckIn);$end=strtotime($w->bookingCheckOut);while($d<$end){$dates[]=date('Y-m-d',$d);$d=strtotime('+1 day',$d);}}}
  return array_values(array_unique($dates));
 }
 public function reserved_dates($roomID=0){
  $roomID=(int)$roomID; if(!$roomID) return $this->output->set_content_type('application/json')->set_output(json_encode(array('dates'=>array())));
  $dates=array();
  if($this->db->table_exists('tbl_website_bookings')){
   $web=$this->db->select('bookingCheckIn,bookingCheckOut')->from('tbl_website_bookings')->where('bookingRoomID',$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))->get()->result();
   foreach($web as $w){$d=strtotime($w->bookingCheckIn);$end=strtotime($w->bookingCheckOut);while($d<$end){$dates[]=date('Y-m-d',$d);$d=strtotime('+1 day',$d);}}
  }
  if($this->db->table_exists('tbl_checkin')){
   $rows=$this->db->select('ckDate,ckDays')->from('tbl_checkin')->where('ckRoomID',$roomID)->where_in('ckCheckInType',array(1,2))->get()->result();
   foreach($rows as $r){$start=strtotime($r->ckDate);$days=max(1,(int)($r->ckDays??1));for($i=0;$i<$days;$i++)$dates[]=date('Y-m-d',strtotime('+'.$i.' day',$start));}
  }
  return $this->output->set_content_type('application/json')->set_output(json_encode(array('dates'=>array_values(array_unique($dates)))));
 }
 public function do_book($roomID){
  if($this->input->method(true)!=='POST')show_error('Invalid request method',405);
  $room=$this->Rooms_model->get((int)$roomID);if(!$room)show_404();
  $range=trim((string)$this->input->post('booking_range',true));
  $parts=preg_split('/\s+to\s+/i',$range);$date=$parts[0]??'';$checkout=$parts[1]??'';
  $a=DateTime::createFromFormat('!Y-m-d',$date);$b=DateTime::createFromFormat('!Y-m-d',$checkout);
  if(!$a||!$b||$a->format('Y-m-d')!==$date||$b->format('Y-m-d')!==$checkout||$date<date('Y-m-d')||$checkout<=$date){$this->session->set_flashdata('error','Select a valid check-in and check-out date range.');redirect('pos/reservations/book/'.$roomID);return;}
  $days=(int)$a->diff($b)->days;
  $overlap=$this->db->where('ckRoomID',(int)$roomID)->where_in('ckCheckInType',array(1,2))->where('ckDate <',$checkout)->where("DATE_ADD(ckDate, INTERVAL IFNULL(NULLIF(ckDays,0),1) DAY) > '".$this->db->escape_str($date)."'",null,false)->count_all_results('tbl_checkin');
  $webOverlap=0;if($this->db->table_exists('tbl_website_bookings')){$webOverlap=$this->db->where('bookingRoomID',(int)$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))->where('bookingCheckIn <',$checkout)->where('bookingCheckOut >',$date)->count_all_results('tbl_website_bookings');}
  if($overlap||$webOverlap){$this->session->set_flashdata('error','The room is already reserved for one or more selected dates.');redirect('pos/reservations/book/'.$roomID.'?date='.rawurlencode($date));return;}
  $guest=trim((string)$this->input->post('guest_name',true));$email=trim((string)$this->input->post('guest_email',true));$phone=trim((string)$this->input->post('guest_phone',true));$message=trim((string)$this->input->post('message',true));
  if($guest===''){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Guest name is required.');redirect('pos/reservations/book/'.$roomID.'?date='.rawurlencode($date));return;}
  if($email!==''&&!filter_var($email,FILTER_VALIDATE_EMAIL)){$this->session->set_flashdata('error','Please enter a valid guest email.');redirect('pos/reservations/book/'.$roomID.'?date='.rawurlencode($date));return;}
  $price=(float)$room->rmPrice*$days;$other=max(0,(float)$this->input->post('other_charges'));$fixDiscount=max(0,(float)$this->input->post('fix_discount'));
  $discountID=(int)$this->input->post('room_discount_id');$discountAmount=0;$savedDiscount=null;
  if($discountID){$d=$this->db->where('disrID',$discountID)->where('disrStatus',1)->get('tbl_room_discounts')->row();if(!$d){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Invalid room discount.');redirect('pos/reservations/book/'.$roomID.'?date='.rawurlencode($date));return;}$discountAmount=round($price*(float)$d->disrPercent/100,2);$savedDiscount=(int)$d->disrID;}
  $fixDiscount=min($fixDiscount,max(0,$price-$discountAmount));$total=max(0,round($price-$discountAmount-$fixDiscount+$other,2));
  $payment=max(0,(float)$this->input->post('payment'));$paymentType=$this->input->post('payment_type',true)==='card'?'card':'cash';
  if($payment<$total){$this->session->set_flashdata('checkin_form_values',$formValues);$this->session->set_flashdata('error','Payment must cover the amount due (₱'.number_format($total,2).').');redirect('pos/reservations/book/'.$roomID.'?date='.rawurlencode($date));return;}
  $now=date('Y-m-d H:i:s');
  $this->db->insert('tbl_checkin',array('ckRoomID'=>(int)$roomID,'ckDate'=>$date,'ckDays'=>$days,'ckDateCreated'=>$now,'ckPaymentStatus'=>null,'ckGuestName'=>$guest,'ckGuestEmail'=>$email,'ckGuestPhone'=>$phone,'ckMessage'=>$message,'ckOtherCharges'=>$other,'ckFixDiscount'=>$fixDiscount,'ckRoomDiscountID'=>$savedDiscount,'ckPrice'=>$total,'ckDiscountAmount'=>round($discountAmount+$fixDiscount,2),'ckCheckInType'=>2,'ckReservationDate'=>$now,'ckPaymentType'=>$paymentType));
  if($this->db->affected_rows()<=0){$this->session->set_flashdata('error','Unable to save reservation.');redirect('pos/checkin?date='.rawurlencode($date));return;}
  $this->session->set_flashdata('success',$room->rmTitle.' has been reserved for '.$days.' day(s).');redirect('pos/checkin?date='.rawurlencode($date));
 }
 public function orders($stayID){$stay=$this->Room_stays_model->getStay($stayID);if(!$stay)show_404();$data['stay']=$stay;$data['room']=$this->Rooms_model->get($stay->stayRoomID);$data['orders']=$this->Room_stays_model->getOrders($stayID);$data['products']=$this->db->where('prodStatus',1)->order_by('prodCategory')->order_by('prodName')->get('tbl_products')->result();$data['food_total']=0;foreach($data['orders'] as $o)$data['food_total']+=(float)$o->rsoLineTotal;$data['title']='checkin';$this->load->view('templates/top',$data);$this->load->view('pos/checkin/orders',$data);$this->load->view('templates/bottom');}
 public function add_order($stayID){$user=$this->session->userdata('login');$result=$this->Room_stays_model->addOrder($stayID,$this->input->post('product'),$this->input->post('qty'),$user->urID??0);$this->session->set_flashdata($result[0]?'success':'error',$result[0]?'Item added to room.':$result[1]);redirect('pos/checkin/orders/'.$stayID);}
 public function checkout($stayID){$stay=$this->Room_stays_model->getStay($stayID);if(!$stay)show_404();$room=$this->Rooms_model->get($stay->stayRoomID);$orders=$this->Room_stays_model->getOrders($stayID);$food=0;foreach($orders as $o)$food+=(float)$o->rsoLineTotal;$roomDue=max(0,(float)$stay->stayRoomPrice-(float)$stay->stayRoomDiscount+(float)$stay->stayOtherCharges);$roomPaid=max(0,(float)$stay->stayRoomPayment-(float)$stay->stayRoomChange);$balance=max(0,round($roomDue+$food-$roomPaid,2));$data=compact('stay','room','orders','food','roomDue','roomPaid','balance');$data['title']='checkin';$this->load->view('templates/top',$data);$this->load->view('pos/checkin/checkout',$data);$this->load->view('templates/bottom');}
 public function do_checkout($stayID){$user=$this->session->userdata('login');$result=$this->Room_stays_model->checkout($stayID,max(0,(float)$this->input->post('payment')),$user->urID??0);$this->session->set_flashdata($result[0]?'success':'error',$result[0]?'Guest checked out successfully.':$result[1]);redirect($result[0]?'pos/checkin':'pos/checkin/checkout/'.$stayID);}
}
