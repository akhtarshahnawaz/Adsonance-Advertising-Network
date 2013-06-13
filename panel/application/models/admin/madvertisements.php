<?php

class Madvertisements extends CI_Model
{
    function __construct(){
        parent::__construct();
    }


    public function getUnverifiedAds(){
        $this->db->where('status','0');
        $query=$this->db->get('advAd');
        return $query->result_array();
    }

    public function verifyAd($adId){
        $parameters=array(
            'status'=>'2'
        );
        $this->db->where('pkey',$adId);
        $result=$this->db->update('advAd',$parameters);
        if($result){
            $this->session->set_flashdata('notification', 'Advertisement Verified Successfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }else{
            $this->session->set_flashdata('notification', 'Problem while verification');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
    }

    public function unverifyAd($data){
        $parameters=array(
            'status'=>$data['inputMessage']
        );
        $this->db->where('pkey',$data['adId']);
        $result=$this->db->update('advAd',$parameters);
        if($result){
            $this->session->set_flashdata('notification', 'Advertisement blockage Successfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }else{
            $this->session->set_flashdata('notification', 'Problem while blocking advertisement');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
    }
}
