<?php

class Settings extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('loggedIn')){
            $data=$this->input->post();
            if($data){
                $this->load->model('advertiser/msettings');
                if($this->msettings->updateGeneralSettings($data)){
                    $this->session->set_flashdata('notification', 'Edited Succesfully');
                    $this->session->set_flashdata('alertType', 'alert-success');
                    redirect('advertiser/settings/index','refresh');
                }else{
                    $this->session->set_flashdata('notification', 'Unable to edit! Try Again Later');
                    $this->session->set_flashdata('alertType', 'alert-error');
                    redirect('advertiser/settings/index','refresh');
                }

            }else{
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->model('advertiser/msettings');
                $data['settings']=$this->msettings->getGeneralSettings();
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/settings/sidebar');
                $this->load->view('advertiser/settings/index',$data);
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }



    public function password(){
        if($this->session->userdata('loggedIn')){

            $data=$this->input->post();
            if($data){
                $this->load->model('advertiser/msettings');
                $this->msettings->updatePassword($data);
                redirect('advertiser/settings/password','refresh');
            }else{
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/settings/sidebar');
                $this->load->view('advertiser/settings/password',$data);
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }

}
