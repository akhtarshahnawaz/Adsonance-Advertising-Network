<?php

class Advertisements extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('adminIsLoggedIn')){
            $this->load->model('admin/madvertisements');
            $data['unverified_Ads']=$this->madvertisements->getUnverifiedAds();
            if($this->session->flashdata('notification')){
                $data['alertType']=$this->session->flashdata('alertType');
                $data['notification']=$this->session->flashdata('notification');
            }


            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/advertisements/index',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }


    public function verifyAd($adID){
        if($this->session->userdata('adminIsLoggedIn')){
            $this->load->model('admin/madvertisements');
            $this->madvertisements->verifyAd($adID);
            redirect('/admin/advertisements/index', 'refresh');


        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }


    public function unverifyAd($adID=null){
        if($this->session->userdata('adminIsLoggedIn')){
            $data=$this->input->post();
            if($data){
                $this->load->model('admin/madvertisements');
                $this->madvertisements->unverifyAd($data);
                redirect('/admin/advertisements/index', 'refresh');
            }else{
                $data['adId']=$adID;
                $this->load->view('admin/structs/head');
                $this->load->view('admin/structs/header');
                $this->load->view('admin/advertisements/unverifyAd',$data);
                $this->load->view('admin/structs/footer');
            }
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }


}
