<?php

class Post extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }


    public function postad($adKey){
        if($this->session->userdata('PubloggedIn')){
            $logged=true;
        }else{
            $this->load->model('publisher/mindex');
            $logged=$this->mindex->login();
        }

        if($logged){
            $this->load->model('publisher/mpost');
            $ad=$this->mpost->getAdsData($adKey);
            if($ad){
                $parameters = array(
                    'message' => 'Posted Via adsonance.com',
                    'picture' => base_url('').$this->config->item('ImageUploadPath').$ad['image'],
                    'link' => site_url('publisher/post/clicked').'/'.$ad['pkey'].'/'.$this->session->userdata('user_ID'),
                    'name' => $ad['title'],
                    'caption' => 'Via Adsonance',
                    'description' => $ad['description'],
                    'access_token' => $this->facebook->getAccessToken()
                );
                    $result=$this->facebook->api(
                        '/me/feed',
                        'POST',
                        $parameters
                    );

                $this->mpost->addStats($ad,$result['id']);
            }
            print('<script> top.location.href=\'' . $this->config->item('Facebook-App-Url') . '\'</script>');
        }

    }

    public function clicked($adId=null,$pubId=null){
        if($adId){
            $this->load->model('publisher/mpost');
            $ad=$this->mpost->getAdsData($adId);
            $pub=$this->mpost->checkPublisher($pubId);
            if($ad && $pub){
                $this->mpost->addCickStats($ad,$pubId);
                redirect($ad['link'],'refresh');
            }elseif($ad){
                $this->mpost->addCickStats($ad);
                redirect($ad['link'],'refresh');
            }else{
                redirect('http://www.adsonance.com','refresh');
            }
        }else{
               redirect('http://www.adsonance.com','refresh');
        }
    }

}
