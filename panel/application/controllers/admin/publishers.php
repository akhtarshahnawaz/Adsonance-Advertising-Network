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


    public function notify(){
        if($this->session->userdata('adminIsLoggedIn')){
            $data=$this->input->post();
            if(!empty($data)){
                $notifyTo=$data['publisherKey'];
                $notifyMsg=$data['inputNotification'];

                $this->load->model('admin/mpublishers');
                $Publishers=$this->mpublishers->getPublishersFromArray($notifyTo);

                foreach($Publishers as $row){
                        $parameters=array(
                            'access_token' => $this->facebook->getAccessToken(),
                            'href' => 'http://apps.facebook.com/adsonance',
                            'template' => 'Hi @['.$row['facebookId'].'] '.$notifyMsg
                        );
                    try{
                        $post=$this->facebook->api(
                        '/'.$row['facebookId'].'/notifications',
                        'POST',
                        $parameters
                        );

                    }catch(FacebookApiException $e){
                    }
                }
                    $this->session->set_flashdata('notification', 'Successfully Notified');
                    $this->session->set_flashdata('alertType', 'alert-success');
                    redirect('/admin/publishers/index','refresh');
            }
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }
}
