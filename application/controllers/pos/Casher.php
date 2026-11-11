<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        
		if(!checkAdmin()){
			// redirect("pos/home");
		}
    }

    public function index()
    {
        redirect("pos/admin/dashboard");
       
    }
	
	function dashboard(){
		$this->load->view('pos/templates/top');
		$this->load->view('admin/dashboard');
		$this->load->view('templates/bottom');
	}
	
	function do_logout(){
		$this->session->unset_userdata('login');
		redirect("pos/login");
	}

   
}
