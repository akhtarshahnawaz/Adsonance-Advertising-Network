<?php

class Billing extends CI_Controller
{

    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('PubloggedIn')){
            $logged=true;
        }else{
            $this->load->model('publisher/mindex');
            $logged=$this->mindex->login();
        }

        if($logged){
            $this->load->model('publisher/mbilling');
            $data['earningGraphData']=$this->mbilling->combinedEarningGraphData(30);
            $data['totalEarning']=$this->mbilling->totalEarning();
            $data['transactions']=$this->mbilling->transactions();
            $data['pendingPayments']=$this->mbilling->pendingPayments();

            $this->load->view('publisher/structs/head');
            $this->load->view('publisher/structs/header');
            $this->load->view('publisher/billing/index',$data);
            $this->load->view('publisher/structs/footer');
        }
    }



    public function withdraw(){
        if($this->session->userdata('PubloggedIn')){
            $logged=true;
        }else{
            $this->load->model('publisher/mindex');
            $logged=$this->mindex->login();
        }

        if($logged){
            $data=$this->input->post();
            if($data){
                switch($data['withdraw_method']){
                    case 'mobile_recharge':
                        $this->load->view('publisher/structs/head');
                        $this->load->view('publisher/structs/header');
                        $this->load->view('publisher/billing/mobile_recharge');
                        $this->load->view('publisher/structs/footer');
                        break;
                    default:
                        echo 'fuckit';
                }

            }else{
                $this->load->view('publisher/structs/head');
                $this->load->view('publisher/structs/header');
                $this->load->view('publisher/billing/selectWithdrawMethod');
                $this->load->view('publisher/structs/footer');
            }
        }
    }


    public function mobileRecharge(){
        if($this->session->userdata('PubloggedIn')){
            $logged=true;
        }else{
            $this->load->model('publisher/mindex');
            $logged=$this->mindex->login();
        }
        if($logged){

            $data=$this->input->post();
            if($data){
                $this->load->model('publisher/mbilling');
                $totalEarning=$this->mbilling->totalEarning();
                if($data['inputAmount']>$totalEarning){
                    var_dump('from inside');
                    $data['error']="Withdrawal amount can't be more than your total earning.";
                    $this->load->view('publisher/structs/head');
                    $this->load->view('publisher/structs/header');
                    $this->load->view('publisher/billing/mobile_recharge',$data);
                    $this->load->view('publisher/structs/footer');
                }elseif($data['inputAmount']<'5'){
                    $data['error']='Minimum withdrawal amount is $5 USD';
                    $this->load->view('publisher/structs/head');
                    $this->load->view('publisher/structs/header');
                    $this->load->view('publisher/billing/mobile_recharge',$data);
                    $this->load->view('publisher/structs/footer');
                }else{
                    $result=$this->mbilling->mobileRechargeWithdrawal($data);
                    if($result){
                        $data['successMessage']='Withdrawal request received. Your payment will be processed within 3 days.';
                        $this->load->view('publisher/structs/head');
                        $this->load->view('publisher/structs/header');
                        $this->load->view('publisher/billing/mobile_recharge',$data);
                        $this->load->view('publisher/structs/footer');
                    }else{
                        $data['error']='Problem while submitting withdrawal request. Try again Later.';
                        $this->load->view('publisher/structs/head');
                        $this->load->view('publisher/structs/header');
                        $this->load->view('publisher/billing/mobile_recharge',$data);
                        $this->load->view('publisher/structs/footer');
                    }
                }


            }else{
                $this->load->view('publisher/structs/head');
                $this->load->view('publisher/structs/header');
                $this->load->view('publisher/billing/mobile_recharge');
                $this->load->view('publisher/structs/footer');
            }
        }
    }
}
