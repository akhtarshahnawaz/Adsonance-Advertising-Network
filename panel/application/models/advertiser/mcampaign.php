<?php
class Mcampaign extends CI_Model
{
    function __construct(){
        parent::__construct();
    }


    /*
     * To Create a Campaign
     * */

    public function create($data){
        if(isset($data['scheduleType'])){
            $insertArray=array(
                'advKeyCamp'=>$this->session->userdata('key'),
                'name'=>$data['inputCampaignName'],
                'budget'=>$data['inputBudget'],
                'budgetPeriod'=>$data['budgetPeriod'],
                'startDate'=>dateToday(),
                'endDate'=>'N/A',
                'status'=>'2'
            );
        }else{
            if(!isset($data['inputEndDate']) || $data['inputEndDate']==null || $data['inputEndDate']==''){
                $data['inputEndDate']='N/A';
            }
            $insertArray=array(
                'advKeyCamp'=>$this->session->userdata('key'),
                'name'=>$data['inputCampaignName'],
                'budget'=>$data['inputBudget'],
                'budgetPeriod'=>$data['budgetPeriod'],
                'startDate'=>$data['inputStartDate'],
                'endDate'=>$data['inputEndDate'],
                'status'=>'2'
            );
        }
        $result=$this->db->insert('advCampaign',$insertArray);
        if($result){
            $this->session->set_flashdata('notification', '<strong>Success!</strong> Campaign Created Succesfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }else{
            $this->session->set_flashdata('notification', '<strong>Error!</strong> Unable to Create Campaign.Try again Later');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
    }



    /*
     * To Remove a Campaign
     * */

    public function remove($pkey){
        $this->db->where('pkey',$pkey);
        $this->db->where('advKeyCamp',$this->session->userdata('key'));
        $result= $this->db->delete('advCampaign');
        if($result){
            $this->session->set_flashdata('notification', '<strong>Success!</strong> Campaign Removed Succesfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }else{
            $this->session->set_flashdata('notification', '<strong>Error!</strong> Unable to Remove Campaign.Try again Later');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
    }


    /*
     * To Get Details about a Campaign if provided Campaign Key
     * */

    public function campaignDetails($pkey){
        $this->db->where('pkey',$pkey);
        $this->db->where('advKeyCamp',$this->session->userdata('key'));
        $query=$this->db->get('advCampaign',1);
        $result=$query->result_array();
        return $result[0];
    }


    /*
     * To Edit a Campaign
     * */

    public function edit($data){

        if(isset($data['scheduleType'])){
            $insertArray=array(
                'advKeyCamp'=>$this->session->userdata('key'),
                'name'=>$data['inputCampaignName'],
                'budget'=>$data['inputBudget'],
                'budgetPeriod'=>$data['budgetPeriod'],
                'startDate'=>dateToday(),
                'endDate'=>'N/A',
                'status'=>'2'
            );
        }else{
            if($data['inputEndDate']==''){
                $data['inputEndDate']='N/A';
            }
            $insertArray=array(
                'advKeyCamp'=>$this->session->userdata('key'),
                'name'=>$data['inputCampaignName'],
                'budget'=>$data['inputBudget'],
                'budgetPeriod'=>$data['budgetPeriod'],
                'startDate'=>$data['inputStartDate'],
                'endDate'=>$data['inputEndDate'],
                'status'=>'2'
            );
        }
        $this->db->where('pkey',$data['pkey']);
        $this->db->where('advKeyCamp',$this->session->userdata('key'));
        $result= $this->db->update('advCampaign',$insertArray);
        if($result){
            $this->session->set_flashdata('notification', '<strong>Success!</strong> Campaign Edited Succesfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }else{
            $this->session->set_flashdata('notification', '<strong>Error!</strong> Unable to Edit Campaign.Try Again Later');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
    }


    /*
     * To Get Advertisements in a single Campaign
     * */

    public function single($campkey){
        if($this->CampOwner($campkey)==$this->session->userdata('key')){
            $this->db->select('adKeyStats,campKeyAd,advAd.name,status,advAd.pkey');
            $this->db->select_sum('clicks');
            $this->db->select_sum('cpm');
            $this->db->from('advAd');
            $this->db->where('campKeyAd', $campkey);
            $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
            $this->db->group_by('pkey');
            $query=$this->db->get();
            return $query->result_array();
        }
    }


    /*
     * Change Status of a Campaign
     * */
    public function changeStatus($pkey,$status){
        $this->db->where('pkey',$pkey);
        $this->db->where('advKeyCamp',$this->session->userdata('key'));
        $query=$this->db->get('advCampaign');
        $result=$query->result_array();
        if($result>0){
            if($result[0]['status']=='0'){
                return false;    //Still Un-Verified ,Can't Change
            }else{
                $data = array(
                    'status' => $status
                );
                $this->db->where('pkey', $pkey);
                return $this->db->update('advCampaign', $data);
            }
        }else{
            return false;
        }
    }


    /*
     * Get Campaign Owner by Providing Campaign Key
     * */

    private function CampOwner($campKey){
        $this->db->where('pkey',$campKey);
        $query=$this->db->get('advCampaign');
        $result=$query->result_array();
        if($result){
            return $result[0]['advKeyCamp'];
        }else{
            return false;
        }
    }


}
