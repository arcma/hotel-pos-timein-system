<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cms_pages_model extends CI_Model {
    private $table = 'tbl_pages';
    public function all() { return $this->db->order_by('pageLastModified','DESC')->get($this->table)->result(); }
    public function get($id) { return $this->db->get_where($this->table,array('pageID'=>(int)$id))->row(); }
    public function slug_exists($slug,$exclude=0) { $this->db->where('pageSlug',$slug); if($exclude) $this->db->where('pageID !=',(int)$exclude); return $this->db->count_all_results($this->table)>0; }
    public function save($data,$id=0) {
        $now=date('Y-m-d H:i:s');
        if($id){$data['pageLastModified']=$now;return $this->db->where('pageID',(int)$id)->update($this->table,$data);}
        $data['pageDateCreated']=$now;$data['pageLastModified']=$now;return $this->db->insert($this->table,$data);
    }
    public function delete($id) { return $this->db->where('pageID',(int)$id)->delete($this->table); }
    public function get_live_by_slug($slug) { return $this->db->where('pageSlug',$slug)->where('pageStatus',1)->get($this->table)->row(); }
}
