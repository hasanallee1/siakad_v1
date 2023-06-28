<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MataPelajaran extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('MataPelajaran_model', 'mapel');
    }

    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $data['title'] = 'Mata Pelajaran';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/mataPelajaran', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $data = $this->mapel->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->mapel->count_all(),
            "recordsFiltered" => $this->mapel->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addMapel()
    {
        $this->form_validation->set_rules('mapel', 'Mapel', 'trim|required', [
            'required' => 'Isi mata pelajaran terlebih dahulu !'
        ]);
        $this->form_validation->set_rules(
            'kode_mapel',
            'Kode Mapel',
            'trim|required|is_unique[tb_mata_pelajaran.kode_mapel]',
            [
                'is_unique' => 'Kode Mata Pelajaran Sudah Ada !',
                'required' => 'Isi kode mata pelajaran terlebih dahulu !'
            ]
        );

        $table = 'tb_mata_pelajaran';

        if ($this->form_validation->run() == false) {

            $array = array(
                'error' => true,
                'mapel_error' => form_error('mapel'),
                'kode_error' => form_error('kode_mapel')
            );
        } else {
            $data = array(
                'kode_mapel' => $this->input->post('kode_mapel'),
                'nama_mapel' => $this->input->post('mapel')
            );

            $save = $this->crud->save($table, $data);

            $array = array("status" => TRUE, "message" => 'Data mata pelajaran berhasil ditambah !');
        }

        echo json_encode($array);
    }

    public function getMapel($id)
    {
        $table = 'tb_mata_pelajaran';

        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function updateMapel()
    {
        $this->form_validation->set_rules('mapel', 'Mapel', 'trim|required', [
            'required' => 'Isi mata pelajaran terlebih dahulu !'
        ]);
        $this->form_validation->set_rules(
            'kode_mapel',
            'Kode Mapel',
            'trim|required',
            [
                'required' => 'Isi kode mata pelajaran terlebih dahulu !'
            ]
        );

        $table = 'tb_mata_pelajaran';

        if ($this->form_validation->run() == false) {

            $array = array(
                'error' => true,
                'mapel_error' => form_error('mapel'),
                'kode_error' => form_error('kode_mapel')
            );
        } else {

            $id = $this->input->post('id');
            $data = array(
                'kode_mapel' => $this->input->post('kode_mapel'),
                'nama_mapel' => $this->input->post('mapel')
            );

            $update = $this->crud->update(array('id' => $id), $data, $table);

            $array = array("status" => TRUE, "message" => 'Data mata pelajaran berhasil diupdate !');
        }

        echo json_encode($array);
    }

    public function deleteMapel()
    {
        $table = 'tb_mata_pelajaran';

        $id = $this->input->post('id');

        $this->crud->delete($table, $id);
        echo json_encode(array("status" => TRUE, "message" => 'Data mata pelajaran berhasil dihapus !'));
    }
}

/* End of file MataPelajaran.php */
