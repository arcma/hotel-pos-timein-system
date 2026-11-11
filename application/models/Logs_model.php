<?php

class Logs_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_logs';
        $this->tablePrefix = 'log';
    }

    public function getLog($from, $to)
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'DateCreated >= "'.$from.'"');
        $this->db->where($this->tablePrefix.'DateCreated <= "'.$to.'"');
        $query = $this->db->get();
        return $query->row();
    }
	
	public function countAll()
    {
        return $this->db->count_all($this->tableName);
    }
	
	public function getAll()
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $query = $this->db->get();

        return $query->result();
    }

	public function add_log($subject, $content){

		$data = array(
            $this->tablePrefix.'Subject' => $subject,
            $this->tablePrefix.'Content' => $content,
            $this->tablePrefix.'User' => $this->session->Userdata('login')->urID,
            $this->tablePrefix.'DateCreated' => date('Y-m-d H:i:s'),
        );
        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
	}
	
	
}