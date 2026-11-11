<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

class Authit {

	private $CI;
	protected $PasswordHash;
	
	public function __construct()
	{
		$this->CI =& get_instance();

		$this->CI->load->database();
		$this->CI->load->model('Logs', '', true);
	}
	
	public function logged_in()
	{
		return $this->CI->session->userdata('logged_in');
	}
	
	public function login($username, $password)
	{
		$user = $this->CI->User->getUserByUsername($username);
		if($user){
			$this->CI->Logs->addLogs('User Logged In', $user->username);
			if(password_verify($password . PASSWORD_SALT, $user->password)){
				unset($user->password);
				$this->CI->session->set_userdata(array(
					'logged_in' => !$this->isMfaEnabled(),
					'user' => $user
				));
				$this->CI->User->update($user->ID, array('last_login' => date('Y-m-d H:i:s')));
				return true;
			}
		}
		
		return false;
	}
	
	public function logout($redirect = false)
	{
		$this->CI->Logs->addLogs('User Logged Out', $this->CI->session->userdata('user')->username);
		
		$this->CI->session->unset_userdata('logged_in');
		if($redirect){
			$this->CI->load->helper('url');
			redirect($redirect, 'refresh');
		}
	}

	public function sendOTP() {
		$user = $this->CI->session->userdata('user');
		$otp = $this->generateOTP();

		$emailSent = false;
		if (isset($user->email) && !empty($user->email)) {
			$emailSent = sendEmailOTP($user->email, $otp);
		}

		$whatsAppSent = false;
		if (isset($user->number) && !empty($user->number)) {
			$whatsAppSent = sendWhatsAppOTP($user->number, $otp);
		}

		$slackOTP = false;
		if (isset($user->slack) && $user->slack == '1') {
			$slackOTP = sendSlackOTP($user->username);
		}

		return $emailSent || $whatsAppSent || $slackOTP;
	}

	public function generateOTP() {
		// Generate random OTP
		$otp = rand(100000, 999999);
	
		// Store OTP in session expire after 10 mins
		$this->CI->session->set_tempdata('otp', $otp, 600);

		return $otp;
	}

	public function verifyOTP($otp) {
		$otpConfirmed = $otp == $_SESSION['otp'];

		$this->CI->session->set_userdata(array(
			'logged_in' => $otpConfirmed,
		));

		return $otpConfirmed;
	}

	public function isMfaEnabled() {
		return !isLocal();
	}
	
	public function resetPassword($user_id, $new_password)
	{
		$new_password = password_hash($new_password . PASSWORD_SALT, PASSWORD_DEFAULT);
		$this->CI->User->update($user_id, array('password' => $new_password));
	}

    public function isSuperAdmin()
    {
        $user = $this->CI->session->userdata('user');
        return $this->logged_in() && isRoleIdSuperAdmin($user->role);
    }

	public function isAdmin()
	{
		$user = $this->CI->session->userdata('user');
        return $this->logged_in() && isRoleIdAdmin($user->role);
	}

	public function checkAdmin()
	{
		if(!$this->isAdmin()) {
			$this->CI->session->set_flashdata('error', 'Administrator privileges required to access this page.');
			redirect(base_url('admin/login'));
			die();
		}
	}

	public function checkLoggedIn()
	{
		if(!$this->logged_in()) {
			$this->CI->session->set_flashdata('error', 'Authorization required to access this page.');
			redirect(base_url('admin/login?redirect='.$this->CI->uri->uri_string));
			die();
		}
	}

	public function verifyPasswordByUserId($userId, $password)
	{
		if (!$userId || !$password) {
			return false;
		}
		$user = $this->CI->User->get($userId);
		return $user && password_verify($password . PASSWORD_SALT, $user->password);
	}

	public function verifyPasswordOfCurrentUser($password)
	{
		$currentUserId = user('ID');
		if (!$currentUserId || !$password) {
			return false;
		}
		return $this->verifyPasswordByUserId($currentUserId, $password);
	}

}
