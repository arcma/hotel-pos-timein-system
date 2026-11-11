<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Settings_model');
    }

    public function index()
    {
		$settings = getSettings();
		
		// echo "<pre>";
		// print_r($settings);
		// echo "</pre>";

		$data['title'] = 'settings';
		$data['settings'] = $settings;
        $this->load->view('templates/top', $data);
		$this->load->view('pos/settings/list', $data);
		$this->load->view('templates/bottom');
    }
	
	public function save_settings(){
        if ($this->input->method(TRUE) !== 'POST') {
            show_error('Method not allowed', 405);
            return;
        }

        // Only save settings that are editable on this page. Passwords are stored as MD5 hashes.
        $passwordSettings = array('userAdmin', 'userKitchen');
        foreach ($passwordSettings as $key) {
            $value = (string) $this->input->post($key, TRUE);
            // Blank password fields mean keep the existing password unchanged.
            if ($value !== '') {
                $this->Settings_model->save_settings($key, md5($value));
            }
        }

        $kitchenIp = $this->input->post('kitchen_ip', TRUE);
        if ($kitchenIp !== NULL) {
            $this->Settings_model->save_settings('kitchen_ip', trim((string) $kitchenIp));
        }

        $cashierIp = $this->input->post('cashier_ip', TRUE);
        if ($cashierIp !== NULL) {
            $this->Settings_model->save_settings('cashier_ip', trim((string) $cashierIp));
        }

        $this->session->set_flashdata('success', 'Settings were updated');
        redirect('pos/settings', 'location');
    }

	
   
}
