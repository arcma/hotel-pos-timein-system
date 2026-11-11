<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discounts extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Discounts_model');
    }

    public function index()
    {
		$discounts = $this->Discounts_model->getall();
		
		// echo "<pre>";
		// print_r($discounts);
		// echo "</pre>";

		$data['discounts'] = $discounts;
		
		$data['title'] = 'discounts';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/discounts/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function add(){
		$data['title'] = 'discounts';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/discounts/add', $data);
		$this->load->view('templates/bottom');
	}
	
	public function do_add(){
		if(isset($_POST)){
			$postData = $_POST;
			$discounts = $this->Discounts_model->do_add($postData);
			if($discounts){
				$this->session->set_flashdata('success', 'discount '.$_POST['name'].' has been added successfully');
				redirect("pos/discounts");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to add discount');
		redirect("pos/discounts");
	}
	
	public function edit($id = false){
		if($id){
			$discount = $this->Discounts_model->get($id);
			$data['discount'] = $discount;
			$data['title'] = 'discounts';
			$this->load->view('templates/top', $data);
			$this->load->view('pos/discounts/edit', $data);
			$this->load->view('templates/bottom');
		
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled discount');
		}
	}
	
	public function do_edit($id){
		if(isset($_POST)){
			$postData = $_POST;
			$discounts = $this->Discounts_model->do_edit($postData, $id);
			if($discounts){
				$this->session->set_flashdata('success', 'discount '.$_POST['name'].' has been updated successfully');
				redirect("pos/discounts");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to update discount');
		redirect("pos/discounts");
	}
	
	public function disable($id){
		if($id){
			$discounts = $this->Discounts_model->do_disable($id);
			$this->session->set_flashdata('success', 'discount successfully disabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled discount');
		}
		redirect("pos/discounts");
	}
	
	public function enable($id){
		if($id){
			$discounts = $this->Discounts_model->do_enable($id);
			$this->session->set_flashdata('success', 'discount successfully enabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to enabled discount');
		}
		redirect("pos/discounts");
	}

	public function delete($id){
		if($id){
			$discounts = $this->Discounts_model->do_delete($id);
			$this->session->set_flashdata('success', 'discount successfully deleted');
		}else{
			$this->session->set_flashdata('error', 'Failed to delete discount');
		}
		redirect("pos/discounts");
	}
   
}
