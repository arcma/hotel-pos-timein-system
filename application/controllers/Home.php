<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends MY_Controller {
 public function __construct(){parent::__construct();$this->load->model('Rooms_model');$this->load->model('Room_image_model');$this->load->model('Cms_pages_model','pages_model');$this->load->model('Settings_model');}
 public function index(){
  $data['title']='Home page';$data['cms_sections']=array();$data['nav_menu']=$this->get_nav_menu();
  if($this->db->table_exists('tbl_cms_homepage')){$rows=$this->db->get('tbl_cms_homepage')->result();foreach($rows as $row){$data['cms_sections'][$row->homeName]=array('status'=>(int)$row->homeStatus,'content'=>json_decode($row->homeContent,true)?:array());}}
  $this->load->view('website/home',$data);
 }
 public function listing(){ $this->rooms(); }
 public function rooms(){ $data['nav_menu']=$this->get_nav_menu(); $data['title']='Our Rooms';$data['rooms']=$this->Rooms_model->getActive();foreach($data['rooms'] as $room)$room->images=$this->Room_image_model->getByRoom($room->rmID,false);$this->load->view('website/rooms',$data); }
 public function room($id=0){$data['nav_menu']=$this->get_nav_menu();$room=$this->Rooms_model->get((int)$id);if(!$room || (int)$room->rmStatus!==1)show_404();$data['title']=$room->rmTitle;$data['room']=$room;$data['images']=$this->Room_image_model->getByRoom($id,false);$this->load->view('website/room',$data);}
 public function page($slug=''){ $data['nav_menu']=$this->get_nav_menu();$page=$this->pages_model->get_live_by_slug($slug);if(!$page)show_404();$data['page']=$page;$data['title']=$page->pageTitle;$this->load->view('website/page',$data); }
 public function booknow($roomID=0){
  $data['nav_menu']=$this->get_nav_menu();
  $data['title']='Book Now';
  $data['selected_room']=$roomID?(int)$roomID:0;
  $data['rooms']=$this->Rooms_model->getActive();
  $data['form_values']=$this->session->flashdata('website_booking_values') ?: array();
  $data['message']=$this->session->flashdata('booking_message') ?: '';
  $this->load->view('website/booknow',$data);
 }
 public function thank_you(){
  $data['nav_menu']=$this->get_nav_menu();
  $data['title']='Thank You';
  $this->load->view('website/booking_thank_you',$data);
 }
 public function check_availability(){if(strtoupper($this->input->method())!=='POST')show_404();$roomID=(int)$this->input->post('roomID');$checkin=$this->input->post('checkin',true);$checkout=$this->input->post('checkout',true);$validDates=$this->valid_date_range($checkin,$checkout);if(!$roomID||!$validDates){return $this->json(array('success'=>false,'message'=>'Select a room and valid check-in/check-out dates.'),400);} $room=$this->Rooms_model->get($roomID);if(!$room||(int)$room->rmStatus!==1)return $this->json(array('success'=>false,'message'=>'Room is not available.'),404);
  if(!$this->db->table_exists('tbl_website_bookings'))return $this->json(array('success'=>false,'message'=>'Booking database migration is missing. Import database/migrations/20261103_website_pages_bookings.sql.'),500);
  $available=$this->room_is_available($roomID,$checkin,$checkout);
  return $this->json(array('success'=>true,'available'=>$available,'message'=>$available?'This room is available for your selected dates.':'This room is already reserved for some of the selected dates.','room'=>array('title'=>$room->rmTitle,'price'=>(float)$room->rmPrice)),200);
 }
 public function reserved_dates($roomID=0){
  if (!$roomID) $roomID=(int)$this->input->get('roomID');
  $roomID=(int)$roomID;
  if (!$roomID) return $this->json(array('success'=>false,'dates'=>array()),400);
  $dates=array();
  if ($this->db->table_exists('tbl_checkin')) {
   $rows=$this->db->select('ckDate,ckDays')->from('tbl_checkin')->where('ckRoomID',$roomID)->where_in('ckCheckInType',array(1,2))->get()->result();
   foreach($rows as $row){
    $start=strtotime($row->ckDate);$days=max(1,(int)($row->ckDays??1));
    for($i=0;$i<$days;$i++)$dates[]=date('Y-m-d',strtotime('+'.$i.' day',$start));
   }
  }
  // Include legacy website requests while old records still exist.
  if ($this->db->table_exists('tbl_website_bookings')) {
   $rows=$this->db->select('bookingCheckIn,bookingCheckOut')->from('tbl_website_bookings')->where('bookingRoomID',$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))->get()->result();
   foreach($rows as $row){$d=strtotime($row->bookingCheckIn);$end=strtotime($row->bookingCheckOut);while($d<$end){$dates[]=date('Y-m-d',$d);$d=strtotime('+1 day',$d);}}
  }
  return $this->json(array('success'=>true,'dates'=>array_values(array_unique($dates))));
 }
 public function submit_booking(){
  if($this->input->method(true)!=='POST')show_404();
  $roomID=(int)$this->input->post('roomID');
  $checkin=trim((string)$this->input->post('checkin',true));
  $checkout=trim((string)$this->input->post('checkout',true));
  $name=trim((string)$this->input->post('guestName',true));
  $email=trim((string)$this->input->post('guestEmail',true));
  $phone=trim((string)$this->input->post('guestPhone',true));
  $message=trim((string)$this->input->post('message',true));
  $formValues=array('roomID'=>$roomID,'checkin'=>$checkin,'checkout'=>$checkout,'guestName'=>$name,'guestEmail'=>$email,'guestPhone'=>$phone,'message'=>$message);
  $redirectUrl='booknow/'.$roomID;
  $fail=function($message) use ($formValues,$redirectUrl){$this->session->set_flashdata('website_booking_values',$formValues);$this->session->set_flashdata('booking_message',$message);redirect($redirectUrl);};
  $room=$this->Rooms_model->get($roomID);
  if(!$room || (int)$room->rmStatus!==1){$fail('Please select a valid room.');return;}
  $a=DateTime::createFromFormat('!Y-m-d',$checkin);
  $b=$checkout!==''?DateTime::createFromFormat('!Y-m-d',$checkout):false;
  if(!$a || $a->format('Y-m-d')!==$checkin || $checkin<date('Y-m-d')){$fail('Please select a valid booking date.');return;}
  // A single selected date is a one-day booking. For a range, both endpoints are included.
  if($checkout==='' || $checkout===$checkin){$days=1;}
  elseif(!$b || $b->format('Y-m-d')!==$checkout || $b<$a){$fail('Please select a valid date range.');return;}
  else{$days=(int)$a->diff($b)->days+1;}
  if($name===''){$fail('Guest name is required.');return;}
  if($email==='' || !filter_var($email,FILTER_VALIDATE_EMAIL)){$fail('Please enter a valid email address.');return;}
  if(!$this->db->table_exists('tbl_checkin')){$fail('The POS booking table (tbl_checkin) is missing. Please check the database setup.');return;}
  $exclusiveEnd=date('Y-m-d',strtotime('+'.$days.' day',strtotime($checkin)));
  // Recheck the full inclusive date range on submit to prevent overlapping bookings.
  $posOverlap=$this->db->where('ckRoomID',$roomID)->where_in('ckCheckInType',array(1,2))
   ->where('ckDate <',$exclusiveEnd)
   ->where("DATE_ADD(ckDate, INTERVAL GREATEST(IFNULL(NULLIF(ckDays,0),1),1) DAY) > ".$this->db->escape($checkin),null,false)
   ->count_all_results('tbl_checkin');
  if($posOverlap>0){$fail('This room is already booked for one or more selected dates. Please choose another date.');return;}
  // Also prevent collision with any legacy website booking requests.
  if($this->db->table_exists('tbl_website_bookings')){
   $legacyOverlap=$this->db->where('bookingRoomID',$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))
    ->where('bookingCheckIn <',$exclusiveEnd)->where('bookingCheckOut >',$checkin)->count_all_results('tbl_website_bookings');
   if($legacyOverlap>0){$fail('This room is already requested for one or more selected dates. Please choose another date.');return;}
  }
  $price=round((float)$room->rmPrice*$days,2);
  $now=date('Y-m-d H:i:s');
  $this->db->insert('tbl_checkin',array(
   'ckRoomID'=>$roomID,'ckDate'=>$checkin,'ckDays'=>$days,'ckDateCreated'=>$now,'ckPaymentStatus'=>null,
   'ckGuestName'=>$name,'ckGuestEmail'=>$email,'ckGuestPhone'=>$phone,'ckMessage'=>$message,
   'ckOtherCharges'=>0,'ckFixDiscount'=>0,'ckRoomDiscountID'=>null,'ckPrice'=>$price,'ckDiscountAmount'=>0,
   'ckCheckInType'=>2,'ckReservationDate'=>$now,'ckPaymentType'=>null
  ));
  if($this->db->affected_rows()<=0){$fail('Unable to save your booking. Please try again.');return;}
  redirect('booknow/thank-you');
 }
 private function room_is_available($roomID,$checkin,$checkout){
  $webOverlap=$this->db->where('bookingRoomID',(int)$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))->where('bookingCheckIn <',$checkout)->where('bookingCheckOut >',$checkin)->count_all_results('tbl_website_bookings');
  if($webOverlap>0)return false;
  // Also respect room reservations/check-ins already entered by the POS.
  if($this->db->table_exists('tbl_checkin')){
   $posOverlap=$this->db->where('ckRoomID',(int)$roomID)->where('ckDate >=',$checkin)->where('ckDate <',$checkout)->where_in('ckCheckInType',array(1,2))->count_all_results('tbl_checkin');
   if($posOverlap>0)return false;
  }
  return true;
 }
 private function valid_date_range($in,$out){$a=DateTime::createFromFormat('Y-m-d',$in);$b=DateTime::createFromFormat('Y-m-d',$out);return $a&&$b&&$a->format('Y-m-d')===$in&&$b->format('Y-m-d')===$out&&$in>=date('Y-m-d')&&$out>$in;}
 private function json($payload,$status=200){return $this->output->set_status_header($status)->set_content_type('application/json','utf-8')->set_output(json_encode($payload));}
 private function get_nav_menu(){ $nav=json_decode((string)$this->Settings_model->get_setting('navMenu'),true); return is_array($nav)?$nav:array(); }
}
