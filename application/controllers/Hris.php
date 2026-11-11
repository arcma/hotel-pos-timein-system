<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hris extends MY_Controller
{
    private $hrisSalt = 'saltme';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Settings_model');
        date_default_timezone_set('Asia/Manila');
    }

    private function dailyCalendarId()
    {
        return md5($this->hrisSalt . date('Y-m-d', strtotime('now')));
    }

    // Admin login page: http://localhost/hris
    public function index()
    {
        $this->load->view('hris/login');
    }

    // Employee login page. The daily ID is issued after admin login and remains
    // usable for the current date even if the admin session later expires.
    public function login()
    {
        $id = (string) $this->input->get('id', true);
        if (!hash_equals($this->dailyCalendarId(), $id)) {
            redirect('hris');
            return;
        }
        $data['calendar_id'] = $this->dailyCalendarId();
        $this->load->view('hris/employee_login', $data);
    }

    public function admin_login()
    {
        if ($this->input->method(true) !== 'POST') {
            redirect('hris');
            return;
        }

        $password = (string) $this->input->post('password');
        // HRIS administrator credentials are configured in tbl_settings.SeSettings
        // under the SeName 'userAdmin', using the same MD5 format as POS settings.
        $storedPassword = $this->Settings_model->get_setting('userAdmin');
        if (!$storedPassword || !hash_equals((string) $storedPassword, md5($password))) {
            $this->session->set_flashdata('hris_error', 'Invalid admin password.');
            redirect('hris');
            return;
        }

        $this->session->set_userdata('hris_admin_user', (object) array(
            'urID' => 0,
            'urName' => 'Admin',
            'urLname' => '',
            'urUsername' => 'admin'
        ));

        redirect('hris/login?id=' . $this->dailyCalendarId());
    }

    // Employee login submission, used by /hris/login
    public function employee_login_post()
    {
        $id = (string) $this->input->post('id', true);
        if (!hash_equals($this->dailyCalendarId(), $id)) {
            redirect('hris');
            return;
        }
        if ($this->input->method(true) !== 'POST') {
            redirect('hris/login?id=' . $this->dailyCalendarId());
            return;
        }

        $username = trim((string) $this->input->post('username', true));
        $password = (string) $this->input->post('password');
        $user = $this->db->where('urUsername', $username)->where('urStatus', 1)->get('tbl_users')->row();

        if (!$user || !hash_equals((string) $user->urPassword, md5($password))) {
            $this->session->set_flashdata('hris_error', 'Invalid username or password.');
            redirect('hris/login?id=' . $this->dailyCalendarId());
            return;
        }

        $this->session->unset_userdata('hris_employee_auth_expires');
        $this->session->set_userdata('hris_user', (object) array(
            'urID' => $user->urID,
            'urName' => $user->urName,
            'urLname' => $user->urLname,
            'urUsername' => $user->urUsername
        ));

        // If the employee has already checked in but has not checked out, start
        // a fresh five-minute window for this authenticated session as well.
        $todayRecord = $this->db->where('userID', $user->urID)
            ->where('DATE(userTimeIn) = ' . $this->db->escape(date('Y-m-d')), null, false)
            ->get('tbl_hris')->row();
        if ($todayRecord && empty($todayRecord->userTimeOut)) {
            $this->session->set_userdata('hris_employee_auth_expires', time() + 300);
        }
        redirect('hris/calendar?id=' . $this->dailyCalendarId());
    }

    private function requireAdmin()
    {
        $user = $this->session->userdata('hris_admin_user');
        if (!$user) {
            redirect('hris');
            return false;
        }
        return $user;
    }

    public function calendar()
    {
        $id = (string) $this->input->get('id', true);
        if (!hash_equals($this->dailyCalendarId(), $id)) {
            redirect('hris');
            return;
        }

        $user = $this->session->userdata('hris_user');
        if (!$user) {
            redirect('hris/login?id=' . $this->dailyCalendarId());
            return;
        }

        // A successful check-in starts a five-minute employee session window.
        $expires = (int) $this->session->userdata('hris_employee_auth_expires');
        if ($expires && time() >= $expires) {
            $this->session->unset_userdata(array('hris_user', 'hris_employee_auth_expires'));
            redirect('hris/login?id=' . $this->dailyCalendarId());
            return;
        }

        $data['user'] = $user;
        $data['calendar_id'] = $this->dailyCalendarId();
        $data['auto_logout_at'] = $expires;
        $data['month'] = $this->input->get('month', true) ?: date('Y-m');
        if (!preg_match('/^\d{4}-\d{2}$/', $data['month'])) $data['month'] = date('Y-m');
        $data['records'] = $this->db->where('userID', $user->urID)
            ->where('userTimeIn >=', $data['month'] . '-01 00:00:00')
            ->where('userTimeIn <', date('Y-m-d H:i:s', strtotime($data['month'] . '-01 +1 month')))
            ->get('tbl_hris')->result();
        $data['today'] = $this->db->where('userID', $user->urID)
            ->where('DATE(userTimeIn) = ' . $this->db->escape(date('Y-m-d')), null, false)
            ->get('tbl_hris')->row();
        $data['can_checkin'] = !$data['today'];
        $data['can_checkout'] = $data['today'] && empty($data['today']->userTimeOut);
        $this->load->view('hris/calendar', $data);
    }

    // Keep old links working by routing employee attendance to the protected calendar.
    public function employee_calendar()
    {
        redirect('hris/calendar?id=' . $this->dailyCalendarId());
    }

    public function punch()
    {
        if ($this->input->method(true) !== 'POST') show_error('Invalid request method', 405);

        $id = (string) $this->input->post('id', true);
        if (!hash_equals($this->dailyCalendarId(), $id)) {
            redirect('hris');
            return;
        }

        $user = $this->session->userdata('hris_user');
        if (!$user) {
            redirect('hris/login?id=' . $this->dailyCalendarId());
            return;
        }

        $expires = (int) $this->session->userdata('hris_employee_auth_expires');
        if ($expires && time() >= $expires) {
            $this->session->unset_userdata(array('hris_user', 'hris_employee_auth_expires'));
            redirect('hris/login?id=' . $this->dailyCalendarId());
            return;
        }

        $action = $this->input->post('action', true);
        $now = date('Y-m-d H:i:s');
        $today = date('Y-m-d');
        $record = $this->db->where('userID', $user->urID)
            ->where('DATE(userTimeIn) = ' . $this->db->escape($today), null, false)
            ->get('tbl_hris')->row();

        if ($action === 'checkin') {
            if ($record) {
                $this->session->set_flashdata('hris_error', 'You already checked in today.');
            } else {
                $this->db->insert('tbl_hris', array('userID' => $user->urID, 'userTimeIn' => $now, 'userTimeOut' => null));
                // The five-minute window begins only after a successful check-in.
                $expires = time() + 300;
                $this->session->set_userdata('hris_employee_auth_expires', $expires);
                $this->session->set_flashdata('hris_success', 'Check-in recorded.');
            }
        } elseif ($action === 'checkout') {
            if (!$record) {
                $this->session->set_flashdata('hris_error', 'You have not checked in today.');
            } elseif ($record->userTimeOut) {
                $this->session->set_flashdata('hris_error', 'You already checked out today.');
            } else {
                $this->db->where('userID', $user->urID)->where('userTimeIn', $record->userTimeIn)
                    ->update('tbl_hris', array('userTimeOut' => $now));
                $this->session->set_flashdata('hris_success', 'Check-out recorded.');
            }
        } else {
            $this->session->set_flashdata('hris_error', 'Invalid attendance action.');
        }

        redirect('hris/calendar?id=' . $this->dailyCalendarId());
    }

    public function logout()
    {
        $this->session->unset_userdata('hris_admin_user');
        redirect('hris');
    }

    public function employee_logout()
    {
        $this->session->unset_userdata(array('hris_user', 'hris_employee_auth_expires'));
        redirect('hris/login?id=' . $this->dailyCalendarId());
    }
}
