<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Tables_model');
    }

    public function index()
    {
		$tables = $this->Tables_model->getall();

		$data['tables'] = $tables;
		
		$data['title'] = 'tables';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/tables/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function add(){
		$data['title'] = 'tables';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/tables/add', $data);
		$this->load->view('templates/bottom');
	}
	
	public function do_add(){
		if(isset($_POST)){
			$postData = $_POST;
			$tables = $this->Tables_model->do_add($postData);
			if($tables){
				$this->session->set_flashdata('success', 'table '.$_POST['name'].' has been added successfully');
				redirect("pos/tables");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to add table');
		redirect("pos/tables");
	}
	
	public function edit($id = false){
		if($id){
			$table = $this->Tables_model->get($id);
			$data['table'] = $table;
			$data['title'] = 'tables';
			$this->load->view('templates/top', $data);
			$this->load->view('pos/tables/edit', $data);
			$this->load->view('templates/bottom');
		
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled table');
		}
	}
	
	public function do_edit($id){
		if(isset($_POST)){
			$postData = $_POST;
			$tables = $this->Tables_model->do_edit($postData, $id);
			if($tables){
				$this->session->set_flashdata('success', 'table '.$_POST['name'].' has been updated successfully');
				redirect("pos/tables");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Failed to update table');
		redirect("pos/tables");
	}
	
	public function disable($id){
		if($id){
			$tables = $this->Tables_model->do_disable($id);
			$this->session->set_flashdata('success', 'table successfully disabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to disabled table');
		}
		redirect("pos/tables");
	}
	
	public function enable($id){
		if($id){
			$tables = $this->Tables_model->do_enable($id);
			$this->session->set_flashdata('success', 'table successfully enabled');
		}else{
			$this->session->set_flashdata('error', 'Failed to enabled table');
		}
		redirect("pos/tables");
	}

	public function delete($id){
		if($id){
			$tables = $this->Tables_model->do_delete($id);
			$this->session->set_flashdata('success', 'table successfully deleted');
		}else{
			$this->session->set_flashdata('error', 'Failed to delete table');
		}
		redirect("pos/tables");
	}
   
}
