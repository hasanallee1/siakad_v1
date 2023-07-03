<?php


defined('BASEPATH') or exit('No direct script access allowed');

class Admin_model extends CI_Model
{

    var $table = 'user_role';
    var $column_order = array(null, 'role', null);
    var $column_search = array('role');
    var $order = array('id' => 'asc'); //default order

    private function  _get_datatables_query()
    {
        $this->db->from($this->table);
        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function loadData()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
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
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    /** Data User */

    private function  _get_user($term = '')
    {
        $column = array('a.name', 'a.email', 'b.role');
        $this->db->select('a.*, b.role');
        $this->db->from('user as a');
        $this->db->join('user_role as b', 'a.role_id = b.id');
        $this->db->like('a.name', $term);
        $this->db->or_like('a.email', $term);
        $this->db->or_like('b.role', $term);

        if (isset($_REQUEST['order'])) {
            $this->db->order_by($column[$_REQUEST['order']['0']['column']], $_REQUEST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function loadDataUser()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_user($term);
        if ($_REQUEST['length'] != -1)
            $this->db->limit($_REQUEST['length'], $_REQUEST['start']);
        $query = $this->db->get();
        return $query->result_array();
    }

    function count_filtered_user()
    {
        $term = $_REQUEST['search']['value'];
        $this->_get_user($term);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_user()
    {
        $this->db->from('user');
        return $this->db->count_all_results();
    }
}

/* End of file Admin_model.php */
