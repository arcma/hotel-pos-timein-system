<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Products_model');
		$this->load->model('Categories_model');
    }

    public function index()
    {
		$products = $this->Products_model->getall();
		$categories = $this->Categories_model->getall();

		$category_list = array();
		foreach($categories as $category){
			$category_list[$category->catID] = $category->catName;
		}
		
		$product_list = array();
		foreach($products as $product){
			$product_list[$product->prodCategory]['name'] = $category_list[$product->prodCategory];
			$product_list[$product->prodCategory]['products'][] = $product;
		}

		$data['userdata'] = $this->session->Userdata('login');
		$data['product_list'] = $product_list;

		$data['title'] = 'products';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/products/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function add(){
		
		$categories = $this->Categories_model->getall();
		$data['categories'] = $categories;
		$data['title'] = 'products';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/products/add', $data);
		$this->load->view('templates/bottom');
	}
	
	public function do_add(){
		if(isset($_POST)){
			$postData = $_POST;
			$products = $this->Products_model->do_add($postData);
			if($products){
				$this->session->set_flashdata('success', 'product '.$_POST['name'].' has been added successfully');
				redirect("pos/products");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to add product');
		redirect("pos/products");
	}
	
	public function edit($id = false){
		$categories = $this->Categories_model->getall();
		$data['categories'] = $categories;
		$data['userdata'] = $this->session->Userdata('login');
		if($id){
			$product = $this->Products_model->get($id);
			$data['product'] = $product;
			$data['title'] = 'products';
			$this->load->view('templates/top', $data);
			$this->load->view('pos/products/edit', $data);
			$this->load->view('templates/bottom');
		
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled product');
		}
	}
	
	public function do_edit($id){
		
		
		if(isset($_POST)){
			$postData = $_POST;
			$products = $this->Products_model->do_edit($postData, $id);
			if($products){
				$this->session->set_flashdata('success', 'product '.$_POST['name'].' has been updated successfully');
				redirect("pos/products");
				
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to update product');
		redirect("pos/products");
	}
	
	public function disable($id){
		if($id){
			$products = $this->Products_model->do_disable($id);
			$this->session->set_flashdata('success', 'product successfully disabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled product');
		}
		redirect("pos/products");
	}
	
	public function enable($id){
		if($id){
			$products = $this->Products_model->do_enable($id);
			$this->session->set_flashdata('success', 'product successfully enabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to enabled product');
		}
		redirect("pos/products");
	}

	public function delete($id){
		if($id){
			$products = $this->Products_model->do_delete($id);
			$this->session->set_flashdata('success', 'product successfully deleted');
		}else{
			$this->session->set_flashdata('error', 'Failed to delete product');
		}
		redirect("pos/products");
	}
   
	public function increase_inventory($id){
		$categories = $this->Categories_model->getall();
		$data['categories'] = $categories;
		
		if($id){
			$product = $this->Products_model->get($id);
			$data['product'] = $product;
			$data['title'] = 'products';
			$this->load->view('templates/top', $data);
			$this->load->view('pos/products/increase_inventory', $data);
			$this->load->view('templates/bottom');
		
		}else{
			$this->session->set_flashdata('error', 'Failed to increase inventory');
		}
	}
	
	public function do_increase_inventory($id){
		if($id && isset($_POST)){
			$postData = $_POST;
			$product = $this->Products_model->get($id);
			
			$newQty = $product->prodQty + $postData['qty'];
			$update['prodQty'] = $newQty;

			$products = $this->Products_model->update($id, $update);
			$this->session->set_flashdata('success', $product->prodName.' increase inventory to '.$newQty.' been successfully');
		}else{
			$this->session->set_flashdata('error', 'Failed to increase inventory');
		}
		redirect("pos/products");
	}

}
