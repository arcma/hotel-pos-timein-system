<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Booking_discounts_model extends CI_Model {
    private $table = 'tbl_room_discounts';
    public function getAll(){return $this->db->order_by('disrStatus','DESC')->order_by('disrName','ASC')->get($this->table)->result();}
    public function get($id){return $this->db->where('disrID',(int)$id)->get($this->table)->row();}
    public function add($name,$percent){$now=date('Y-m-d H:i:s');$this->db->insert($this->table,array('disrName'=>trim($name),'disrPercent'=>(int)$percent,'disrDate_created'=>$now,'disrDate_modified'=>$now,'disrStatus'=>1));return $this->db->insert_id();}
    public function updateDiscount($id,$name,$percent){$this->db->where('disrID',(int)$id)->update($this->table,array('disrName'=>trim($name),'disrPercent'=>(int)$percent,'disrDate_modified'=>date('Y-m-d H:i:s')));return $this->db->affected_rows()>=0;}
    public function setStatus($id,$status){$this->db->where('disrID',(int)$id)->update($this->table,array('disrStatus'=>(int)$status,'disrDate_modified'=>date('Y-m-d H:i:s')));return $this->db->affected_rows()>=0;}
    public function delete($id){$this->db->where('disrID',(int)$id)->delete($this->table);return $this->db->affected_rows()>0;}
}
