<?php

class Billing extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('loggedIn')){
            $data=$this->input->post();
            $this->load->model('advertiser/mspend');
            if($data && $data['daterange']){
                $data=explode(' - ',$data['daterange']);
                $this->load->model('advertiser/mbilling');
                $data['billingSummary']=$this->mbilling->billingSummary($data[0],$data[1]);
                $data['range']=$data[0].' - '.$data[1];
                $data['balances']=$this->mspend->balances($this->session->userdata('key'));
                $data['spendGraphData']=$this->mspend->dailySpendGraphData();
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/index',$data);
                $this->load->view('advertiser/structs/footer');
            }else{
                $to=dateToday();
                $from=explode('/',$to);
                $from=$from[0].'/01/'.$from[2];
                $this->load->model('advertiser/mbilling');
                $data['billingSummary']=$this->mbilling->billingSummary($from,$to);
                $data['range']=$from.' - '.$to;
                $data['balances']=$this->mspend->balances($this->session->userdata('key'));
                $data['spendGraphData']=$this->mspend->dailySpendGraphData();
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/index',$data);
                $this->load->view('advertiser/structs/footer');
            }        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function addfund(){
        if($this->session->userdata('loggedIn')){

            $data=$this->input->post();
            if($data){
                switch($data['pay_method']){
                    case 'paypal':
                        echo 'paypal';
                        break;

                    case 'wireTransfer':
                        redirect('advertiser/billing/wireTransfer');
                        break;

                    default:
                        echo 'fuckit';
                }


            }else{
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/selectPayMethod');
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }



    public function wireTransfer(){
        if($this->session->userdata('loggedIn')){
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/structs/header');
            $this->load->view('advertiser/billing/wireTransfer');
            $this->load->view('advertiser/structs/footer');
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


}
