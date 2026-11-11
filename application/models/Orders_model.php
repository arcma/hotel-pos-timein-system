<?php

class Orders_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'tbl_orders';
        $this->tablePrefix = 'odr';
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
        $this->db->where($this->tablePrefix.'Status', '1');
        $query = $this->db->get();

        return $query->result();
    }
	
	public function do_add($postData){
		$data = array(
            $this->tablePrefix.'Table' => $postData['table'],
            $this->tablePrefix.'Customer' => $postData['customer_name'],
            $this->tablePrefix.'Sales' => $postData['sales'],
            $this->tablePrefix.'OtherCharges' => $postData['other_charges'],
            $this->tablePrefix.'AddDiscount' => $postData['add_discount'],
            $this->tablePrefix.'Payment' => $postData['payment'],
            $this->tablePrefix.'Change' => $postData['change'],
            $this->tablePrefix.'Casher' => $postData['casher'],
            $this->tablePrefix.'Price' => $postData['price'],
            $this->tablePrefix.'Discount' => $postData['discount'],
            $this->tablePrefix.'Date_created' => $postData['payment_date'] != "" ? date('Y-m-d H:i:s', strtotime($postData['payment_date'])) : date('Y-m-d H:i:s'),
            $this->tablePrefix.'Status' => 1,
            $this->tablePrefix.'JsonCartItems' => $postData['cart_data']
        );

        $this->db->insert($this->tableName, $data);
		$insert_id = $this->db->insert_id();
		
		// add products log, for report
		$cart = json_decode($postData['cart_data']);
		$userdata = $this->session->Userdata('login');
		$waiter = $userdata->urID;
		foreach($cart as $items){
			$data2 = array(
				'prOrderID' => $insert_id,
				'prTable' => $items->ctTable,
				'prProduct' => $items->ctProduct,
				'prQty' => $items->ctQty,
				'prDiscount' => $items->ctPrice - $items->ctSales,
				'prPrice' => $items->ctPrice,
				'prSales' => $items->ctSales,
				'prWaiter' => $waiter,
				'prDateCreated' => date('Y-m-d H:i:s'),
			);
			$this->db->insert('tbl_product_reports', $data2);
		}
		
		
        return $insert_id;
	}
	
	public function getProductOrderByDateRange($from, $to)
    {
        $this->db->select();
        $this->db->from('tbl_product_reports');
		$this->db->where('prDateCreated >= "'.$from.'"');
        $this->db->where('prDateCreated <= "'.$to.'"');
		$this->db->join('tbl_tables', 'tbl_product_reports.prTable = tbl_tables.tbID','left');
		$this->db->join('tbl_products', 'tbl_product_reports.prProduct = tbl_products.prodID','left');
		$this->db->join('tbl_users', 'tbl_product_reports.prWaiter = tbl_users.urID','left');

        $query = $this->db->get();

        return $query->result();
    }
	
	public function getProductOrderByDate($date)
    {

		$from = date('Y-m-d H:i:s',strtotime($date));
		$to = date('Y-m-d H:i:s',strtotime($date . ' +1 day'));
		
        $this->db->select();
        $this->db->from('tbl_product_reports');
		$this->db->where('prDateCreated >= "'.$from.'"');
        $this->db->where('prDateCreated <= "'.$to.'"');
		$this->db->join('tbl_tables', 'tbl_product_reports.prTable = tbl_tables.tbID','left');
		$this->db->join('tbl_products', 'tbl_product_reports.prProduct = tbl_products.prodID','left');
		$this->db->join('tbl_users', 'tbl_product_reports.prWaiter = tbl_users.urID','left');

        $query = $this->db->get();

        return $query->result();
    }
	
	
	public function do_delete($id)
    {
        $this->db->delete($this->tableName, array($this->tablePrefix.'ID' => $id));
    }

	public function update($id, $data)
    {
		$this->db->update($this->tableName, $data, array($this->tablePrefix.'ID' => $id));
    }

    public function delete($id)
    {
        $this->db->delete($this->tableName, array('id' => $id));
    }
	
	public function getByDateRange($from, $to)
    {
        $this->db->select();
        $this->db->from($this->tableName);
        $this->db->where($this->tablePrefix.'Date_created >= "'.$from.'"');
        $this->db->where($this->tablePrefix.'Date_created <= "'.$to.'"');
		$this->db->join('tbl_tables', $this->tableName.'.'.$this->tablePrefix.'Table = tbl_tables.tbID','left');
		$this->db->join('tbl_users', $this->tableName.'.'.$this->tablePrefix.'Casher = tbl_users.urID','left');
		$this->db->order_by($this->tablePrefix . 'ID','asc');
        $query = $this->db->get();

        return $query->result();
    }

}