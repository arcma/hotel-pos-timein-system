<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms_homepage_model extends CI_Model
{
    private $table = 'tbl_cms_homepage';

    public function get_all_sections()
    {
        $query = $this->db->get($this->table);
        $sections = array();
        foreach ($query->result() as $row) {
            $sections[$row->homeName] = $row;
        }
        return $sections;
    }

    public function get_section($name)
    {
        return $this->db->get_where($this->table, array('homeName' => $name), 1)->row();
    }

    public function save_section($name, $content, $status)
    {
        $existing = $this->get_section($name);
        $data = array(
            'homeName' => $name,
            'homeContent' => is_string($content) ? $content : json_encode($content, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'homeStatus' => (int) $status,
            'homeLastModified' => date('Y-m-d H:i:s')
        );

        if ($existing) {
            return $this->db->where('homeName', $name)->update($this->table, $data);
        }
        return $this->db->insert($this->table, $data);
    }
}
