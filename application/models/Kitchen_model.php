<?php

class Kitchen_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_kitchen';
        $this->tablePrefix = 'kit';
    }

    public function get($id)
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'ID', $id);
        $query = $this->db->get();
        return $query->row();
    }
	
	public function getByTable($id)
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'Table', $id);
        $query = $this->db->get();
        return $query->row();
    }

	public function getAll()
    {
        $this->db->select();
        $this->db->from($this->tableName);
		$this->db->order_by($this->tablePrefix . 'Status');
        $query = $this->db->get();

        return $query->result();
    }
	
	public function getKitchenActive()
    {
        $this->db->select();
        $this->db->from($this->tableName);
		$this->db->join('tbl_products', $this->tableName.'.'.$this->tablePrefix.'Product = tbl_products.prodID','left');
		$this->db->join('tbl_tables', $this->tableName.'.'.$this->tablePrefix.'Table = tbl_tables.tbID','left');
        $this->db->where($this->tablePrefix.'Status !=', '0');
        $this->db->where($this->tablePrefix.'Status !=', '4');
		// $this->db->order_by($this->tablePrefix . 'Status');
        $query = $this->db->get();

        return $query->result();
    }
	

    /** Queued items and previously printed items for the terminal history table. */
    public function getPrinterTerminalItems($limit = 200)
    {
        $this->db->select('tbl_kitchen.*, tbl_products.prodName, tbl_tables.tbName');
        $this->db->from('tbl_kitchen');
        $this->db->join('tbl_products', 'tbl_kitchen.kitProduct = tbl_products.prodID', 'left');
        $this->db->join('tbl_tables', 'tbl_kitchen.kitTable = tbl_tables.tbID', 'left');
        $this->db->group_start();
        $this->db->where('tbl_kitchen.kitPrinted', 1);
        $this->db->or_group_start();
        $this->db->where('tbl_kitchen.kitStatus', 1);
        $this->db->where('tbl_kitchen.kitPrinted', 0);
        $this->db->group_end();
        $this->db->group_end();
        $this->db->order_by('tbl_kitchen.kitTimeOrdered', 'DESC');
        $this->db->limit((int) $limit);
        return $this->db->get()->result();
    }

    /** One kitchen print item with its product and table labels. */
    public function getPrinterItem($id)
    {
        return $this->db->select('tbl_kitchen.*, tbl_products.prodName, tbl_tables.tbName')
            ->from('tbl_kitchen')
            ->join('tbl_products', 'tbl_kitchen.kitProduct = tbl_products.prodID', 'left')
            ->join('tbl_tables', 'tbl_kitchen.kitTable = tbl_tables.tbID', 'left')
            ->where('tbl_kitchen.kitID', (int) $id)
            ->limit(1)
            ->get()->row();
    }

    /** Items eligible for automatic printing. */
    public function getPendingPrinterItems()
    {
        return $this->db->select('tbl_kitchen.*, tbl_products.prodName, tbl_tables.tbName')
            ->from('tbl_kitchen')
            ->join('tbl_products', 'tbl_kitchen.kitProduct = tbl_products.prodID', 'left')
            ->join('tbl_tables', 'tbl_kitchen.kitTable = tbl_tables.tbID', 'left')
            ->where('tbl_kitchen.kitStatus', 1)
            ->where('tbl_kitchen.kitPrinted', 0)
            ->order_by('tbl_kitchen.kitTimeOrdered', 'ASC')
            ->get()->result();
    }

	public function add($data){
		$this->db->insert($this->tableName, $data);
        return $this->db->insert_id();
	}
	
	public function update($id, $data){
        $this->db->update($this->tableName, $data, array($this->tablePrefix.'Cart' => $id));
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