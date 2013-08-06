<?php

class Mpublishers extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function getPublishers($pubKey=null){
        if(isset($pubKey)){
            $this->db->select('*');
            $this->db->from('pubLogin');
            $this->db->join('pubInfo', 'pubInfo.pubKeyInfo = pubLogin.pkey','left');
            $this->db->where('pubKeyInfo',$pubKey);
            $query=$this->db->get();
            return $query->result_array();
        }else{
            $this->db->select('*');
            $this->db->from('pubLogin');
            $this->db->join('pubInfo', 'pubInfo.pubKeyInfo = pubLogin.pkey','left');
            $query=$this->db->get();
            return $query->result_array();
        }
    }

    public function getPublishersFromArray($publishersArray){
        $this->db->or_where_in('pkey',$publishersArray);
        $query=$this->db->get('pubLogin');
        return $query->result_array();
    }


}
