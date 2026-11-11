<?php

class Tables_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_tables';
        $this->tablePrefix = 'tb';
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
	
	public function getAll()
    {
        $this->db->select();
        $this->db->from($this->tableName);
        // $this->db->where($this->tablePrefix.'Status', '1');
		$this->db->order_by($this->tablePrefix . 'Status','desc');
		$this->db->order_by($this->tablePrefix . 'Category','asc');
		$this->db->order_by($this->tablePrefix . 'SortOrder','asc');
        $query = $this->db->get();
        return $query->result();
    }
	
	public function getByName($name)
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'Username', $name);
        $this->db->where($this->tablePrefix.'Status', '1');
        $query = $this->db->get();
        return $query->row();
    }
	
	public function do_add($postData){
        $category = (int)($postData['category'] ?? 1);
        // Hotel tables are automatically created and linked when a room is created.
        if (!in_array($category, array(1, 2), true)) return false;
        $data = array(
            $this->tablePrefix.'Name' => trim((string)($postData['name'] ?? '')),
            $this->tablePrefix.'Category' => $category,
            $this->tablePrefix.'SortOrder' => $postData['order'] ?? 0,
            $this->tablePrefix.'Date_Created' => date('Y-m-d H:i:s'),
            $this->tablePrefix.'Date_modified' => date('Y-m-d H:i:s'),
            $this->tablePrefix.'Status' => 1,
        );
        if ($data[$this->tablePrefix.'Name'] === '') return false;
        $this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
	}

	public function do_edit($postData, $id){
        $table = $this->get($id);
        if (!$table) return false;

        // A room-linked hotel table is managed from the Rooms screen.
        $category = !empty($table->tbRoomID) ? 3 : (int)($postData['category'] ?? $table->tbCategory);
        $name = !empty($table->tbRoomID) ? $table->tbName : trim((string)($postData['name'] ?? $table->tbName));
        if (!in_array($category, array(1, 2, 3), true)) return false;
        if ($category === 3 && empty($table->tbRoomID)) {
            // Hotel tables must be created through Rooms so tbRoomID is always populated.
            return false;
        }
        $data = array(
            $this->tablePrefix.'Name' => $name,
            $this->tablePrefix.'Category' => $category,
            $this->tablePrefix.'SortOrder' => $postData['order'] ?? $table->tbSortOrder,
            $this->tablePrefix.'Date_modified' => date('Y-m-d H:i:s'),
        );
        $this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
        return $this->db->affected_rows() >= 0;
	}

	public function do_delete($id)
    {
        $this->db->delete($this->tableName, array($this->tablePrefix.'ID' => $id));
    }
	public function do_disable($id){
		$data[$this->tablePrefix.'Status'] = 0;
		$data[$this->tablePrefix.'Date_modified'] = date('Y-m-d H:i:s');
        $this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
	}
	
	public function do_enable($id){
		$data[$this->tablePrefix.'Status'] = 1;
		$data[$this->tablePrefix.'Date_modified'] = date('Y-m-d H:i:s');
        $this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
	}

    // public function getAll($withRole = false, $filter = null, $perPage = null, $page = null)
    // {
        // $this->db->select();
        // $this->db->from($this->tableName);
        // if($withRole) {
            // $this->db->join('tblroles', 'tblusers.role = tblroles.RoID');
        // }
        // if($filter) {
            // $this->db->like('username', $filter);
            // $this->db->or_like('email', $filter);
        // }
        // if($perPage && $page) {
            // $this->db->limit($perPage, $page*$perPage-$perPage);
        // }
        // $query = $this->db->get();
        // return $query->result();
    // }

    // public function getAdmins()
    // {
        // $this->db->select();
        // $this->db->from($this->tableName);
        // $this->db->where('role', 1);
        // $query = $this->db->get();
        // return $query->result();
    // }

    // public function getUserByUsername($username)
    // {
        // $query = $this->db->get_where($this->tableName, array('username' => $username));
        // if($query->num_rows()) return $query->row();
        // return false;
    // }

    

    // public function addUser($username, $password, $role = null, $email = null, $number = null, $slack = null)
    // {
        // $data = array(
            // 'username' => $username,
            // 'password' => password_hash($password . PASSWORD_SALT, PASSWORD_DEFAULT),
            // 'role' => $role,
            // 'email' => filter_var($email, FILTER_SANITIZE_EMAIL),
            // 'number' => $number,
            // 'slack' => ($slack ? 1 : 0),
            // 'created' => date('Y-m-d H:i:s'),
            // 'last_login' => null
        // );
        // $this->db->insert($this->tableName, $data);
        // return $this->db->insert_id();
    // }

    // public function update($id, $data)
    // {
        // if (isset($data['password'])) {
            // $data['password'] = password_hash($data['password'] . PASSWORD_SALT, PASSWORD_DEFAULT);
        // }
        // $this->db->update($this->tableName, $data, array('ID' => $id));
    // }

    // public function delete($id)
    // {
        // $this->db->delete($this->tableName, array('id' => $id));
    // }

}