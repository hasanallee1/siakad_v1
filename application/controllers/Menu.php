<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_logged_in();
        $this->load->model('Crud_model', 'crud');
        $this->load->model('Menu_model', 'menu');
        $this->load->model('Submenu_model', 'subMenu');
    }


    public function index()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $data['title'] = 'Menu Management';

        $role_id = $data['user']['role_id'];


        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('menu/index', $data);
        $this->load->view('template/footer', $data);

        // echo "Selamat Datang " . $data['user']['name'];
    }

    public function loadData()
    {
        $menu = $this->menu->loadData();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->menu->count_all(),
            "recordsFiltered" => $this->menu->count_filtered(),
            "data" => $menu,
        );
        //output to json format
        echo json_encode($output);
    }


    public function addMenu()
    {
        $table = 'user_menu';
        $data = array(
            'menu' => $this->input->post('menu'),
        );

        $save = $this->crud->save($table, $data);
        echo json_encode(array("status" => TRUE, "message" => 'Data menu berhasil ditambah !'));
    }

    public function getMenu($id)
    {
        $table = 'user_menu';
        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function updateMenu()
    {
        $table = 'user_menu';
        $data = array(
            'menu' => $this->input->post('menu')
        );

        $id = $this->input->post('id');

        $this->crud->update(array('id' => $id), $data, $table);
        echo json_encode(array("status" => TRUE));
    }

    public function deleteMenu()
    {
        $table = 'user_menu';
        $id = $this->input->post('id');
        $delete = $this->crud->delete($table, $id);
        echo json_encode(array("status" => TRUE));
    }

    /**
     * Sub Menu Management
     */

    public function subMenu()
    {
        $email =  $this->session->userdata('email');

        $data['user'] = $this->db->get_where('user', ['email' => $email])->row_array();

        $data['menu'] = $this->db->get('user_menu')->result_array();
        $data['sub_menu'] = $this->db->get('user_sub_menu')->result_array();

        $role_id = $data['user']['role_id'];
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();


        $data['title'] = 'Sub Menu Management';

        $this->load->view('template/header', $data);
        $this->load->view('template/sidebar', $data);
        $this->load->view('menu/subMenu', $data);
        $this->load->view('template/footer', $data);

        // echo "Selamat Datang " . $data['user']['name'];
    }

    public function loadDataSubMenu()
    {
        $subMenu = $this->subMenu->loadDataSubMenu();
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->subMenu->count_all(),
            "recordsFiltered" => $this->subMenu->count_filtered(),
            "data" => $subMenu,
        );
        //output to json format
        echo json_encode($output);
    }

    public function addSubMenu()
    {
        $table = 'user_sub_menu';

        $data = array(
            'title' => $this->input->post('title'),
            'menu_id' => $this->input->post('menu'),
            'sub_menu_id' => $this->input->post('sub_menu'),
            'url' => $this->input->post('url1'),
            'icon' => $this->input->post('ikon'),
            'is_active' => $this->input->post('is_active'),
        );
        $save = $this->crud->save($table, $data);
        echo json_encode(array("status" => TRUE, "message" => 'Data berhasil ditambah !'));
    }

    public function getSubMenu($id)
    {
        $table = 'user_sub_menu';
        $data = $this->crud->getData($table, $id);
        echo json_encode($data);
    }

    public function deleteSubMenu()
    {
        $table = 'user_sub_menu';
        $id = $this->input->post('id');
        $delete = $this->crud->delete($table, $id);
        echo json_encode(array("status" => TRUE));
    }

    public function updateSubMenu()
    {
        $table = 'user_sub_menu';

        $sub_menu = $this->input->post('sub_menu');

        if ($sub_menu == '') {
            $sub_menu = 0;
        } else {
            $sub_menu = $sub_menu;
        }
        $data = array(
            'title' => $this->input->post('title'),
            'menu_id' => $this->input->post('menu'),
            'sub_menu_id' => $sub_menu,
            'url' => $this->input->post('url1'),
            'icon' => $this->input->post('ikon'),
            'is_active' => $this->input->post('is_active'),
        );

        $id = $this->input->post('id');

        $this->crud->update(array('id' => $id), $data, $table);
        echo json_encode(array("status" => TRUE, "message" => 'Data berhasil diedit !'));
    }
}

/* End of file Menu.php */
