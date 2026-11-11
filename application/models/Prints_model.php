<?php
/** Central print queue backed by tbl_prints. */
class Prints_model extends CI_Model
{
    public function add($type, $cartId = null, $kitchenId = null, $data = null)
    {
        return $this->db->insert('tbl_prints', array(
            'prtType' => (int) $type,
            'prtCartID' => $cartId === null ? null : (int) $cartId,
            'prtKitchenID' => $kitchenId === null ? null : (int) $kitchenId,
            'prtData' => $data === null ? null : (is_string($data) ? $data : json_encode($data)),
            'prtStatus' => 0,
            'prtDateCreated' => date('Y-m-d H:i:s'),
            'prtDatePrinted' => null
        )) ? $this->db->insert_id() : false;
    }

    public function getPending($limit = 50)
    {
        return $this->db->from('tbl_prints')
            ->where('prtStatus', 0)
            ->order_by('prtDateCreated', 'ASC')
            ->limit((int) $limit)
            ->get()->result();
    }

    public function markPrinted($id)
    {
        return $this->db->where('prtID', (int) $id)->where('prtStatus', 0)
            ->update('tbl_prints', array(
                'prtStatus' => 1,
                'prtDatePrinted' => date('Y-m-d H:i:s')
            ));
    }

    public function getRecent($limit = 100)
    {
        return $this->db->select('prtID, prtType, prtCartID, prtKitchenID, prtStatus, prtDateCreated, prtDatePrinted')
            ->from('tbl_prints')->order_by('prtID', 'DESC')->limit((int) $limit)->get()->result();
    }
}
