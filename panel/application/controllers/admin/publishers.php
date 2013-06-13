<?php


class Publishers extends CI_Controller
{

    function __construct(){
        parent::__construct();
    }


    public function index(){
        if($this->session->userdata('adminIsLoggedIn')){

            $this->load->model('admin/mpublishers');
            $data['publishers']=$this->mpublishers->getPublishers();
            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/publishers/index',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }

}
