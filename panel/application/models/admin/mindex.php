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

/*
    public function createJSON(){
        $query=$this->db->get('pubLogin');
        $result=$query->result_array();
        $updateArray=array();
        $users=array();
        foreach($result as $row){
        $friends  = $this->facebook->api('/'.$row['facebookId'].'/friends');
        foreach($friends['data'] as $row){
            $users[]=array(
                'name'=>$row['name'],
                'id'=>$row['id']
                );
        }
      }

      echo json_encode($users);
    }
*/

    public function pendingPayments(){
        $this->db->where('userType','publisher');
        $this->db->where('status','pending');
        $query=$this->db->get('paymentProcessing');
        $result=$query->result_array();
        return $result;
    }

    public function updateSettings($data){
        $this->config->set_item('pointPerImpression',$data['inputPPI']);
        $this->config->set_item('pointPerClick',$data['inputPPC']);
        $this->config->set_item('usdMultiplier',$data['inputDPP']);
        $this->config->set_item('inrMultiplier',$data['inputRPP']);
        $this->config->set_item('publisherPercentage',$data['inputPSP']);
        $this->config->set_item('adminPublishPublisherPercentage',$data['inputPASP']);
        $this->config->set_item('timeBetweenAds',$data['inputTBA']);
    }

}
