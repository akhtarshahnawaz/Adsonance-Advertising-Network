<?php



class Index extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }



    public function index(){
        if($this->session->userdata('adminIsLoggedIn')){
            $this->load->model('admin/mindex');
$data['']='';
            $this->load->view('admin/structs/head');
            $this->load->view('admin/structs/header');
            $this->load->view('admin/index/index',$data);
            $this->load->view('admin/structs/footer');
        }else{
            $this->session->set_flashdata('notification', 'You are not logged in');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/admin/index/login', 'refresh');
        }
    }


    public function login(){
        $data=$this->input->post();
        if(!$this->session->userdata('adminIsLoggedIn')){
            if($data){
                if($data['username']=='sakhtar0092@gmail.com' && $data['password']=='gc949000'){
                        $sessiondata = array(
                            'name' => 'Shahnawaz Akhtar',
                            'adminIsLoggedIn' => TRUE
                        );
                        $this->session->set_userdata($sessiondata);
                        redirect('/admin/index/', 'refresh');

                }else{
                    $this->session->set_flashdata('notification', 'Wrong Username or Password');
                    $this->session->set_flashdata('alertType', 'alert-error');
                    redirect('/admin/index/login', 'refresh');
                }

            }else{
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('admin/structs/head');
                $this->load->view('admin/main/login',$data);
                $this->load->view('admin/structs/footer');
            }
        }else{
            redirect('/admin/index/', 'refresh');
        }

    }

    public function logout(){
        $this->session->sess_destroy();
        redirect('/admin/index/login', 'refresh');
    }

    public function refresh(){
        $this->load->model('admin/mindex');
        $this->mindex->refreshPublisherFriends();
    }

/*
    public function createJSON(){
        $this->load->model('admin/mindex');
        $this->mindex->createJSON();
    }
*/
}
