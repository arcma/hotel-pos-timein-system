<?php

class Settings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();

		if($this->uri->segment('1') != "pos")
			return false;
		
        $this->tableName = 'tbl_settings';
        $this->tablePrefix = 'se';

		$env = strtolower($this->uri->segment('1'));
		$page = strtolower($this->uri->segment('2'));
			
		if($this->session->Userdata('login')){
			
		}else{
			$login[] = "pos";
			$login[] = "cms";
			if (in_array($env, $login)) {
				redirect('login');
			}

			

		}
		/*
		if(!$this->session->Userdata('login')){
			$allow[] = "login";
			$allow[] = "kitchen";
			$allow[] = "ajax";
			if (!in_array($env, $allow)) {
				redirect('login');
			}
		}else if($this->session->Userdata('login')->urRoleID == 3 || $this->session->Userdata('login')->urRoleID == 4 ){
			$allow[] = "home";
			$allow[] = "admin";
			$allow[] = "transactions";
			$allow[] = "ajax";
			$allow[] = "kitchen";
			if (!in_array($controller, $allow)) {
				if($this->session->Userdata('login')->urRoleID == 4)
					redirect('pos/kitchen');
				else
					redirect('pos/transactions');
			}
		}
			*/
    }

	/** Retrieve a dynamic setting value by its SeName. */
	public function get_setting($name)
	{
		$query = $this->db->select('SeSettings')
			->from('tbl_settings')
			->where('SeName', $name)
			->limit(1)
			->get();
		$row = $query->row();
		return $row ? $row->SeSettings : null;
	}

	/** Insert or update a dynamic setting value by its SeName. */
	public function set_setting($name, $value)
	{
		$query = $this->db->get_where('tbl_settings', array('SeName' => $name), 1);
		if ($query->num_rows()) {
			return $this->db->where('SeName', $name)->update('tbl_settings', array('SeSettings' => $value, 'SeLastModified' => date('Y-m-d H:i:s')));
		}
		return $this->db->insert('tbl_settings', array('SeName' => $name, 'SeSettings' => $value, 'SeLastModified' => date('Y-m-d H:i:s')));
	}

    public function getall()
    {
        $this->db->select();
        $this->db->from($this->tableName);
		$query = $this->db->get();
        return $query->result();

    }
	
	public function save_settings($name, $value){
        $current = $this->db->select('SeSettings')->from($this->tableName)
            ->where('SeName', $name)->limit(1)->get()->row();
        if (!$current) {
            return $this->db->insert($this->tableName, array(
                'SeName' => $name,
                'SeSettings' => $value,
                'SeLastModified' => date('Y-m-d H:i:s')
            ));
        }
        if ((string) $current->SeSettings === (string) $value) {
            return true;
        }
        $data = array(
            'SeSettings' => $value,
            'SeLastModified' => date('Y-m-d H:i:s')
        );
        return $this->db->where('SeName', $name)->update($this->tableName, $data);
	}
	
	
}