<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa_model extends CI_Model
{

    private function  _get_siswa($term = '')
    {
        $column = array('a.id', 'a.nis', 'a.nama');
        $this->db->select('a.id, a.nama, a.nis, a.tempat_lahir,a.tanggal_lahir as tanggal_lahir');
        // $this->db->select('a.id, a.nama, a.nis, a.tempat_lahir,tanggal_indo(a.tanggal_lahir) as tanggal_lahir');
        $this->db->from('tb_siswa as a');
        $this->db->like('a.nama', $term);
        $this->db->or_like('a.nis', $term);
        $this->db->or_like('a.tempat_lahir', $term);

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
        $this->_get_siswa($term);
        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_siswa($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from('tb_siswa');
        return $this->db->count_all_results();
    }
}

/* End of file Siswa_model.php */
