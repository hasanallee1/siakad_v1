<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Submenu_model extends CI_Model
{

    // var $table = 'user_sub_menu';

    private function _get_datatables_query($term = '')
    {
        $column = array('a.id', 'a.title', 'b.menu');
        $this->db->select('a.*, b.menu');
        $this->db->from('user_sub_menu as a');
        $this->db->join('user_menu as b', 'a.menu_id = b.id');
        $this->db->like('a.title', $term);
        $this->db->or_like('b.menu', $term);

        if (isset($_REQUEST['order'])) {
            $this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function loadDataSubMenu()
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
        $this->_get_datatables_query();
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

/* End of file Submenu_model.php */
