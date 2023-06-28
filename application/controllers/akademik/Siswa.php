<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Siswa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('Siswa_model', 'siswa');
    }

    public function index()
    {
        $email =  $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $role_id = $data['user']['role_id'];


        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();


        $data['title'] = 'Siswa';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/siswa', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $data = $this->siswa->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->siswa->count_all(),
            "recordsFiltered" => $this->siswa->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function add()
    {
        $this->form_validation->set_rules('kode_kelas', 'kode kelas', 'trim|required|is_unique[tb_kelas.kode_kelas]', [
            'is_unique' => 'Kode kelas sudah terpakai !',
            'required' => 'Kode kelas tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('nama_kelas', 'nama kelas', 'trim|required', [
            'required' => 'Nama kelas tidak boleh kosong !'
        ]);

        $table = 'tb_kelas';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'kode_error' => form_error('kode_kelas'),
                'nama_error' => form_error('nama_kelas')
            );
        } else {
            $data = array(
                'kode_kelas' => $this->input->post('kode_kelas'),
                'nama_kelas' => $this->input->post('nama_kelas'),
                'tingkat_id' => $this->input->post('tingkat'),
            );

            $this->crud->save($table, $data);

            $array = array(
                'status' => true,
                'message' => 'Data Kelas Berhasil Ditambahkan !'
            );
        }

        echo json_encode($array);
    }
}

/* End of file Siswa.php */
