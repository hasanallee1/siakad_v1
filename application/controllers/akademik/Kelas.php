<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kelas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('Kelas_model', 'kelas');
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $data['tingkat'] = $this->db->get('tb_tingkat_kelas')->result_array();

        $data['title'] = 'Kelas';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/kelas', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $tahun = $this->kelas->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kelas->count_all(),
            "recordsFiltered" => $this->kelas->count_filtered(),
            "data" => $tahun,
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

    public function get($id)
    {
        $table = 'tb_kelas';
        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('kode_kelas', 'kode kelas', 'trim|required', [
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

            $id = $this->input->post('id');
            $data = array(
                'nama_kelas' => $this->input->post('nama_kelas'),
                'tingkat_id' => $this->input->post('tingkat'),
            );

            $this->crud->update(array('id' => $id), $data, $table);

            $array = array(
                'status' => true,
                'message' => 'Data Kelas Berhasil Diubah !'
            );
        }

        echo json_encode($array);
    }

    public function delete()
    {
        $table = 'tb_kelas';
        $id = $this->input->post('id');
        $this->crud->delete($table, $id);

        echo json_encode(array('status' => true, 'message' => 'Data Kelas dihapus!'));
    }
}

/* End of file TingkatKelas.php */
