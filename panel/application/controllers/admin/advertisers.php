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

    public function loginas($advKey){
        if($this->session->userdata('adminIsLoggedIn')){

            $this->load->model('admin/madvertisers');
            $advertiser=$this->madvertisers->getAdvertiser($advKey);

            if(!empty($advertiser)){
                $sessiondata = array(
                    'key' => $advertiser[0]['pkey'],
                    'username' => $advertiser[0]['username'],
                    'type' => 'advertiser',
                    'currency'=>$advertiser[0]['currency'],
                    'status'=>$advertiser[0]['status'],
                    'lastLogin'=>$advertiser[0]['lastLogin'],
                    'loggedIn' => TRUE
                );
                $this->session->set_userdata($sessiondata);
                redirect('/advertiser/index/', 'refresh');
            }
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }


    public function addfund($advKey=null,$currency=null){
        if($this->session->userdata('adminIsLoggedIn')){
            $data=$this->input->post();
            if($data){
                $this->load->model('admin/madvertisers');
                $advertiser=$this->madvertisers->addfund($data);
            }else{
                $data['advKey']=$advKey;
                $data['currency']=$currency;
                $this->load->view('admin/structs/head');
                $this->load->view('admin/structs/header');
                $this->load->view('admin/advertisers/addfund',$data);
                $this->load->view('admin/structs/footer');
            }

        }
    }



}
