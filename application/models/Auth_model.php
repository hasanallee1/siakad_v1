<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function save($tabel, $data)
    {
        $this->db->insert($tabel, $data);
    }

    // public function get_where()
}

/* End of file Auth_model.php */
