<?php

class Ad extends  CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function create($campKey=null){
        if($this->session->userdata('loggedIn')){
            $data=$this->input->post();
            if(isset($data['campaign_key']) && isset($data['campaign_choose'])){
                //campaign choosed- create ad
                $ViewData['campaignKey']=$data['campaign_key'];
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/ad/create',$ViewData);
                $this->load->view('advertiser/structs/footer');
            }elseif(isset($campKey)){
                //campaign choosed- through GET method
                $ViewData['campaignKey']=$campKey;
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/ad/create',$ViewData);
                $this->load->view('advertiser/structs/footer');

            }elseif($data && !isset($data['campaign_key']) && !isset($data['campaign_choose'])){
                //ad filled-create ad
                $this->load->model('advertiser/mad');
                $this->mad->create($data);
                redirect('advertiser/campaign/single'.'/'.$data['campaignKey']);

            }else{
                //choose campaign
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function edit($campKey=null,$adKey=null){
        if($this->session->userdata('loggedIn')){
            $data=$this->input->post();
            if($data){
                $this->load->model('advertiser/mad');
                $this->mad->edit($data);
                redirect('advertiser/campaign/single'.'/'.$data['campKey']);

            }else{
                $this->load->model('advertiser/mad');
                $adData=$this->mad->getAd($adKey);
                $data['ad']=$adData[0];
                $data['campKey']=$campKey;
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/ad/edit',$data);
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }



    public function remove($campKey=null,$adKey=null){
        if($this->session->userdata('loggedIn')){
                $this->load->model('advertiser/mad');
                $this->mad->remove($adKey);
                redirect('advertiser/campaign/single'.'/'.$campKey);
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function uploadImage(){

        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']	= '10000';
        $config['max_width']  = '10240';
        $config['max_height']  = '7680';

        $this->load->library('upload', $config);

        if ( ! $this->upload->do_upload('inputImage'))
        {
            $error = array('error' => $this->upload->display_errors());
            return var_dump($error);
        }
        else
        {
            $data = $this->upload->data();
            $file= base_url().'uploads/'.$data['file_name'];
            echo $file;
        }
    }

    public function removeImage(){

        $config['upload_path'] = './uploads/';
        $oldImage=$this->input->post('imageName');

        $this->load->helper('file');
        delete_files(base_url().'uploads/'.$oldImage);
    }


    public function status($pkeyCamp,$pkeyAd,$status){
        if($this->session->userdata('loggedIn')){
            $this->load->model('advertiser/mad');
            if($this->mad->changeStatus($pkeyAd,$status)){
                redirect('advertiser/campaign/single/'.$pkeyCamp);
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }

}
