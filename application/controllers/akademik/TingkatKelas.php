<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TingkatKelas extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('TingkatKelas_model', 'tingkat');
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $data['title'] = 'Tingkatan Kelas';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/tingkatKelas', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $tahun = $this->tingkat->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->tingkat->count_all(),
            "recordsFiltered" => $this->tingkat->count_filtered(),
            "data" => $tahun,
        );
        //output to json format
        echo json_encode($output);
    }

    public function add()
    {
        $this->form_validation->set_rules('kode_tingkat', 'kode Tingkat', 'trim|required|is_unique[tb_tingkat_kelas.kode_tingkat]', [
            'is_unique' => 'Kode tingkatan sudah terpakai !',
            'required' => 'Kode tingkatan tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('nama_tingkat', 'nama Tingkat', 'trim|required', [
            'required' => 'Nama tingkatan tidak boleh kosong !'
        ]);

        $table = 'tb_tingkat_kelas';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'kode_error' => form_error('kode_tingkat'),
                'nama_error' => form_error('nama_tingkat')
            );
        } else {
            $data = array(
                'kode_tingkat' => $this->input->post('kode_tingkat'),
                'nama_tingkat' => $this->input->post('nama_tingkat'),
            );

            $this->crud->save($table, $data);

            $array = array(
                'status' => true,
                'message' => 'Data Tingkatan Berhasil Ditambahkan !'
            );
        }

        echo json_encode($array);
    }

    public function get($id)
    {
        $table = 'tb_tingkat_kelas';
        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('kode_tingkat', 'kode Ruang', 'trim|required', [
            'required' => 'Kode tingkatan tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('nama_tingkat', 'nama Ruang', 'trim|required', [
            'required' => 'Nama tingkatan tidak boleh kosong !'
        ]);

        $table = 'tb_tingkat_kelas';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'kode_error' => form_error('kode_tingkat'),
                'nama_error' => form_error('nama_tingkat')
            );
        } else {

            $id = $this->input->post('id');
            $data = array(
                'nama_tingkat' => $this->input->post('nama_tingkat'),
            );

            $this->crud->update(array('id' => $id), $data, $table);

            $array = array(
                'status' => true,
                'message' => 'Data Tingkatan Kelas Berhasil Diubah !'
            );
        }

        echo json_encode($array);
    }

    public function delete()
    {
        $table = 'tb_tingkat_kelas';
        $id = $this->input->post('id');
        $this->crud->delete($table, $id);

        echo json_encode(array('status' => true, 'message' => 'Data Tingkatan Kelas dihapus!'));
    }
}

/* End of file TingkatKelas.php */
