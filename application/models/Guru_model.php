<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Guru_model extends CI_Model
{

    private function  _get_guru($term = '')
    {
        $column = array('a.id', 'a.nip', 'a.nama');
        $this->db->select('a.id, a.nama, a.nip, if(a.jenis_kelamin = "L", "Laki-laki", "Perempuan") as jenis_kelamin, is_active');
        $this->db->from('tb_guru as a');
        $this->db->like('a.nama', $term);
        $this->db->or_like('a.nip', $term);
        $this->db->or_like('a.jenis_kelamin', $term);

        if (isset($_REQUEST['order'])) {
            $this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function loadData()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_guru($term);
        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_guru($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('tb_guru');
        return $this->db->count_all_results();
    }
}

/* End of file Guru_model.php */
