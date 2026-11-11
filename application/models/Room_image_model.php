<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Room_image_model extends CI_Model
{
    private $table = 'tbl_room_images';

    public function getByRoom($roomID, $includeDisabled = true)
    {
        $this->db->where('rmiRoomID', (int)$roomID);
        if (!$includeDisabled) $this->db->where('rmiStatus', 1);
        return $this->db->order_by('rmiID', 'DESC')->get($this->table)->result();
    }

    public function get($imageID)
    {
        return $this->db->where('rmiID', (int)$imageID)->get($this->table)->row();
    }

    public function create($roomID, $path)
    {
        $this->db->insert($this->table, array(
            'rmiRoomID' => (int)$roomID,
            'rmiPath' => $path,
            'rmiStatus' => 1,
            'rmiDateCreated' => date('Y-m-d H:i:s')
        ));
        return $this->db->insert_id();
    }

    public function setStatus($imageID, $status)
    {
        return $this->db->where('rmiID', (int)$imageID)->update($this->table, array('rmiStatus' => (int)$status));
    }

    public function deleteImage($imageID)
    {
        $image = $this->get($imageID);
        if (!$image) return false;
        $this->db->where('rmiID', (int)$imageID)->delete($this->table);
        if ($this->db->affected_rows() < 1) return false;
        $fullPath = FCPATH . ltrim($image->rmiPath, '/');
        if (is_file($fullPath)) @unlink($fullPath);
        return true;
    }
}
