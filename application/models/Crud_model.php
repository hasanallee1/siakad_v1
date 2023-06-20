<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Crud_model extends CI_Model
{

    public function save($table, $data)
    {
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function getData($table, $id)
    {
        $this->db->from($table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function delete($table, $id)
    {
        $this->db->where('id', $id);
        $this->db->delete($table);
    }

    public function update($where, $data, $table)
    {
        $this->db->update($table, $data, $where);

        return $this->db->affected_rows();
    }
}

/* End of file Crud_model.php */
