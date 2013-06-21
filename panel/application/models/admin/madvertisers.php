<?php

class Madvertisers extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function getAdvertisers($advKey=null){
        if(isset($advKey)){
            $this->db->where('advKeyInfo',$advKey);
            $query=$this->db->get('advInfo');
            return $query->result_array();
        }else{
            $this->db->select('*');
            $this->db->from('advLogin');
            $this->db->join('advInfo', 'advInfo.advKeyInfo = advInfo.pkey','left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }



    public function getCampaigns($advKey=null){
        if(isset($advKey)){
            $this->db->where('advKeyCamp',$advKey);
            $query=$this->db->get('advCampaign');
            return $query->result_array();
        }else{
            return null;
        }
    }


    public function getAds($campKey=null){
        if(isset($advKey)){
            $this->db->where('campKeyAd',$advKey);
            $query=$this->db->get('advAd');
            return $query->result_array();
        }else{
            return null;
        }
    }


}
