<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Users_model');
    }

    public function index()
    {
		$users = $this->Users_model->getRegularUsers();
		
		// echo "<pre>";
		// print_r($users);
		// echo "</pre>";
		$data['users'] = $users;
		
		$data['title'] = 'users';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/users/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function add(){
		$data['title'] = 'users';
        $this->load->view('templates/top', $data);
		$this->load->view('pos/users/add', $data);
		$this->load->view('templates/bottom');
	}
	
	public function do_add(){
		if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role']) && isset($_POST['name'])){
			$postData = $_POST;
			$users = $this->Users_model->do_add($postData);
			if($users){
				$this->session->set_flashdata('success', 'User '.$_POST['name'].' has been added successfully');
				redirect("pos/users");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Falied to add user');
		redirect("pos/users");
	}
	
	public function edit($id = false){
		if($id){
			$user = $this->Users_model->get($id);
			$data['user'] = $user;
			$data['title'] = 'users';
			$this->load->view('templates/top', $data);
			$this->load->view('pos/users/edit', $data);
			$this->load->view('templates/bottom');
		
		}else{
			$this->session->set_flashdata('error', 'Falied to disabled user');
		}
	}
	
	public function do_edit($id){
		if(isset($_POST)){
			$postData = $_POST;
			$users = $this->Users_model->do_edit($postData, $id);
			if($users){
				$this->session->set_flashdata('success', 'User '.$_POST['name'].' has been updated successfully');
				redirect("pos/users");
				return false;
			}
		}
		$this->session->set_flashdata('error', 'Falied to update user');
		redirect("pos/users");
	}
	
	public function disable($id){
		if($id){
			$users = $this->Users_model->do_disable($id);
			$this->session->set_flashdata('success', 'User successfully disabled');
		}else{
			$this->session->set_flashdata('error', 'Falied to disabled user');
		}
		redirect("pos/users");
	}
	
	public function enable($id){
		if($id){
			$users = $this->Users_model->do_enable($id);
			$this->session->set_flashdata('success', 'User successfully enabled');
		}else{
			$this->session->set_flashdata('error', 'Falied to enabled user');
		}
		redirect("pos/users");
	}

	public function delete($id){
		if($id){
			$users = $this->Users_model->do_delete($id);
			$this->session->set_flashdata('success', 'User successfully deleted');
		}else{
			$this->session->set_flashdata('error', 'Falied to delete user');
		}
		redirect("pos/users");
	}
   
}
