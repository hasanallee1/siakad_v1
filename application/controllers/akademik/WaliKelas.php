<?php

defined('BASEPATH') or exit('No direct script access allowed');

class WaliKelas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('WaliKelas_model', 'wali');
    }

    public function index()
    {
        $email =  $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $role_id = $data['user']['role_id'];


        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();


        $data['title'] = 'Wali Kelas';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/waliKelas', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $data = $this->wali->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->wali->count_all(),
            "recordsFiltered" => $this->wali->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
}

/* End of file WaliKelas.php */
