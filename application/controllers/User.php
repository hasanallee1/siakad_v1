<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        //Do your magic here
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $role_id = $data['user']['role_id'];


        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $data['ta'] = tahun_akademik();

        $data['title'] = 'My Profile';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('template/footer', $data);

        // echo "Selamat Datang " . $data['user']['name'];
    }
}

/* End of file User.php */
