<?php
defined('BASEPATH') or exit('No direct script access allowed');

class RuangKelas extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('RuangKelas_model', 'kelas');
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $data['title'] = 'Ruang Kelas';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/ruangKelas', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $data = $this->kelas->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->kelas->count_all(),
            "recordsFiltered" => $this->kelas->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function add()
    {
        $this->form_validation->set_rules('kode_ruang', 'kode Ruang', 'trim|required|is_unique[tb_ruang_kelas.kode_ruangan]', [
            'is_unique' => 'Kode ruangan sudah terpakai !',
            'required' => 'Kode ruangan tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('nama_ruang', 'nama Ruang', 'trim|required', [
            'required' => 'Nama ruangan tidak boleh kosong !'
        ]);

        $table = 'tb_ruang_kelas';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'kode_error' => form_error('kode_ruang'),
                'nama_error' => form_error('nama_ruang')
            );
        } else {
            $data = array(
                'kode_ruangan' => $this->input->post('kode_ruang'),
                'nama_ruangan' => $this->input->post('nama_ruang'),
            );

            $this->crud->save($table, $data);

            $array = array(
                'status' => true,
                'message' => 'Data Ruangan Berhasil Ditambahkan !'
            );
        }

        echo json_encode($array);
    }

    public function get($id)
    {
        $table = 'tb_ruang_kelas';
        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('kode_ruang', 'kode Ruang', 'trim|required', [
            'required' => 'Kode ruangan tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('nama_ruang', 'nama Ruang', 'trim|required', [
            'required' => 'Nama ruangan tidak boleh kosong !'
        ]);

        $table = 'tb_ruang_kelas';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'kode_error' => form_error('kode_ruang'),
                'nama_error' => form_error('nama_ruang')
            );
        } else {

            $id = $this->input->post('id');
            $data = array(
                'nama_ruangan' => $this->input->post('nama_ruang'),
            );

            $this->crud->update(array('id' => $id), $data, $table);

            $array = array(
                'status' => true,
                'message' => 'Data Ruangan Berhasil Diubah !'
            );
        }

        echo json_encode($array);
    }

    public function delete()
    {
        $table = 'tb_ruang_kelas';
        $id = $this->input->post('id');
        $this->crud->delete($table, $id);

        echo json_encode(array('status' => true, 'message' => 'Data Ruangan dihapus!'));
    }
}

/* End of file RuangKelas.php */
