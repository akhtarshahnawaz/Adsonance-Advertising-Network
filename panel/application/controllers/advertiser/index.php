<?Php

class Index extends CI_Controller{

    function __construct(){
        parent::__construct();
    }


    public function index(){
        if($this->session->userdata('loggedIn')){
            $this->load->model('advertiser/mindex');
            $data['campaigns']=$this->mindex->index();
            $this->load->model('advertiser/mspend');
            $data['dailySpend']=$this->mspend->dailySpend(5);
            $data['balances']=$this->mspend->balances($this->session->userdata('key'));
            $data['cpmGraphData']= file_get_contents(site_url('advertiser/index/getUserCpmGraphData/'.$this->session->userdata('key')));
            $data['clicksGraphData']= file_get_contents(site_url('advertiser/index/getUserClicksGraphData/'.$this->session->userdata('key')));
            $data['spendGraphData']=file_get_contents(site_url('advertiser/index/getUserSpendGraphData/'.$this->session->userdata('key')));
            $data['ctrGraphData']=file_get_contents(site_url('advertiser/index/getUserCtrGraphData/'.$this->session->userdata('key')));
            $this->config->load('admin_settings');
            $data['spendConfig']=array(
                'pointPerImpression'=>$this->config->item('pointPerImpression'),
                'pointPerClick'=>$this->config->item('pointPerClick'),
                'USD'=>$this->config->item('usdMultiplier'),
                'INR'=>$this->config->item('inrMultiplier')
            );
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/structs/header');
            $this->load->view('advertiser/index/index',$data);
            $this->load->view('advertiser/structs/footer');
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }




    public function signup(){
        $data=$this->input->post();
        if(!$this->session->userdata('loggedIn')){
            if($data){
                $this->load->model('advertiser/mindex');
                $result=$this->mindex->signup($data);
                if($result['status']=='dataInserted'){
                    //sending email to verify
                    $sendMail=$this->mindex->sendSignupMail($result['data']);
                    if($sendMail){
                        $data['status']='text-success';
                        $data['message']='Verification link has been send to your E-mail address.Click on link to verify your account.';
                        $this->load->view('advertiser/structs/head');
                        $this->load->view('advertiser/main/messages',$data);
                        $this->load->view('advertiser/structs/footer');
                    }else{
                        $data['status']='text-error';
                        $data['message']='Problem while creating your account. Try again Later.';
                        $this->load->view('advertiser/structs/head');
                        $this->load->view('advertiser/main/messages',$data);
                        $this->load->view('advertiser/structs/footer');
                    }
                }elseif($result['status']=='dataInsertionProblem'){
                    $data['status']='text-error';
                    $data['message']='Problem while creating your account. Try again Later.';
                    $this->load->view('advertiser/structs/head');
                    $this->load->view('advertiser/main/messages',$data);
                    $this->load->view('advertiser/structs/footer');
                }elseif($result['status']=='inputError'){
                    $data['data']=$result['data'];
                    $data['errors']=$result['errors'];
                    $this->load->view('advertiser/structs/head');
                    $this->load->view('advertiser/main/signup',$data);
                    $this->load->view('advertiser/structs/footer');
                }
            }else{
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/main/signup');
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            redirect('/advertiser/index/', 'refresh');
        }
    }



    public function verifyUser($verificationCode){
        $this->load->model('advertiser/mindex');
        $result=$this->mindex->verifyUser($verificationCode);
        if(!$result){
           header("HTTP/1.0 404 Not Found");
        }
    }



    public function login(){
        $data=$this->input->post();
        if(!$this->session->userdata('loggedIn')){
            if($data){
                if(($data['username']!=null || $data['username']!='') && ($data['password']!=null || $data['password']!='')){
                    $this->load->model('advertiser/mindex');
                    $result=$this->mindex->login($data);
                    if($result){
                        $sessiondata = array(
                            'key' => $result[0]['pkey'],
                            'username' => $result[0]['username'],
                            'type' => 'advertiser',
                            'currency'=>$result[0]['currency'],
                            'status'=>$result[0]['status'],
                            'lastLogin'=>$result[0]['lastLogin'],
                            'loggedIn' => TRUE
                        );
                        $this->session->set_userdata($sessiondata);
                        redirect('/advertiser/index/', 'refresh');
                    }else{
                        $this->session->set_flashdata('notification', '<strong>Sorry!</strong> Wrong Username-Password Combination');
                        $this->session->set_flashdata('alertType', 'alert-error');
                        redirect('/advertiser/index/login', 'refresh');
                    }
                }else{
                    $this->session->set_flashdata('notification', '<strong>Please enter username and password</strong>');
                    $this->session->set_flashdata('alertType', 'alert-warning');
                    redirect('/advertiser/index/login', 'refresh');
                }

            }else{
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/main/login',$data);
            }
        }else{
                redirect('/advertiser/index/', 'refresh');
        }

    }



    public function logout(){
        $this->session->sess_destroy();
        redirect('/advertiser/index/login', 'refresh');
    }


    /*
     * Generates Graph data for various Campaigns Impressions
     * */

    public function getUserCpmGraphData($userID){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getUserStats($userID);
        $innerData=array();
        foreach($result as $key=>$value){
            $innerData[$key]='[';
            foreach($value as $row){
                $innerData[$key].='[ gd('.str_replace('/',',',$row['date']).'),'.$row['cpm'].'],';
            }
            $innerData[$key]=substr_replace($innerData[$key] ,"",-1);
            $innerData[$key].=']';
            $innerData[$key]='{ label: "'.$value[0]['name'].'" , data: '.$innerData[$key].'}';
        }


        $graphData='[';
        foreach($innerData as $row){
          $graphData.=$row.',';
        }
        $graphData=substr_replace($graphData ,"",-1);
        $graphData.=']';
        echo $graphData;
    }

    /*
     * Generates Graph data for various Campaigns Clicks
     * */

    public function getUserClicksGraphData($userID){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getUserStats($userID);

        $innerData=array();
        foreach($result as $key=>$value){
            $innerData[$key]='[';
            foreach($value as $row){
                $innerData[$key].='[ gd('.str_replace('/',',',$row['date']).'),'.$row['clicks'].'],';
            }
            $innerData[$key]=substr_replace($innerData[$key] ,"",-1);
            $innerData[$key].=']';
            $innerData[$key]='{ label: "'.$value[0]['name'].'" , data: '.$innerData[$key].'}';
        }


        $graphData='[';
        foreach($innerData as $row){
            $graphData.=$row.',';
        }
        $graphData=substr_replace($graphData ,"",-1);
        $graphData.=']';
        echo $graphData;
    }


    /*
     * Generates Graph data for various Campaigns Click Through rate
     * */

    public function getUserCtrGraphData($userID){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getUserStats($userID);

        $innerData=array();
        foreach($result as $key=>$value){
            $innerData[$key]='[';
            foreach($value as $row){
                if($row['cpm']==null || $row['cpm']==0){
                    $ctr=$row['clicks'];
                }else{
                    $ctr=$row['clicks']/$row['cpm'];
                }
                $innerData[$key].='[ gd('.str_replace('/',',',$row['date']).'),'.$ctr.'],';
            }
            $innerData[$key]=substr_replace($innerData[$key] ,"",-1);
            $innerData[$key].=']';
            $innerData[$key]='{ label: "'.$value[0]['name'].'" , data: '.$innerData[$key].'}';
        }


        $graphData='[';
        foreach($innerData as $row){
            $graphData.=$row.',';
        }
        $graphData=substr_replace($graphData ,"",-1);
        $graphData.=']';
        echo $graphData;
    }


    /*
     * Generates Graph Data for Amount user Spend on each ad Daily
     * */
    public function getUserSpendGraphData($userID){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getUserStats($userID);

        $innerData=array();
        foreach($result as $key=>$value){
            $innerData[$key]='[';
            foreach($value as $row){
                $spend=$row['cpm']+$row['clicks'];
                $innerData[$key].='[ gd('.str_replace('/',',',$row['date']).'),'.$spend.'],';
            }
            $innerData[$key]=substr_replace($innerData[$key] ,"",-1);
            $innerData[$key].=']';
            $innerData[$key]='{ label: "'.$value[0]['name'].'" , data: '.$innerData[$key].'}';
        }


        $graphData='[';
        foreach($innerData as $row){
            $graphData.=$row.',';
        }
        $graphData=substr_replace($graphData ,"",-1);
        $graphData.=']';
        echo $graphData;
    }
}
