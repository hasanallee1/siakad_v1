<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProfilSekolah extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('ProfilSekolah_model', 'sekolah');
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $profil = $this->db->get('tb_profil_sekolah');

        $cek = $profil->num_rows();

        $data['cek'] = $cek;
        $data['profil'] = $profil->row_array();


        $data['title'] = 'Profil Sekolah';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('akademik/profilSekolah', $data);
        $this->load->view('template/footer', $data);
    }

    public function add()
    {
        $this->form_validation->set_rules('nama_sekolah', 'nama sekolah', 'trim|required', [
            'required' => 'Nama sekolah tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'trim|required', [
            'required' => 'Nama sekolah tidak boleh kosong !'
        ]);

        $table = 'tb_profil_sekolah';
        $date = date('Y-m-d H:i:s');


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'nama_error' => form_error('nama_sekolah'),
            );
        } else {
            $data = array(
                'nama_sekolah' => htmlspecialchars($this->input->post('nama_sekolah')),
                'npsn' => $this->input->post('npsn'),
                'bentuk_sekolah' => $this->input->post('bentuk_sekolah'),
                'alamat' => $this->input->post('alamat'),
                'desa_kelurahan' => $this->input->post('kel_des'),
                'kecamatan' => $this->input->post('kecamatan'),
                'kabupaten_kota' => $this->input->post('kab_kota'),
                'provinsi' => $this->input->post('provinsi'),
                'kode_pos' => $this->input->post('kode_pos'),
                'telp' => $this->input->post('telp'),
                'email' => $this->input->post('email'),
                'website' => $this->input->post('website'),
                'logo' => 'default-logo.jpg',
                'date_created' => $date
            );

            $this->crud->save($table, $data);
            $array = array('status' => true, 'message' => 'Data profil sekolah berhasil ditambah');
        }

        echo json_encode($array);
    }

    public function get($id)
    {
        $table = 'tb_profil_sekolah';
        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function update()
    {
        $this->form_validation->set_rules('nama_sekolah', 'nama sekolah', 'trim|required', [
            'required' => 'Nama sekolah tidak boleh kosong !'
        ]);


        $table = 'tb_profil_sekolah';
        $date = date('Y-m-d H:i:s');


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'nama_error' => form_error('nama_sekolah'),
            );
        } else {

            $id = $this->input->post('id');
            $data = array(
                'nama_sekolah' => htmlspecialchars($this->input->post('nama_sekolah')),
                'npsn' => $this->input->post('npsn'),
                'bentuk_sekolah' => $this->input->post('bentuk_sekolah'),
                'alamat' => $this->input->post('alamat'),
                'desa_kelurahan' => $this->input->post('kel_des'),
                'kecamatan' => $this->input->post('kecamatan'),
                'kabupaten_kota' => $this->input->post('kab_kota'),
                'provinsi' => $this->input->post('provinsi'),
                'kode_pos' => $this->input->post('kode_pos'),
                'telp' => $this->input->post('telp'),
                'email' => $this->input->post('email'),
                'website' => $this->input->post('website'),
                'date_modified' => $date
            );

            $this->crud->update(array('id' => $id), $data, $table);
            $array = array('status' => true, 'message' => 'Data profil sekolah berhasil diedit');
        }

        echo json_encode($array);
    }

    public function update_image()
    {
        $id = $this->input->post('id_profil');
        $upload_image = $_FILES['image']['name'];
        $date = date('Y-m-d H:i:s');

        if ($upload_image) {
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048';
            $config['upload_path'] = './assets/img';

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('image')) {
                //get image
                $profil = $this->db->get_where('tb_profil_sekolah', ['id' => $id])->row_array();

                // cek old image
                $old_image = $profil['logo'];

                if ($old_image != 'default-logo.jpg' || $old_image == '') {
                    unlink(FCPATH . 'assets/img/' . $old_image);
                    // unlink(FCPATH . 'assets/img/' . $old_image);
                }

                $img = $this->upload->data('file_name');
                $this->db->set('logo', $img);
                $this->db->set('date_modified', $date);
                $this->db->where('id', $id);
                $this->db->update('tb_profil_sekolah');

                $array = array("status" => TRUE, "message" => 'Foto berhasil diupdate !');
                echo json_encode($array);
            } else {
                // $array['upload_error'] = $this->upload->display_errors();
                $array = array(
                    'error' => true,
                    'image_error' => $this->upload->display_errors()
                );

                echo json_encode($array);
            }
        }
    }

    public function delete_image()
    {
        $id = $this->input->post('id');
        $date = date('Y-m-d H:i:s');
        //get image user
        $profil = $this->db->get_where('tb_profil_sekolah', ['id' => $id])->row_array();

        // cek old image
        $old_image = $profil['logo'];

        if ($old_image != 'default-logo.jpg') {
            unlink(FCPATH . 'assets/img/' . $old_image);
        }

        $data = array(
            'logo' => 'default-logo.jpg',
            'date_modified' => $date
        );

        $this->crud->update(array('id' => $id), $data, 'tb_profil_sekolah');

        echo json_encode(array('status' => true, 'message' => 'Foto berhasil dihapus !'));
    }
}

/* End of file ProfilSekolah.php */
