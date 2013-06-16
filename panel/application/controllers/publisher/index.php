<?Php

class Index extends CI_Controller{

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
            $this->load->model('publisher/mindex');
            $data['adsList']=$this->mindex->getAds();;

            $this->load->model('publisher/mbilling');
            $data['totalEarning']=$this->mbilling->totalEarning();


            $this->load->view('publisher/structs/head');
            $this->load->view('publisher/structs/header');
            $this->load->view('publisher/index/index',$data);
            $this->load->view('publisher/structs/footer');
        }
    }









    public function sendrequest(){
        if($this->session->userdata('PubloggedIn')){
            $logged=true;
        }else{
            $this->load->model('publisher/mindex');
            $logged=$this->mindex->login();
        }

        if($logged){
            $message=$this->config->item('invitation_message');
            $requests_url = "https://www.facebook.com/dialog/apprequests?app_id="
                . $this->config->item('appId') . "&redirect_uri=" . urlencode($this->config->item('Facebook-App-Url'))
                . "&message=" . $message;
        }


        if (empty($_REQUEST["request_ids"])) {
            echo("<script> top.location.href='" . $requests_url . "'</script>");
        } else {
            echo "Request Ids: ";
        }
    }



    /*
        public function logout(){
            $this->session->sess_destroy();
        }
    */
}