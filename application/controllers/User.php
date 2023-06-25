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
        // $this->form_validation->set_rules('image', 'Image', 'callback_file_check');

        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'name_error' => form_error('name'),
                'email_error' => form_error('email'),
            );
        } else {
            $id = $this->input->post('id');
            $name = htmlspecialchars($this->input->post('name', true));
            $email = htmlspecialchars($this->input->post('email', true));
            $date = date('Y-m-d H:i:s');
            $table = 'user';

            $data = array(
                'name' => $name,
                'date_modified' => $date
            );

            $this->crud->update(array('id' => $id), $data, $table);

            // $this->db->set('name', $name);
            // $this->db->set('date_modified', $date);
            // $this->db->where('id', $id);
            // $this->db->update('user');

            $array = array("status" => TRUE, "message" => 'Data user berhasil diupdate !');
        }

        echo json_encode($array);
    }

    public function update_image()
    {
        $id = $this->input->post('id_user');
        $upload_image = $_FILES['image']['name'];
        $date = date('Y-m-d H:i:s');

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

                if ($old_image != 'user.png' || $old_image == '') {
                    unlink(FCPATH . 'assets/img/' . $old_image);
                    // unlink(FCPATH . 'assets/img/' . $old_image);
                }

                $img = $this->upload->data('file_name');
                $this->db->set('image', $img);
                $this->db->set('date_modified', $date);
                $this->db->where('id', $id);
                $this->db->update('user');

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
        $user = $this->db->get_where('user', ['id' => $id])->row_array();

        // cek old image
        $old_image = $user['image'];

        if ($old_image != 'user.png') {
            unlink(FCPATH . 'assets/img/' . $old_image);
        }

        $data = array(
            'image' => 'user.png',
            'date_modified' => $date
        );

        $this->crud->update(array('id' => $id), $data, 'user');

        echo json_encode(array('status' => true, 'message' => 'Foto berhasil dihapus !'));
    }

    public function changePassword()
    {
        $id = $this->input->post('user_id');
        $this->form_validation->set_rules('currentPassword', 'Current Password', 'required|trim');

        $this->form_validation->set_rules('newpassword1', 'Password1', 'required|trim|min_length[6]|matches[newpassword2]', [
            'matches' => 'Password tidak sama!',
            'min_length' => 'Password terlalu pendek !',
            'required' => 'Password tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('newpassword2', 'Password2', 'required|trim');


        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'current_error' => form_error('currentPassword'),
                'password1_error' => form_error('newpassword1'),
                'password2_error' => form_error('newpassword2'),
            );
            echo json_encode($array);
        } else {

            $id = $this->input->post('user_id');
            $date = date('Y-m-d H:i:s');

            $currentPassword = $this->input->post('currentPassword');
            $newpassword1 = $this->input->post('newpassword1');

            $user = $this->db->get_where('user', ['id' => $id])->row_array();

            // get old password
            $old_password = $user['password'];

            if (!password_verify($currentPassword, $old_password)) {
                $array = array('cekPassword' => true, 'message' => 'Password salah !');
                echo json_encode($array);
            } else if ($currentPassword == $newpassword1) {
                $array = array('cekPassword' => true, 'message' => 'Password tidak boleh sama dengan yang sebelumnya !');
                echo json_encode($array);
            } else {
                $newPassword = password_hash($this->input->post('newpassword1'), PASSWORD_DEFAULT);
                $this->db->set('date_modified', $date);
                $this->db->set('password', $newPassword);
                $this->db->where('id', $id);
                $this->db->update('user');
                $array = array('status' => true, 'message' => 'Password berhasil diubah');
                echo json_encode($array);
            }
        }
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
