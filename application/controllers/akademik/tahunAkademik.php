<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TahunAkademik extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('TahunAkademik_model', 'taAkademik');
    }

    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $data['title'] = 'Tahun Akademik';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('master/tahunAkademik', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $tahun = $this->taAkademik->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->taAkademik->count_all(),
            "recordsFiltered" => $this->taAkademik->count_filtered(),
            "data" => $tahun,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addTaAkademik()
    {
        $table = 'tb_tahun_akademik';
        $data = array(
            'tahun_akademik' => $this->input->post('tahun'),
        );

        $save = $this->crud->save($table, $data);
        echo json_encode(array("status" => TRUE, "message" => 'Data role berhasil ditambah !'));
    }

    public function getTaAkademik($id)
    {
        $table = 'tb_tahun_akademik';

        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function deleteTaAkademik()
    {
        $table = 'tb_tahun_akademik';

        $id = $this->input->post('id');

        $this->crud->delete($table, $id);
        echo json_encode(array("status" => TRUE, "message" => 'Data tahun akademik berhasil dihapus !'));
    }

    public function updateTaAkademik()
    {
        $table = 'tb_tahun_akademik';

        $id = $this->input->post('id');
        $data = array(
            'tahun_akademik' => $this->input->post('tahun'),
        );

        $this->crud->update(array('id' => $id), $data, $table);

        echo json_encode(array("status" => TRUE, "message" => 'Data tahun akademik berhasil diupdate !'));
    }

    public function aktif($id)
    {
        // non aktifkan yg lain 
        $off = $this->db->query("UPDATE tb_tahun_akademik SET is_active = 0 where is_active = 1 ");

        $on = $this->db->query("UPDATE tb_tahun_akademik SET is_active = 1 where id = $id");

        echo json_encode(array("status" => TRUE, "message" => 'Tahun Akademik Aktif !'));
    }
}

/* End of file TahunAkademik.php */
