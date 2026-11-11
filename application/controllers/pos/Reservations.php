<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Reservations extends MY_Controller {
    public function __construct(){ parent::__construct(); $this->load->model('Rooms_model'); $this->load->model('Room_stays_model'); date_default_timezone_set('Asia/Manila'); }
    private function selectedDate(){ $d=$this->input->get('date',true); $dt=$d?DateTime::createFromFormat('!Y-m-d',$d):false; return ($dt && $dt->format('Y-m-d')===$d)?$d:date('Y-m-d'); }
    public function index(){ redirect('pos/checkin?date='.rawurlencode($this->selectedDate())); }
    public function book($roomID){ $this->bookingForm((int)$roomID,false); }
    public function booked($roomID){ $this->bookingForm((int)$roomID,true); }
    private function bookingForm($roomID,$forceReadOnly){
        $room=$this->Rooms_model->get($roomID); if(!$room) show_404();
        $date=$this->selectedDate();
        $reservation=$this->db->where('ckRoomID',$roomID)->where('ckDate',$date)->where('ckCheckInType',2)->get('tbl_checkin')->row();
        $data=array('room'=>$room,'reservation'=>$reservation,'date'=>$date,'reserved_dates'=>$this->reservedDates($roomID),'read_only'=>$forceReadOnly || $reservation || $date<date('Y-m-d'),'discounts'=>$this->db->where('disrStatus',1)->order_by('disrName')->get('tbl_room_discounts')->result(),'title'=>'booking','form_mode'=>'reservation','stay'=>null);
        $this->load->view('templates/top',$data); $this->load->view('pos/checkin/booking_form',$data); $this->load->view('templates/bottom');
    }
    private function reservedDates($roomID){
        $dates=array();
        $rows=$this->db->select('ckDate,ckDays')->from('tbl_checkin')->where('ckRoomID',(int)$roomID)->where_in('ckCheckInType',array(1,2))->get()->result();
        foreach($rows as $r){$start=strtotime($r->ckDate);$days=max(1,(int)($r->ckDays??1));for($i=0;$i<$days;$i++)$dates[]=date('Y-m-d',strtotime('+'.$i.' day',$start));}
        if($this->db->table_exists('tbl_website_bookings')){$web=$this->db->select('bookingCheckIn,bookingCheckOut')->from('tbl_website_bookings')->where('bookingRoomID',(int)$roomID)->where_not_in('bookingStatus',array('cancelled','rejected'))->get()->result();foreach($web as $w){$d=strtotime($w->bookingCheckIn);$end=strtotime($w->bookingCheckOut);while($d<$end){$dates[]=date('Y-m-d',$d);$d=strtotime('+1 day',$d);}}}
        return array_values(array_unique($dates));
    }
    public function do_book($roomID){
        // Backward compatibility for old form actions. New reservation forms post to Checkin::do_book.
        if($this->input->method(true)!=='POST'){redirect('pos/reservations/book/'.(int)$roomID);return;}
        $room=$this->Rooms_model->get((int)$roomID);
        if(!$room) show_404();
        $date=$this->input->post('booking_date',true);
        $this->session->set_flashdata('error','Please submit the reservation using the current booking form.');
        redirect('pos/reservations/book/'.(int)$roomID.'?date='.rawurlencode($date ?: date('Y-m-d')));
    }
}
