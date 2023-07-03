<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Guru extends CI_Controller
{



    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('Guru_model', 'guru');
    }

    public function index()
    {
        $email = $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $data['title'] = 'Guru';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/guru', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $data = $this->guru->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->guru->count_all(),
            "recordsFiltered" => $this->guru->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function add()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);


        $table = 'tb_guru';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'nama_error' => form_error('nama'),
            );
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'nip' => $this->input->post('nip'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'gelar_depan' => $this->input->post('gelar_depan'),
                'gelar_belakang' => $this->input->post('gelar_belakang'),
                'telp' => $this->input->post('telp'),
                'email' => $this->input->post('email'),
                'is_active' => $this->input->post('is_active'),
                'date_created' => date('Y-m-d H:i:s')
            );

            $this->crud->save($table, $data);

            $array = array(
                'status' => true,
                'message' => 'Data Guru Berhasil Ditambahkan !'
            );
        }

        echo json_encode($array);
    }

    public function get($id)
    {
        $table = 'tb_guru';
        $data = $this->crud->getData($table, $id);

        echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);


        $table = 'tb_guru';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'nama_error' => form_error('nama'),
            );
        } else {

            $is_active = $this->input->post('is_active');

            if ($is_active == '' || $is_active == 0) {
                $is_active = 0;
            } else {
                $is_active = $is_active;
            }

            $data = array(
                'nama' => $this->input->post('nama'),
                'nip' => $this->input->post('nip'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'gelar_depan' => $this->input->post('gelar_depan'),
                'gelar_belakang' => $this->input->post('gelar_belakang'),
                'telp' => $this->input->post('telp'),
                'email' => $this->input->post('email'),
                'is_active' => $is_active,
                'date_modified' => date('Y-m-d H:i:s')
            );

            $id = $this->input->post('id');

            $this->crud->update(array('id' => $id), $data, $table);

            $array = array(
                'status' => true,
                'message' => 'Data Guru Berhasil Diedit !'
            );
        }

        echo json_encode($array);
    }

    public function delete()
    {
        $table = 'tb_guru';
        $id = $this->input->post('id');

        $this->crud->delete($table, $id);

        echo json_encode(array('status' => true, 'message' => 'Data Guru Berhasil dihapus'));
    }
}

/* End of file Guru.php */
