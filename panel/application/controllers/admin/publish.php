<?php

class Publish extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('adminIsLoggedIn')){
            $this->load->model('admin/mpublish');
            $data['adstopublish']=$this->mpublish->getAds();

            if($this->session->flashdata('notification')){
                $data['alertType']=$this->session->flashdata('alertType');
                $data['notification']=$this->session->flashdata('notification');
            }

            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/publish/index',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }

    public function publishad($adID,$points){
        if($this->session->userdata('adminIsLoggedIn')){
            if($points>0){
                $this->load->model('admin/mpublish');
                $publishers=$this->mpublish->getPublishers();
                $adData=$this->mpublish->getAdData($adID);
                $sharedto=0;
                $error='';
                foreach($publishers as $row){
                    if($row['totalfriends']<$points){
                        $parameters = array(
                            'message' => 'Posted Via adsonance.com',
                            'picture' => base_url('').$this->config->item('ImageUploadPath').$adData['image'],
                            'link' => site_url('publisher/post/clicked').'/'.$adData['pkey'].'/'.$row['pkey'],
                            'name' => $adData['title'],
                            'caption' => 'Via Adsonance',
                            'description' => $adData['description'],
                            'access_token' => $this->facebook->getAccessToken()
                        );
                        try{
                        $result=$this->facebook->api(
                            '/'.$row['facebookId'].'/feed',
                            'POST',
                            $parameters
                        );
                        }catch(FacebookApiException $e) {
                            $error.=$row['pkey'].'</br>';
                        }
                        if($result){
                            $this->mpublish->addStats($adData,$result['id'],$row);
                            $points-=$row['totalfriends'];
                            $sharedto+=$row['totalfriends'];
                        }
                    }
                }
                $this->session->set_flashdata('notification', 'Shared to '.$sharedto.' People </br>'.$error);
                $this->session->set_flashdata('alertType', 'alert-success');
                redirect('/admin/publish/index', 'refresh');
            }else{
                $this->session->set_flashdata('notification', 'This Advertiser has quite low balance');
                $this->session->set_flashdata('alertType', 'alert-error');
                redirect('/admin/publish/index', 'refresh');
            }

        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }






}
