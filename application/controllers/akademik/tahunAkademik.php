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

        $data['title'] = 'Tahun Pelajaran';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/tahunAkademik', $data);
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
        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required', [
            'required' => 'Isi tahun pelajaran terlebih dahulu !'
        ]);


        $table = 'tb_tahun_akademik';

        if ($this->form_validation->run() == false) {

            $array = array(
                'error' => true,
                'tahun_error' => form_error('tahun'),
            );
        } else {
            $data = array(
                'tahun_akademik' => $this->input->post('tahun'),
            );

            $save = $this->crud->save($table, $data);
            $array = array("status" => TRUE, "message" => 'Data role berhasil ditambah !');
        }

        echo json_encode($array);
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

        $this->form_validation->set_rules('tahun', 'Tahun', 'trim|required', [
            'required' => 'Isi tahun pelajaran terlebih dahulu !'
        ]);


        $table = 'tb_tahun_akademik';

        if ($this->form_validation->run() == false) {

            $array = array(
                'error' => true,
                'tahun_error' => form_error('tahun'),
            );
        } else {
            $id = $this->input->post('id');
            $data = array(
                'tahun_akademik' => $this->input->post('tahun'),
            );

            $this->crud->update(array('id' => $id), $data, $table);
            $array = array("status" => TRUE, "message" => 'Data role berhasil diupdate !');
        }

        echo json_encode($array);
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
