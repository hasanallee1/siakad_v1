<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Admin_model', 'admin');
        $this->load->model('Crud_model', 'crud');
    }


    public function index()
    {
        $email =  $this->session->userdata('email');
        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $data['title'] = 'Dashboard';

        // get user role
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        // get jumlah user aktif
        $user = $this->db->query("SELECT count(*) as jum from user where is_active = 1")->row_array();
        $data['jum_user'] = $user['jum'];


        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('admin/index', $data);
        $this->load->view('template/footer', $data);
    }

    public function roleAccess()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $data['title'] = 'Role Access Management';


        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('admin/roleAccess', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadData()
    {
        $role = $this->admin->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->admin->count_all(),
            "recordsFiltered" => $this->admin->count_filtered(),
            "data" => $role,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addRole()
    {
        $table = 'user_role';
        $data = array(
            'role' => $this->input->post('role')
        );

        $save = $this->crud->save($table, $data);
        echo json_encode(array("status" => TRUE, "message" => 'Data role berhasil ditambah !'));
    }

    public function getRole($id)
    {
        $table = 'user_role';

        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function deleteRole()
    {
        $table = 'user_role';

        $id = $this->input->post('id');

        $this->crud->delete($table, $id);
        echo json_encode(array("status" => TRUE, "message" => 'Data role berhasil dihapus !'));
    }

    public function updateRole()
    {
        $table = 'user_role';

        $id = $this->input->post('id');
        $data = array(
            'role' => $this->input->post('role')
        );

        $this->crud->update(array('id' => $id), $data, $table);

        echo json_encode(array("status" => TRUE, "message" => 'Data role berhasil diupdate !'));
    }

    public function accessMenu($id)
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $role_id = $data['user']['role_id'];

        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $data['roleId'] = $this->db->get_where('user_role', ['id' => $id])->row_array();

        $data['title'] = 'Access Menu Management';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('admin/accessMenu', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadMenu($role_id)
    {
        $dataMenu = array();
        $no = 1;

        $queryMenu = "SELECT distinct a.*, IF(b.menu_id > 0, 1, 0)cek from user_menu a left join (select * from user_access_menu where role_id = $role_id)b on a.id = b.menu_id order by a.id";

        $menu = $this->db->query($queryMenu)->result();

        foreach ($menu as $m) {
            $menuId = $m->id;

            /** query sub menu */

            $querySubMenu = "SELECT * FROM (
                SELECT DISTINCT a.id AS parenxt,a.id,a.sub_menu_id,a.menu_id,
                (CASE WHEN a.sub_menu_id!=0 THEN CONCAT('&nbsp;&nbsp;&nbsp;',a.title) ELSE a.title END)title,
                a.url,a.icon,a.is_active,IF(b.menu_id>0,1,0)chk  
                FROM user_sub_menu a LEFT JOIN 
                (SELECT * FROM user_access_menu WHERE role_id=$role_id) b ON a.id=b.sub_menu_id
                WHERE a.menu_id=$menuId AND a.sub_menu_id=0
                UNION ALL
                SELECT DISTINCT a.sub_menu_id AS parentx,a.id,a.sub_menu_id,a.menu_id,
                (CASE WHEN a.sub_menu_id!=0 THEN CONCAT('&nbsp;&nbsp;&nbsp;',a.title) ELSE a.title END)title,
                a.url,a.icon,a.is_active,IF(b.menu_id>0,1,0)chk  
                FROM user_sub_menu a LEFT JOIN (SELECT * FROM user_access_menu WHERE role_id=$role_id) b ON a.id=b.sub_menu_id
                WHERE a.menu_id=$menuId AND a.sub_menu_id!=0
                )a
                ORDER BY menu_id,parenxt,id";

            $dataSubMenu = array();
            $subMenu = $this->db->query($querySubMenu)->result();

            foreach ($subMenu as $sm) {
                $dataSubMenu[] = array(
                    'idSubMenu' => $sm->id,
                    'idParent' => $sm->sub_menu_id,
                    'title' => $sm->title,
                    'cek' => $sm->chk,
                    'idMenu' => $menuId
                );
            }

            /** end sub menu */

            $nor = $no;
            $dataMenu[] = array(
                'urut' => $nor,
                'idMenu' => $m->id,
                'menu' => $m->menu,
                'cek' => $m->cek,
                'subMenu' => $dataSubMenu
            );
            $no++;
        }
        echo json_encode(array('data' => $dataMenu), JSON_PRETTY_PRINT);
    }

    public function saveAccess()
    {
        $role_id = $this->input->post('role');
        $menu_id = $this->input->post('menuId');
        $sub_menu_id = $this->input->post('subMenuId');

        $data = array(
            'role_id' => $role_id,
            'menu_id' => $menu_id,
            'sub_menu_id' => $sub_menu_id
        );

        $cek = $this->db->get_where('user_access_menu', $data);

        if ($cek->num_rows == 0) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }

        echo json_encode(array('status' => TRUE, 'message' => 'Akses diubah !'));
    }


    /**
     * User Management
     */

    public function userManagement()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();
        $data['title'] = 'User Management';
        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        // data role untuk pilihan
        $data['userRole'] = $this->db->get('user_role')->result();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('admin/userManagement', $data);
        $this->load->view('template/footer', $data);
    }

    public function loadDataUser()
    {
        $user = $this->admin->loadDataUser();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->admin->count_all_user(),
            "recordsFiltered" => $this->admin->count_filtered_user(),
            "data" => $user,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addUser()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);

        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'Email sudah terdaftar !',
            'required' => 'Email tidak boleh kosong !'
        ]);
        // $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[6]|matches[password2]', [
            'matches' => 'Password tidak sama !',
            'min_length' => 'Password terlalu pendek',
            'required' => 'Password tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim');

        $table = 'user';

        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'nama_error' => form_error('nama'),
                'email_error' => form_error('email'),
                'password_error' => form_error('password1'),
            );
        } else {
            $data = array(
                'name' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'password' =>  password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'image' => 'user.png',
                'role_id' => $this->input->post('role'),
                'is_active' => $this->input->post('is_active'),
                'date_created' => date('Y-m-d H:i:s')
            );

            $save = $this->crud->save($table, $data);
            $array = array("status" => TRUE, "message" => 'Data user berhasil ditambah !');
        }

        echo json_encode($array);
    }

    public function updateUser()
    {
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required', [
            'required' => 'Nama tidak boleh kosong !'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Email tidak boleh kosong !'
        ]);
        // $this->form_validation->set_rules('username', 'Username', 'required|trim');


        $table = 'user';

        if ($this->form_validation->run() == FALSE) {
            $array = array(
                'error' => true,
                'nama_error' => form_error('nama'),
                'password_error' => form_error('password1'),
            );
        } else {
            $data = array(
                'name' => htmlspecialchars($this->input->post('nama', true)),
                'email' => htmlspecialchars($this->input->post('email', true)),
                'password' =>  password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => $this->input->post('role'),
                'is_active' => $this->input->post('is_active'),
                'date_modified' => date('Y-m-d H:i:s')

            );


            $id = $this->input->post('id');

            $update = $this->crud->update(array('id' => $id), $data, $table);

            $array = array("status" => TRUE, "message" => 'Data user berhasil diupdate !');
        }

        echo json_encode($array);
    }

    public function getUser($id)
    {
        $table = 'user';

        $data = $this->crud->getData($table, $id);

        echo json_encode($data);
    }

    public function deleteUser()
    {
        $table = 'user';

        $id = $this->input->post('id');

        //get image user
        $user = $this->db->get_where('user', ['id' => $id])->row_array();

        // cek old image
        $old_image = $user['image'];

        if ($old_image != 'user.png') {
            unlink(FCPATH . 'assets/img/' . $old_image);
        }

        $this->crud->delete($table, $id);

        echo json_encode(array("status" => TRUE, "message" => 'User telah dihapus !'));
    }
}

/* End of file Admin.php */
