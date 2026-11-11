<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Categories_model');
    }

    public function index()
    {
		$categories = $this->Categories_model->getall();
		
		// echo "<pre>";
		// print_r($categories);
		// echo "</pre>";

		$data['categories'] = $categories;
		
		$data['title'] = 'categories';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/categories/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function add(){
		$data['title'] = 'categories';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/categories/add', $data);
		$this->load->view('templates/bottom');
	}
	
	public function do_add(){
		if(isset($_POST)){
			$postData = $_POST;
			$categories = $this->Categories_model->do_add($postData);
			if($categories){
				$this->session->set_flashdata('success', 'category '.$_POST['name'].' has been added successfully');
				redirect("pos/categories");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to add category');
		redirect("pos/categories");
	}
	
	public function edit($id = false){
		if($id){
			$category = $this->Categories_model->get($id);
			$data['category'] = $category;
			$data['title'] = 'categories';
			$this->load->view('templates/top', $data);
			$this->load->view('pos/categories/edit', $data);
			$this->load->view('templates/bottom');
		
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled category');
		}
	}
	
	public function do_edit($id){
		if(isset($_POST)){
			$postData = $_POST;
			$categories = $this->Categories_model->do_edit($postData, $id);
			if($categories){
				$this->session->set_flashdata('success', 'category '.$_POST['name'].' has been updated successfully');
				redirect("pos/categories");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to update category');
		redirect("pos/categories");
	}
	
	public function disable($id){
		if($id){
			$categories = $this->Categories_model->do_disable($id);
			$this->session->set_flashdata('success', 'category successfully disabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled category');
		}
		redirect("pos/categories");
	}
	
	public function enable($id){
		if($id){
			$categories = $this->Categories_model->do_enable($id);
			$this->session->set_flashdata('success', 'category successfully enabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to enabled category');
		}
		redirect("pos/categories");
	}

	public function delete($id){
		if($id){
			$categories = $this->Categories_model->do_delete($id);
			$this->session->set_flashdata('success', 'category successfully deleted');
		}else{
			$this->session->set_flashdata('error', 'Failed to delete category');
		}
		redirect("pos/categories");
	}
   
}
