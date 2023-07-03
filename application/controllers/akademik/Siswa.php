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
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('nis', 'NIS', 'is_unique[tb_siswa.nis]', [
            'is_unique' => 'NIS sudah digunakan !'
        ]);

        $table = 'tb_siswa';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'nama_error' => form_error('nama'),
                'nis_error' => form_error('nis'),
            );
        } else {
            $data = array(
                'nama' => $this->input->post('nama'),
                'nis' => $this->input->post('nis'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'agama' => $this->input->post('agama'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'kelurahan' => $this->input->post('kel_des'),
                'kecamatan' => $this->input->post('kecamatan'),
                'kabupaten' => $this->input->post('kab_kota'),
                'provinsi' => $this->input->post('provinsi'),
                'kode_pos' => $this->input->post('kode_pos'),
                'ayah' => $this->input->post('ayah'),
                'pekerjaan_ayah' => $this->input->post('pekerjaan_ayah'),
                'no_telp_ayah' => $this->input->post('no_telp_ayah'),
                'ibu' => $this->input->post('ibu'),
                'pekerjaan_ibu' => $this->input->post('pekerjaan_ibu'),
                'no_telp_ibu' => $this->input->post('no_telp_ibu'),
                'wali' => $this->input->post('wali'),
                'pekerjaan_wali' => $this->input->post('pekerjaan_wali'),
                'no_telp_wali' => $this->input->post('no_telp_wali'),
                'is_active' => $this->input->post('is_active'),
                'date_created' => date('Y-m-d H:i:s')
            );

            $this->crud->save($table, $data);

            $array = array(
                'status' => true,
                'message' => 'Data Siswa Berhasil Ditambahkan !'
            );
        }

        echo json_encode($array);
    }

    public function get($id)
    {
        $table = 'tb_siswa';
        $data = $this->crud->getData($table, $id);

        echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);

        $table = 'tb_siswa';


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => TRUE,
                'nama_error' => form_error('nama')
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
                'nis' => $this->input->post('nis'),
                'jenis_kelamin' => $this->input->post('jenis_kelamin'),
                'agama' => $this->input->post('agama'),
                'tempat_lahir' => $this->input->post('tempat_lahir'),
                'tanggal_lahir' => $this->input->post('tanggal_lahir'),
                'alamat' => $this->input->post('alamat'),
                'kelurahan' => $this->input->post('kel_des'),
                'kecamatan' => $this->input->post('kecamatan'),
                'kabupaten' => $this->input->post('kab_kota'),
                'provinsi' => $this->input->post('provinsi'),
                'kode_pos' => $this->input->post('kode_pos'),
                'ayah' => $this->input->post('ayah'),
                'pekerjaan_ayah' => $this->input->post('pekerjaan_ayah'),
                'no_telp_ayah' => $this->input->post('no_telp_ayah'),
                'ibu' => $this->input->post('ibu'),
                'pekerjaan_ibu' => $this->input->post('pekerjaan_ibu'),
                'no_telp_ibu' => $this->input->post('no_telp_ibu'),
                'wali' => $this->input->post('wali'),
                'pekerjaan_wali' => $this->input->post('pekerjaan_wali'),
                'no_telp_wali' => $this->input->post('no_telp_wali'),
                'is_active' => $is_active,
                'date_modified' => date('Y-m-d H:i:s')
            );

            $id = $this->input->post('id');

            $this->crud->update(array('id' => $id), $data, $table);

            $array = array(
                'status' => true,
                'message' => 'Data Siswa Berhasil Diedit !'
            );
        }

        echo json_encode($array);
    }

    public function delete()
    {
        $id = $this->input->post('id');
        $table = 'tb_siswa';

        $this->crud->delete($table, $id);
        $array = array(
            'status' => true,
            'message' => 'Data Siswa Berhasil Dihapus !'
        );
        echo json_encode($array);
    }
}

/* End of file Siswa.php */
