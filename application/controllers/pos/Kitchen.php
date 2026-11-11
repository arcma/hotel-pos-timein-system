<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchen extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Cart_model');
		$this->load->model('Kitchen_model');
    }

    public function index_old()
    {
		
		$cart = $this->Cart_model->getProductKitchen();
		$products = $this->Kitchen_model->getKitchenActive();
		
		$cartStatus = "0";
		foreach($cart as $item){
			$cartStatus .=$item->ctStatus;
		}
		// echo "<pre>";
		// print_r($products);
		// echo "</pre>";
		// return false;
		
		$tables = array();
		foreach($products as $product){
			// $table_data[$product->tbID][] = $product->tbName;
			$tables[$product->tbID][] = $product;
		}

		$data['title'] = 'kitchen';
		$data['cartStatus'] = $cartStatus;
		$data['tables'] = $tables;
        $this->load->view('templates/top', $data);
		$this->load->view('pos/kitchen/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function index()
    {
		$this->load->model('Users_model');
		$products = $this->Cart_model->getProductKitchen();

		// $cart_data = $this->Cart_model->getAll();
		$cartStatus = "0";
		foreach($products as $item){
			$cartStatus .=$item->ctStatus;
		}
		// echo "<pre>";
		// print_r($products);
		// echo "</pre>";
		// return false;

		$data['title'] = 'kitchen';
		$data['cartStatus'] = $cartStatus;
		$data['products'] = $products;
        $this->load->view('templates/top', $data);
		$this->load->view('pos/kitchen/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function update_cart_inprogress($id){
		if(isset($_POST)){
			$postData = $_POST;
			
			$this->Cart_model->update($id, array('ctStatus'=>2, 'ctTimeInprogress' =>date('Y-m-d H:i:s')));
			$this->Kitchen_model->update($id, array('kitStatus'=>2));
			
			$this->session->set_flashdata('success', 'Cart item in progress');
			redirect('pos/kitchen', 'location');
			return false;
		}
		$this->session->set_flashdata('error', 'Failed to update cart item');
		redirect('pos/kitchen', 'location');
	}
	
	public function update_cart_deliver($id){
		if(isset($_POST)){
			$postData = $_POST;
			
			$this->Cart_model->update($id, array('ctStatus'=>3, 'ctTimeServe' =>date('Y-m-d H:i:s')));
			$this->Kitchen_model->update($id, array('kitStatus'=>3));
			
			$this->session->set_flashdata('success', 'Cart item is ready to served');
			redirect('pos/kitchen', 'location');
			return false;
		}
		$this->session->set_flashdata('error', 'Failed to update cart item');
		redirect('pos/kitchen', 'location');
	}
	
	public function removekitchen($id){
		$this->Kitchen_model->update($id, array('kitStatus'=>4));
		$this->session->set_flashdata('success', 'Item was removed');
		redirect('pos/kitchen', 'location');
	}
	

}
