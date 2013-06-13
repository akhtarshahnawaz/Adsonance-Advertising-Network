<?php



class Mstats extends CI_Model
{

    function __construct(){
        parent::__construct();
    }



    /*
     * Returns User Campaign's Stats ie. sum of stats of all ads in a given campaign
     * [FORMAT]
     *
     * array(
     * 'campaignKey'=>array(
     * '0'=>array('date'=>'','cpm'=>'','clicks'=>'','campKeyAd'=>'','name'=>''),
     * '1'=>array(row2),
     * 'n'=>array(rown)
        ))
     */
    public function getUserStats($userId){
        $this->db->select('advKeyCamp,date,campKeyAd,adKeyStats,advCampaign.name');
        $this->db->select_sum('clicks');
        $this->db->select_sum('cpm');
        $this->db->where('advKeyCamp',$userId);
        $this->db->from('advCampaign');
        $this->db->join('advAd', 'advAd.campKeyAd = advCampaign.pkey','left');
        $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
        $this->db->group_by(array("date", "campKeyAd"));
        $query=$this->db->get();
        $result=$query->result_array();

        $timeStampArray=array();

        foreach($result as $row){
            $timeStampArray[]=createTimeStamp($row['date']);
        }

        array_multisort($timeStampArray,$result);

        $finalArray=array();

        foreach($result as $row){
            $finalArray[$row['campKeyAd']][]=$row;
        }


        /*Sending NULL Data if No Campaign is Created and User Has Just Signed Up
         * */
        if($finalArray==null){
            for($i=0;$i<3;$i++){
                $insertArray=array(
                    'date'=>timestampToDate(timestampToday()-(86400*$i)),
                    'cpm'=>'0',
                    'clicks'=>'0',
                    'campKeyAd'=>0,
                    'name'=>''
                );
                $finalArray[0][]=$insertArray;
            }
        }

        /*
         * End of sending Null Data
         * */
        return $finalArray;
    }




    /*
   * Returns Statistics of a Single Advertisement
   * Gets Ad ID as Argument and returns Statistics of that Advertisement
   * [FORMAT]:
   * array(
     * '0'=>array('date'=>'','cpm'=>'','clicks'=>'','adKeyStats'=>'','name'=>''),
     * '1'=>array(ROW-1),
     * 'n'=>array(ROW-n))
   *
   * */



    public function getAdStats($adID){
        if($this->adOwner($adID)==$this->session->userdata('key')){
            $this->db->select('name,clicks,cpm,adKeyStats,date');
            $this->db->from('advAd');
            $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
            $this->db->where('adKeyStats',$adID);
            $query=$this->db->get();
            $result=$query->result_array();

            $timeStampArray=array();

            foreach($result as $row){
                $timeStampArray[]=createTimeStamp($row['date']);
            }

            array_multisort($timeStampArray,$result);
            /*Sending NULL Data if No Campaign is Created i.e. User Has Just Signed Up
           * */
            if($result==null){
                for($i=0;$i<3;$i++){
                    $insertArray=array(
                        'date'=>timestampToDate(timestampToday()-(86400*$i)),
                        'cpm'=>'0',
                        'clicks'=>'0',
                        'adKeyStats'=>0,
                        'name'=>''
                    );
                    $result[]=$insertArray;
                }
            }

            /*
           * End of sending Null Data
           * */
            return $result;
        }
        return false;
    }



    /*
     * Returns Statistics of Ads of a Single Campaign
     * Gets Campaign ID as Argument and returns Statistics of all ads in that Campaign in Given Format
     * [FORMAT]:
     * array(
     * 'AdKey'=>array(
     * '0'=>array('date'=>'','cpm'=>'','clicks'=>'','adKeyStats'=>'','name'=>''),
     * '1'=>array(ROW-1),
     * 'n'=>array(ROW-n))
     * )
     *
     * */


    public function getCampStats($campID){
        if($this->CampOwner($campID)==$this->session->userdata('key')){
            $this->db->select('date,campKeyAd,adKeyStats,advAd.name');
            $this->db->select_sum('clicks');
            $this->db->select_sum('cpm');
            $this->db->where('campKeyAd', $campID);
            $this->db->from('advAd');
            $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
            $this->db->group_by(array("date", "adKeyStats"));

            $query=$this->db->get();
            $result=$query->result_array();

            $timeStampArray=array();

            foreach($result as $row){
                $timeStampArray[]=createTimeStamp($row['date']);
            }

            array_multisort($timeStampArray,$result);

            $finalArray=array();

            foreach($result as $row){
                $finalArray[$row['adKeyStats']][]=$row;
            }

            /*Sending NULL Data if No Advertisements are Created i.e. User Has Just Signed Up
           * */
            if($finalArray==null){
                for($i=0;$i<3;$i++){
                    $insertArray=array(
                        'date'=>timestampToDate(timestampToday()-(86400*$i)),
                        'cpm'=>'0',
                        'clicks'=>'0',
                        'adKeyStats'=>0,
                        'name'=>''
                    );
                    $finalArray[0][]=$insertArray;
                }
            }

            /*
           * End of sending Null Data
           * */

            return $finalArray;
        }
        return false;
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
