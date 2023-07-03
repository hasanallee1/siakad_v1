<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Kelas_model extends CI_Model
{

    private function _get_datatables_query($term = '')
    {
        $column = array('a.id', 'b.kode_tingkat', 'a.kode_kelas', 'a.nama_kelas');
        $this->db->select('a.*, b.kode_tingkat');
        $this->db->from('tb_kelas as a');
        $this->db->join('tb_tingkat_kelas as b', 'a.tingkat_id = b.id', 'inner');
        $this->db->like('a.nama_kelas', $term);
        $this->db->or_like('a.kode_kelas', $term);
        $this->db->or_like('b.kode_tingkat', $term);

        if (isset($_REQUEST['order'])) {
            $this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function loadData()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_filtered()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_datatables_query($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        // $this->db->from($this->table);
        // return $this->db->count_all_results();
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
}

/* End of file Kelas_model.php */
