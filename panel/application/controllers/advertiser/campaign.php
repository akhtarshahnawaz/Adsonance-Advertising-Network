<?php

class Campaign extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }


    public function index(){
        if($this->session->userdata('loggedIn')){
            $this->load->model('advertiser/mindex');
            $data['campaigns']=$this->mindex->index();
            $data['cpmGraphData']= file_get_contents(site_url('advertiser/index/getUserCpmGraphData/'.$this->session->userdata('key')));
            $data['clicksGraphData']= file_get_contents(site_url('advertiser/index/getUserClicksGraphData/'.$this->session->userdata('key')));
            $data['ctrGraphData']=file_get_contents(site_url('advertiser/index/getUserCtrGraphData/'.$this->session->userdata('key')));
            $this->config->load('admin_settings');
            $data['spendConfig']=array(
                'pointPerImpression'=>$this->config->item('pointPerImpression'),
                'pointPerClick'=>$this->config->item('pointPerClick'),
                'USD'=>$this->config->item('usdMultiplier'),
                'INR'=>$this->config->item('inrMultiplier')
            );
            if($this->session->flashdata('notification')){
                $data['alertType']=$this->session->flashdata('alertType');
                $data['notification']=$this->session->flashdata('notification');
            }
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/structs/header');
            $this->load->view('advertiser/campaign/index',$data);
            $this->load->view('advertiser/structs/footer');
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function create(){
        if($this->session->userdata('loggedIn')){
            $post=$this->input->post();
            if($post){
                $this->load->model("advertiser/mcampaign");
                $this->mcampaign->create($post);
                redirect('advertiser/campaign/index');

            }else{
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/campaign/create');
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }

    public function remove($pkey=null){
        if($this->session->userdata('loggedIn')){
            if(isset($pkey)){
                $this->load->model('advertiser/mcampaign');
                $this->mcampaign->remove($pkey);
                redirect('advertiser/campaign/index','refresh');
            }else{
               //wrong page
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }

    public function edit($pkey=null){
        if($this->session->userdata('loggedIn')){
            $post=$this->input->post();
            if($post){
                $this->load->model("advertiser/mcampaign");
                $this->mcampaign->edit($post);
                redirect('advertiser/campaign/index','refresh');

            }elseif(isset($pkey)){
                $this->load->model("advertiser/mcampaign");
                $data['single']=$this->mcampaign->campaignDetails($pkey);
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/campaign/edit',$data);
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function single($campKey=null){
        if($this->session->userdata('loggedIn')){
            $this->load->model('advertiser/mcampaign');
            $data['ads']=$this->mcampaign->single($campKey);
            $data['keyCamp']=$campKey;
            $data['cpmGraphData']=$this->getCampaignCpmGraphData($campKey);
            $data['clicksGraphData']=$this->getCampaignClicksGraphData($campKey);
            $data['ctrGraphData']=$this->getCampaignCtrGraphData($campKey);
            $this->config->load('admin_settings');
            $data['spendConfig']=array(
                'pointPerImpression'=>$this->config->item('pointPerImpression'),
                'pointPerClick'=>$this->config->item('pointPerClick'),
                'USD'=>$this->config->item('usdMultiplier'),
                'INR'=>$this->config->item('inrMultiplier')
            );
            if($this->session->flashdata('notification')){
                $data['alertType']=$this->session->flashdata('alertType');
                $data['notification']=$this->session->flashdata('notification');
            }
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/structs/header');
            $this->load->view('advertiser/campaign/single',$data);
            $this->load->view('advertiser/structs/footer');
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function status($pkey,$status){
        if($this->session->userdata('loggedIn')){
            $this->load->model('advertiser/mcampaign');
            if($this->mcampaign->changeStatus($pkey,$status)){
                redirect('advertiser/campaign/index');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    private function getCampaignCpmGraphData($campKey){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getCampStats($campKey);

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
        return $graphData;
    }


    private function getCampaignClicksGraphData($campKey){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getCampStats($campKey);

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
        return $graphData;
    }


    private function getCampaignCtrGraphData($campKey){
        $this->load->model('advertiser/mstats');
        $result=$this->mstats->getCampStats($campKey);

        $innerData=array();
        foreach($result as $key=>$value){
            $innerData[$key]='[';
            foreach($value as $row){
                if($row['cpm']==null || $row['cpm']== 0){
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
        return $graphData;
    }





}
