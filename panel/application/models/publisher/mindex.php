<?php


class Mindex extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function login(){
        /*Setup API*/
        $user_FID = $this->facebook->getUser();

        /*if user has not authenticated already*/
        if (!$user_FID) {
            $loginUrl = $this->facebook->getLoginUrl(array(
                'scope' => $this->config->item('Facebook-Scope'),
                'redirect_uri' => $this->config->item('Facebook-App-Url')
            ));
            print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
        }else{
            /*if user has already authenticated check if proper permissions are given*/
            /* $permissions_list = $this->facebook->api('/me/permissions');
        $permissions_needed = $this->config->item('Facebook-Permission-Needed');
        foreach($permissions_needed as $perm) {
            if( !isset($permissions_list['data'][0][$perm]) || $permissions_list['data'][0][$perm] != 1 ) {
                $loginUrl = $this->facebook->getLoginUrl(array(
                    'scope' => $this->config->item('Facebook-Scope'),
                    'redirect_uri' => $this->config->item('Facebook-App-Url'),
                ));
                print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
            }}*/


            /*Check if user is stored in Database*/
            $user_ID=$this->get_UserID_by_FID($user_FID);
            if(!$user_ID){
                $user_FINFO=$this->facebook->api('/me?fields=first_name,last_name,email,website','GET');
                $this->addUser($user_FID,$user_FINFO);
                $sessiondata = array(
                    'user_FID' => $user_FID,
                    'user_ID'=>$user_ID,
                    'type' => 'publisher',
                    'access_token' => $this->facebook->getAccessToken(),
                    'totalFriends'=>$this->totalFriends(),
                    'PubloggedIn' => TRUE
                );
                $this->session->set_userdata($sessiondata);
                return true;
            }else{
                /*If Proper Permissions are given and user is stored in database then START SESSION - SET SESSION DATA and return true*/
                $sessiondata = array(
                    'user_FID' => $user_FID,
                    'user_ID'=>$user_ID,
                    'type' => 'publisher',
                    'access_token' => $this->facebook->getAccessToken(),
                    'totalFriends'=>$this->totalFriends(),
                    'PubloggedIn' => TRUE
                );
                $this->session->set_userdata($sessiondata);
                return true;
            }
        }
    }



    public function totalFriends(){
        $friendslist=$this->friendsList();
        return count($friendslist['data']);
    }


    public function friendsList(){
        $friends  = $this->facebook->api('/me/friends');
        return $friends;
    }


    function get_UserID_by_FID($user_FID){
        $this->db->where('facebookId',$user_FID);
        $query=$this->db->get('pubLogin');
        $result=$query->result_array();
        if($result){
            return $result[0]['pkey'];
        }else{
            return false;
        }
    }



    public function addUser($userId,$userData){
        $data = array(
            'facebookId'=>$userId,
        );
        $this->db->insert('pubLogin',$data);

        if(isset($userData['first_name'])){
            $firstname=$userData['first_name'];
        }else{
            $firstname='';
        }
        if(isset($userData['last_name'])){
            $lastname=$userData['last_name'];
        }else{
            $lastname='';
        }
        if(isset($userData['email'])){
            $email=$userData['email'];
        }else{
            $email='';
        }

        if(isset($userData['website'])){
            $website=$userData['website'];
        }else{
            $website='';
        }

        $data = array(
            'pubKeyInfo'=>$this->db->insert_id(),
            'firstname' => $firstname ,
            'lastname' => $lastname ,
            'email' => $email,
            'phone' => '',
            'website' => $website,
            'address' => ''
        );
        $this->db->insert('pubInfo',$data);
    }

    public function getUser($userId){
        $this->db->where('facebookId',$userId);
        $query=$this->db->get('pubLogin');
        $data= $query->result_array();
        $this->db->where('pubKeyInfo',$data[0]['pkey']);
        $query=$this->db->get('pubInfo');
        return $query->result_array();
    }

