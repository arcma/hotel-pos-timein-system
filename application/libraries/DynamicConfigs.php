<?php

class DynamicConfigs
{

    public function __construct()
    {
        $this->CI =& get_instance();
        isset($this->CI->db) or $this->CI->load->database();

        self::setSessionLocation();
        self::setSessionCookieName();
    }

    private function setSessionCookieName()
    {
        $baseUrlkey = str_replace(array('http://', 'https://'), '', base_url());
        $baseUrlkey = trim($baseUrlkey, " \t\n\r\0\x0B" . '/');
        $baseUrlkey = preg_replace('/[^a-zA-Z0-9]/', '_', $baseUrlkey);
        
        $this->CI->config->set_item('sess_cookie_name', "ci_sessions_$baseUrlkey");
    }

    private function setSessionLocation()
    {
        if ($this->CI->db->table_exists('ci_sessions')) {
            $this->CI->config->set_item('sess_driver', 'database');
            $this->CI->config->set_item('sess_save_path', 'ci_sessions');
        } else {
            $savePath = null;
            if (preg_match('~Mac OS X (.*?);~', $_SERVER['HTTP_USER_AGENT'], $matches) > 0) {
                $savePath = sys_get_temp_dir();
            }
        
            $this->CI->config->set_item('sess_driver', 'files');
            $this->CI->config->set_item('sess_save_path', $savePath);
        }
    }

}