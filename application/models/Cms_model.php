<?php

class Cms_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_cms';
        $this->tablePrefix = 'cms';
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
		$this->db->order_by($this->tablePrefix . 'Status','desc');
        $query = $this->db->get();
        return $query->result();
    }
	

	
	public function do_edit($postData, $id){
		$data = array(
            $this->tablePrefix.'Name' => $postData['username'],
            $this->tablePrefix.'RoleID' => $postData['role'],
			$this->tablePrefix.'Date_modified' => date('Y-m-d H:i:s'),
        );
		if($postData['role'] != ""){
			$data[$this->tablePrefix.'Password'] = md5($postData['password']);
		}
        $this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
		return true;
	}
	
	public function do_delete($id)
    {
        $this->db->delete($this->tableName, array($this->tablePrefix.'ID' => $id));
    }
	
	public function do_deleteByTable($tableID)
    {
        $this->db->delete($this->tableName, array($this->tablePrefix.'Table' => $tableID));
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
	
	public function update($id, $data)
    {
        $this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
    }
	
	public function update_byTable($table_id, $data){
		
		$this->db->update($this->tableName, $data, array($this->tablePrefix.'Table' => $table_id));
	}
	
	public function getUnserved($table_id){
        $this->db->select();
        $this->db->from($this->tableName);
		$this->db->where($this->tablePrefix.'Table', $table_id);
		$this->db->where($this->tablePrefix.'Status', 0);
        $query = $this->db->get();
        return $query->result();
	}
	
	public function getProductKitchen()
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where_in($this->tablePrefix.'Status', array(1,2,3));
		$this->db->join('tbl_products', $this->tableName.'.'.$this->tablePrefix.'Product = tbl_products.prodID','left');
		$this->db->join('tbl_tables', $this->tableName.'.'.$this->tablePrefix.'Table = tbl_tables.tbID','left');
		$this->db->join('tbl_users', $this->tableName.'.'.$this->tablePrefix.'Waiter = tbl_users.urID','left');
		// $this->db->order_by($this->tablePrefix . 'Status','desc');
		$this->db->order_by($this->tablePrefix . 'TimeOrder','asc');
        $query = $this->db->get();
		
		// echo $this->db->last_query();

        return $query->result();
    }
	
	public function getTransactionTableStatus()
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where_in($this->tablePrefix.'Status', array(1,2,3,4));
		$this->db->join('tbl_products', $this->tableName.'.'.$this->tablePrefix.'Product = tbl_products.prodID','left');
		$this->db->join('tbl_tables', $this->tableName.'.'.$this->tablePrefix.'Table = tbl_tables.tbID','left');
		// $this->db->order_by($this->tablePrefix . 'Status','desc');
        $query = $this->db->get();

		$cartStatus = "0";
		foreach($query->result() as $item){
			$cartStatus .=$item->ctStatus;
			
		}
		
		return $cartStatus;
    }
	
	public function getCartByTableKitchen($tableID)
    {
        $this->db->select();
        $this->db->from($this->tableName);
		$this->db->join('tbl_products', $this->tableName.'.'.$this->tablePrefix.'Product = tbl_products.prodID','left');
		$this->db->join('tbl_discounts', $this->tableName.'.'.$this->tablePrefix.'Discount = tbl_discounts.disID','left');
		$this->db->join('tbl_tables', $this->tableName.'.'.$this->tablePrefix.'Table = tbl_tables.tbID','left');
		//$this->db->join('tbl_users', $this->tableName.'.'.$this->tablePrefix.'Waiter = tbl_users.urID','left');
		//$this->db->where('prodPrint', '1');
		$this->db->where($this->tablePrefix ."Status !=", '0');
		$this->db->where($this->tablePrefix ."Status !=", '3');
		$this->db->where($this->tablePrefix ."Status !=", '4');
        $this->db->where($this->tablePrefix.'Table', $tableID);
		
		// $this->db->select();
        // $this->db->from($this->tableName);
		// $this->db->join('tbl_products', $this->tableName.'.'.$this->tablePrefix.'Product = tbl_products.prodID','left');
		// $this->db->join('tbl_discounts', $this->tableName.'.'.$this->tablePrefix.'Discount = tbl_discounts.disID','left');
		// $this->db->join('tbl_tables', $this->tableName.'.'.$this->tablePrefix.'Table = tbl_tables.tbID','left');
		// $this->db->join('tbl_users', $this->tableName.'.'.$this->tablePrefix.'Waiter = tbl_users.urID','left');
        // $this->db->where($this->tablePrefix.'Table', $tableID);


        $query = $this->db->get();
		
		// echo "<pre>";
		// print_r($query->result());
		// echo "</pre>";
		
        return $query->result();
    }

    
}