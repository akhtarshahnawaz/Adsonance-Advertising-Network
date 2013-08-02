<?php

class Mindex extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function refreshPublisherFriends(){
        $query=$this->db->get('pubLogin');
        $result=$query->result_array();
        $updateArray=array();
        foreach($result as $row){
            $friends  = $this->facebook->api('/'.$row['facebookId'].'/friends');
            $totalfriends= count($friends['data']);
            $updateArray[]=array(
                'pkey'=>$row['pkey'],
                'totalfriends'=>$totalfriends
            );
        }
        $result=$this->db->update_batch('pubLogin',$updateArray,'pkey');
        if($result){
            echo 'Successfully refreshed friends';
        }else{
            echo 'friends refreshing problem';
        }
    }


}
