<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Hris extends MY_Controller { public function __construct(){parent::__construct();$this->load->model('Room_stays_model');}
public function index(){
    $today = date('Y-m-d');
    $rangeInput = trim((string) $this->input->get('range', true));
    $from = $today;
    $to = $today;

    if ($rangeInput !== '' && strpos($rangeInput, ' - ') !== false) {
        $parts = explode(' - ', $rangeInput, 2);
        $startDate = DateTime::createFromFormat('!m/d/Y', trim($parts[0]));
        $endDate = DateTime::createFromFormat('!m/d/Y', trim($parts[1]));
        if ($startDate && $endDate && $startDate->format('m/d/Y') === trim($parts[0]) && $endDate->format('m/d/Y') === trim($parts[1])) {
            $from = $startDate->format('Y-m-d');
            $to = $endDate->format('Y-m-d');
            if ($from > $to) { $swap = $from; $from = $to; $to = $swap; }
        }
    } elseif ($this->input->get('from', true) || $this->input->get('to', true)) {
        // Backward compatibility with bookmarked URLs using separate from/to parameters.
        $legacyFrom = (string) $this->input->get('from', true);
        $legacyTo = (string) $this->input->get('to', true);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $legacyFrom) && strtotime($legacyFrom) !== false) $from = $legacyFrom;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $legacyTo) && strtotime($legacyTo) !== false) $to = $legacyTo;
    }

    $employee = (int) $this->input->get('user', true);
    $data['from'] = $from;
    $data['to'] = $to;
    $data['range'] = date('m/d/Y', strtotime($from)) . ' - ' . date('m/d/Y', strtotime($to));
    $data['employees'] = $this->db->where('urStatus', 1)->order_by('urName')->get('tbl_users')->result();
    $data['records'] = $this->db->select('a.*,u.urName,u.urLname')->from('tbl_hris a')->join('tbl_users u','u.urID=a.userID','left')->where('DATE(a.userTimeIn) >= ' . $this->db->escape($from), null, false)->where('DATE(a.userTimeIn) <= ' . $this->db->escape($to), null, false)->order_by('a.userTimeIn','DESC')->get()->result();
    if ($employee) {
        $data['records'] = array_values(array_filter($data['records'], function($r) use ($employee) { return (int) $r->userID === $employee; }));
    }
    $data['selected_user'] = $employee;
    $data['title'] = 'hris';
    $this->load->view('templates/top', $data);
    $this->load->view('pos/hris/list', $data);
    $this->load->view('templates/bottom');
}
}
