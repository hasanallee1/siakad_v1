<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        //Do your magic here
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $role_id = $data['user']['role_id'];


        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();


        $data['title'] = 'My Profile';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('user/index', $data);
        $this->load->view('template/footer', $data);

        // echo "Selamat Datang " . $data['user']['name'];
    }

    public function updateUser()
    {

        $this->form_validation->set_rules('name', 'Name', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Email tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('image', 'Image', 'callback_file_check');

        $table = 'user';



        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'name_error' => form_error('name'),
                'email_error' => form_error('email'),
                'image_error' => form_error('image'),
            );
        } else {
            $id = $this->input->post('id');
            $upload_image = $_FILES['image']['name'];

            if ($upload_image) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '2048';
                $config['upload_path'] = './assets/img';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    //get image user
                    $user = $this->db->get_where('user', ['id' => $id])->row_array();

                    // cek old image
                    $old_image = $user['image'];

                    if ($old_image != 'user.png') {
                        unlink(FCPATH . 'assets/img/' . $old_image);
                    }

                    $img = $this->upload->data('file_name');
                } else {
                    $data['error'] = $this->upload->display_errors();
                }
            }

            $data = array(
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'image' => $img,
            );

            $this->crud->update(array('id' => $id), $data, $table);
            $array = array("status" => TRUE, "message" => 'Data user berhasil diupdate !');
        }

        echo json_encode($array);
    }

    public function file_check($str)
    {
        $allowed_mime_type_arr = array('image/gif', 'image/jpeg', 'image/jpg', 'image/png');
        $mime = get_mime_by_extension($_FILES['image']['name']);
        if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != "") {
            if (in_array($mime, $allowed_mime_type_arr)) {
                return true;
            } else {
                $this->form_validation->set_message('file_check', 'Tipe file yang diizinkan jpg/png/png.');
                return false;
            }
        }
    }
}

/* End of file User.php */
