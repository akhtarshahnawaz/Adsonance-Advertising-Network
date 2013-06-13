<?php

class Advertisers extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('adminIsLoggedIn')){

            $this->load->model('admin/madvertisers');
            $data['advertisers']=$this->madvertisers->getAdvertisers();
            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/advertisers/index',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }

    public function view($advKey){
        if($this->session->userdata('adminIsLoggedIn')){

            $this->load->model('admin/madvertisers');
            $data['campaigns']=$this->madvertisers->getCampaigns($advKey);

            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/advertisers/advertiserCampaigns',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }


    public function ads($campKey){

        if($this->session->userdata('adminIsLoggedIn')){

            $this->load->model('advertiser/mcampaign');
            $data['ads']=$this->mcampaign->single($campKey);
            $data['keyCamp']=$campKey;
            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/advertisers/ads',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }



    public function sendNotification($advKey){
        if($this->session->userdata('adminIsLoggedIn')){
            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/advertisers/sendNotification');
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }

}
