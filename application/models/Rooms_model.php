<?php

class Rooms_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_rooms';
        $this->tablePrefix = 'rm';
    }

    public function get($id)
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'ID', $id);
        $query = $this->db->get();
        return $query->row();
    }
	
	public function countAll()
    {
        return $this->db->count_all($this->tableName);
    }
	
	public function getall()
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $query = $this->db->get();

        return $query->result();
    }

    public function getActive()
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'Status', '1');
        $query = $this->db->get();

        return $query->result();
    }
	
	public function do_add($postData){
        $now = date('Y-m-d H:i:s');
        $roomData = array(
            $this->tablePrefix.'Title' => trim((string)$postData['title']),
            $this->tablePrefix.'Desc' => $postData['desc'],
            $this->tablePrefix.'Info' => $postData['info'],
            $this->tablePrefix.'Capacity' => $postData['capacity'],
            $this->tablePrefix.'Price' => $postData['price'],
            $this->tablePrefix.'Date_Created' => $now,
            $this->tablePrefix.'Date_modified' => $now,
            $this->tablePrefix.'Status' => isset($postData['status']) ? (int)$postData['status'] : 1,
        );

        $this->db->trans_begin();
        $this->db->insert($this->tableName, $roomData);
        $roomID = (int)$this->db->insert_id();
        if ($roomID > 0) {
            $sortRow = $this->db->select_max('tbSortOrder')->get('tbl_tables')->row();
            $sortOrder = (int)($sortRow ? $sortRow->tbSortOrder : 0) + 1;
            $tableData = array(
                'tbName' => trim((string)$postData['title']),
                'tbCategory' => 3,
                'tbRoomID' => $roomID,
                'tbSortOrder' => $sortOrder,
                'tbDate_created' => $now,
                'tbDate_modified' => $now,
                'tbType' => 1,
                'tbStatus' => isset($postData['status']) ? (int)$postData['status'] : 1,
            );
            $this->db->insert('tbl_tables', $tableData);
        }
        if ($roomID < 1 || $this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return $roomID;
	}

	public function do_delete($id)
    {
        $id = (int)$id;
        if ($id < 1) {
            return false;
        }

        // Delete the automatically-created POS table linked to this room and
        // the room itself as one operation, so neither record is left orphaned.
        $this->db->trans_begin();
        $this->db->delete('tbl_tables', array('tbRoomID' => $id));
        $this->db->delete($this->tableName, array($this->tablePrefix.'ID' => $id));

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }

        $this->db->trans_commit();
        return true;
    }

	public function update($id, $data)
    {
		$this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
    }

    public function delete($id)
    {
        $this->db->delete($this->tableName, array('id' => $id));
    }

	public function do_disable($id){
        $now = date('Y-m-d H:i:s');
        $this->db->update($this->tableName, array('rmStatus' => 0, 'rmDate_modified' => $now), array('rmID' => (int)$id));
        $this->db->update('tbl_tables', array('tbStatus' => 0, 'tbDate_modified' => $now), array('tbRoomID' => (int)$id));
	}
	
	public function do_enable($id){
        $now = date('Y-m-d H:i:s');
        $this->db->update($this->tableName, array('rmStatus' => 1, 'rmDate_modified' => $now), array('rmID' => (int)$id));
        $this->db->update('tbl_tables', array('tbStatus' => 1, 'tbDate_modified' => $now), array('tbRoomID' => (int)$id));
	}

    public function do_edit($postData, $id){
        $id = (int)$id;
        $now = date('Y-m-d H:i:s');
        $data = array(
            'rmTitle' => trim((string)$postData['title']),
            'rmDesc' => $postData['desc'],
            'rmInfo' => $postData['info'],
            'rmCapacity' => $postData['capacity'],
            'rmStatus' => (int)$postData['status'],
            'rmPrice' => $postData['price'],
            'rmDate_modified' => $now,
        );

        $this->db->trans_begin();
        $this->db->update($this->tableName, $data, array('rmID' => $id));
        $this->db->update('tbl_tables', array(
            'tbName' => trim((string)$postData['title']),
            'tbCategory' => 3,
            'tbStatus' => (int)$postData['status'],
            'tbDate_modified' => $now,
        ), array('tbRoomID' => $id));
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        $this->db->trans_commit();
        return true;
    }

}