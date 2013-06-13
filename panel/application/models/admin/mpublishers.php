<?php

class Mpublishers extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function getPublishers($pubKey=null){
        if(isset($pubKey)){
            $this->db->where('pubKeyInfo',$pubKey);
            $query=$this->db->get('pubInfo');
            return $query->result_array();
        }else{
            $query = $this->db->get('pubInfo');
            return $query->result_array();
        }
    }


}
