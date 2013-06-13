<?php

class Mad extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    /*
     * Create an Advertisement
     * */

    public function create($data){
        $insertData=array(
            'campKeyAd'=>$data['campaignKey'],
            'name'=>$data['inputAdvertisementName'],
            'title'=>$data['inputTitle'],
            'link'=>$data['inputLink'],
            'description'=>$data['inputDescription'],
            'image'=>$data['advImage'],
            'status'=>'0'
        );

        $result=$this->db->insert('advAd',$insertData);

        if($result){
            $this->session->set_flashdata('notification', '<strong>Success!</strong> Advertisement Created Succesfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }else{
            $this->session->set_flashdata('notification', '<strong>Error!</strong> Unable to Create Advertisement.Try again Later');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
    }


    /*
     * Edit an Advertisement
     * */

    public function edit($data){
        if($this->adOwner($data['adKey'])==$this->session->userdata('key')){
            $insertData=array(
                'name'=>$data['inputAdvertisementName'],
                'title'=>$data['inputTitle'],
                'link'=>$data['inputLink'],
                'description'=>$data['inputDescription'],
                'image'=>$data['advImage'],
                'status'=>'0'
            );

            $this->db->where('pkey',$data['adKey']);
            $result= $this->db->update('advAd',$insertData);
            if($result){
                $this->session->set_flashdata('notification', '<strong>Success!</strong> Advertisement Edited Succesfully');
                $this->session->set_flashdata('alertType', 'alert-success');
            }else{
                $this->session->set_flashdata('notification', '<strong>Error!</strong> Unable to Edit Advertisement.Try again Later');
                $this->session->set_flashdata('alertType', 'alert-error');
            }
        }
    }


    /*
   * Remove an Advertisement
   * */

    public function remove($adKey){
        if($this->adOwner($adKey)==$this->session->userdata('key')){
            $this->db->where('pkey',$adKey);
            $result= $this->db->delete('advAd');
            if($result){
                $this->session->set_flashdata('notification', '<strong>Success!</strong> Advertisement Removed Succesfully');
                $this->session->set_flashdata('alertType', 'alert-success');
            }else{
                $this->session->set_flashdata('notification', '<strong>Error!</strong> Unable to Remove Advertisement.Try again Later');
                $this->session->set_flashdata('alertType', 'alert-error');
            }
        }
    }



    /*
     * Get single Advertisement if provided adKey
     * */

    public function getAd($adId){
        if($this->adOwner($adId)==$this->session->userdata('key')){
            $this->db->where('pkey',$adId);
            $query=$this->db->get('advAd');
            return $query->result_array();
        }
    }


    /*
   * Get Single Advertisement if provided adKey
   * */

    public function changeStatus($pkey,$status){
        if($this->adOwner($pkey)==$this->session->userdata('key')){

            $this->db->where('pkey',$pkey);
            $query=$this->db->get('advAd');
            $result=$query->result_array();
            if($result>0){
                if($result[0]['status']=='0'){
                    return false;    //Still Un-Verified ,Can't Change
                }else{
                    $data = array(
                        'status' => $status
                    );
                    $this->db->where('pkey', $pkey);
                    return $this->db->update('advAd', $data);
                }
            }else{
                return false;
            }
        }
    }




    /*
   * Get Advertisement Owner by Providing Advertisement Key
   * */

    private function adOwner($adKey){
        $this->db->select('advKeyCamp,advAd.pkey');
        $this->db->from('advCampaign');
        $this->db->join('advAd', 'advAd.campKeyAd = advCampaign.pkey','left');
        $this->db->having('pkey', $adKey);
        $query=$this->db->get();
        $result=$query->result_array();

        if($result){
            return $result[0]['advKeyCamp'];
        }else{
            return false;
        }
    }

}