/*  OLD getAdsList Function
    public function getAdsList($publisher_ID){
        $white_listed_users=array();
        $white_listed_campaigns=array();
        $white_listed_ads=array();
        $black_listed_ads=array();
        //Get Whitelisted users
        $this->db->select('pkey');
        $this->db->where('status',1);
        $query=$this->db->get('advLogin');
        $result=$query->result_array();
        foreach($result as $user){
            $white_listed_users[]=$user['pkey'];
        }
        //Get Whitelisted users with active campaigns
        if($white_listed_users){
            $this->db->select('pkey');
            $this->db->or_where_in('advKeyCamp', $white_listed_users);
            $this->db->where('status','active');
            $query=$this->db->get('advCampaign');
            $result= $query->result_array();
            foreach($result as $campaign){
                $white_listed_campaigns[]=$campaign['pkey'];
            }
        }
        //Get active campaigns with active ads

        if($white_listed_campaigns){
            $this->db->or_where_in('campkeyAd',$white_listed_campaigns);
            $this->db->where('status','active');
            $query=$this->db->get('advAd');
            $result= $query->result_array();
            foreach($result as $ad){
                $white_listed_ads[]=$ad['pkey'];
            }
        }
        //check if this publisher hasn't posted this ad recently

        if($white_listed_ads){
            $this->db->select('pkey');
            $this->db->or_where_in('adkeyBuffer',$white_listed_ads);
            $this->db->where('pubKeyBuffer',$publisher_ID);
            $time=time()-1800;
            $this->db->where('timestamp >',$time);
            $query=$this->db->get('adBuffer');
            $result=$query->result_array();
            foreach($result as $ad){
                $black_listed_ads[]=$ad['pkey'];
            }
        }
        $finalised_ads=array_diff($white_listed_ads,$black_listed_ads);


        if($finalised_ads){
            $this->db->or_where_in('pkey',$finalised_ads);
            $query=$this->db->get('advAd');
            return $query->result_array();
        }


        return null;
    }

*/



    public function lifetimeBudgetAds(){
        $this->db->select('clicks,cpm,date,campkeyAd,advLogin.status as userStatus,advCampaign.status as campStatus,advAd.status as adStatus,startDate,endDate,adKeyStats,budget,budgetPeriod,currency,advAd.pkey as adId,advCampaign.pkey as campId');
        $this->db->select_sum('clicks');
        $this->db->select_sum('cpm');
        $this->db->from('advLogin');
        $this->db->where('budgetPeriod','lifetime');
        $this->db->join('advCampaign', 'advCampaign.advKeyCamp = advLogin.pkey','left');
        $this->db->join('advAd', 'advAd.campkeyAd = advCampaign.pkey','left');
        $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
        $this->db->group_by('advCampaign.pkey');
        $query=$this->db->get();
        $result=$query->result_array();
        $finalArray=array();
        foreach($result as $row){
            if($row['userStatus']==1 && $row['campStatus']==2 && $row['adStatus']==2){
                $startDate=createTimeStamp($row['startDate']);
                if($row['endDate'] == 'N/A'){
                    $endDate='N/A';
                }else{
                    $endDate=createTimeStamp($row['endDate']);
                }
                if($startDate<=timestampToday() && ($endDate>=timestampToday() || $endDate=='N/A')){
                    $cpmPoints=$row['cpm']*$this->config->item('pointPerImpression');
                    $clickPoints=$row['clicks']*$this->config->item('pointPerClick');
                    if($row['currency']=='USD'){
                        $spend=($cpmPoints+$clickPoints)*$this->config->item('usdMultiplier');
                    }elseif($row['currency']=='INR'){
                        $spend=($cpmPoints+$clickPoints)*$this->config->item('inrMultiplier');
                    }
                    if($spend<$row['budget']){
                        if($row['adId']!=null){
                            $finalArray[]=$row['adId'];
                        }
                    }
                }
            }
        }
        return $finalArray;
    }



    public function dailyBudgetAds(){
        $this->db->select('clicks,cpm,date,campKeyAd,advLogin.status as userStatus,advCampaign.status as campStatus,advAd.status as adStatus,startDate,endDate,adKeyStats,budget,budgetPeriod,currency,advAd.pkey as adId,advCampaign.pkey as campId');
        $this->db->select_sum('clicks');
        $this->db->select_sum('cpm');
        $this->db->from('advLogin');
        $this->db->where('budgetPeriod','daily');
        $this->db->join('advCampaign', 'advCampaign.advKeyCamp = advLogin.pkey','left');
        $this->db->join('advAd', 'advAd.campKeyAd = advCampaign.pkey','left');
        $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
        $this->db->group_by(array('date', 'advCampaign.pkey'));
        $query=$this->db->get();
        $result=$query->result_array();
        $finalArray=array();
        foreach($result as $row){
            //if($row['date']==dateToday() || $row['date']==null){
                if($row['userStatus']==1 && $row['campStatus']==2 && $row['adStatus']==2){
                    $startDate=createTimeStamp($row['startDate']);
                    if($row['endDate'] == 'N/A'){
                        $endDate='N/A';
                    }else{
                        $endDate=createTimeStamp($row['endDate']);
                    }
                    if($startDate<=timestampToday() && ($endDate>=timestampToday() || $endDate=='N/A')){
                        $cpmPoints=$row['cpm']*$this->config->item('pointPerImpression');
                        $clickPoints=$row['clicks']*$this->config->item('pointPerClick');
                        if($row['currency']=='USD'){
                            $spend=($cpmPoints+$clickPoints)*$this->config->item('usdMultiplier');
                        }elseif($row['currency']=='INR'){
                            $spend=($cpmPoints+$clickPoints)*$this->config->item('inrMultiplier');
                        }
                        if($spend<$row['budget']){
                            if($row['adId']!=null){
                                $finalArray[]=$row['adId'];
                            }
                        }
                    }
                }
            //}
        }
        return $finalArray;
    }



    public function getAds(){
        $dailyAds=$this->dailyBudgetAds();
        $lifetimeAds=$this->lifetimeBudgetAds();
        $whiteListedAds=$dailyAds+$lifetimeAds;
        $black_listed_ads=array();
        if($whiteListedAds){
            $this->db->or_where_in('adkeyBuffer',$whiteListedAds);
            $this->db->where('pubKeyBuffer',$this->session->userdata('user_ID'));
            $time=timestampToday()-$this->config->item('timeBetweenAds');
            $this->db->where('timestamp >',$time);
            $query=$this->db->get('adBuffer');
            $result=$query->result_array();
            foreach($result as $ad){
                $black_listed_ads[]=$ad['adKeyBuffer'];
            }
        }
        $finalised_ads=array_diff($whiteListedAds,$black_listed_ads);
        if($finalised_ads){
            $this->db->or_where_in('pkey',$finalised_ads);
            $query=$this->db->get('advAd');
            return $query->result_array();
        }
        return null;
    }


}