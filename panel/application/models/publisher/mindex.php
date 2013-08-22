<?php


class Mindex extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function login($redirectURI=null){
        if(isset($redirectURI)){
            $redirect=$redirectURI;
        }else{
            $redirect=$this->config->item('Facebook-App-Url');
        }
        /*Setup API*/
        $user_FID = $this->facebook->getUser();

        /*if user has not authenticated already*/
        if (!$user_FID) {
            $loginUrl = $this->facebook->getLoginUrl(array(
                'scope' => $this->config->item('Facebook-Scope'),
                'redirect_uri' => $redirect
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
            if($user_ID==false){
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
        if($user_FID){
        $this->db->where('facebookId',$user_FID);
        $query=$this->db->get('pubLogin',1);
        $result=$query->result_array();
        if(!empty($result)){
            return $result[0]['pkey'];
        }else{
            return false;
        }
        }
    }



    public function addUser($userId,$userData){
        $data = array(
            'facebookId'=>$userId
        );
        $this->db->insert('pubLogin',$data);
        $insertID=$this->db->insert_id();

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
            'pubKeyInfo'=>$insertID,
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


    /*
     * Get Ads list which are active and have life time of campaign budget
     * */
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


    /*
   * Get Ads list which are active and have daily of campaign budget
   * */
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


    /*
   * Check Whether advertisers have enough balance to pay for this advertisement if
   *  not then finally create an array of blacklisted advertisers
   * */

    public function advertiserBalances($advertisersArray){
        $this->db->select('currency,amount,transType,advKeyPayment');
        $this->db->select_sum('amount');
        $this->db->from('advLogin');
        $this->db->join('advPayment', 'advPayment.advKeyPayment = advLogin.pkey','left');
        $this->db->or_where_in('advKeyPayment',$advertisersArray);
        $this->db->group_by(array('advPayment.transType','advPayment.advKeyPayment'));
        $query=$this->db->get();
        $result=$query->result_array();
        $blacklist=array();
        foreach($advertisersArray as $advertiser){
            $spend=0;
            $deposit=0;
            $currency='';
            $currencyToPoints=0;
            foreach($result as $row){
                if($row['transType']=='deposit' && $row['advKeyPayment'] == $advertiser){
                    $deposit+=$row['amount'];
                    $currency=$row['currency'];
                }elseif($row['transType']=='spend' && $row['advKeyPayment'] == $advertiser){
                    $spend+=$row['amount'];
                    $currency=$row['currency'];
                }
            }
            $remaining=$deposit-$spend;
            $this->config->load('admin_settings');
            if($currency=='USD'){
                $currencyToPoints=$remaining/$this->config->item('usdMultiplier');
            }elseif($currency=='INR'){
                $currencyToPoints=$remaining/$this->config->item('inrMultiplier');
            }

            if($currencyToPoints < $this->session->userdata('totalFriends')){
                $blacklist[]=$advertiser;
            }
            
        }
        return $blacklist;
    }


    /*
     * Get lifetime whitelisted ads and daily whitelisted ads and then check
     *  them in buffer table if they can be shown right now after that checks
     * whether advertiser has enough credits to pay for this ad by using
     * function advertiserBalances() if yes then it creates list of final
     * advertisements to show.
     * */

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
        $newWhiteListedAds=array_diff($whiteListedAds,$black_listed_ads);
        if($newWhiteListedAds){
            $this->db->select('advAd.name as name,description,link,image,title,advLogin.pkey as advKey,advAd.pkey as pkey');
            $this->db->from('advLogin');
            $this->db->or_where_in('advAd.pkey',$newWhiteListedAds);
            $this->db->join('advCampaign', 'advCampaign.advKeyCamp = advLogin.pkey','left');
            $this->db->join('advAd', 'advAd.campkeyAd = advCampaign.pkey','left');
            $query=$this->db->get();
            $result=$query->result_array();

            $Advertisers=array();
            foreach($result as $row){
                $Advertisers[]=$row['advKey'];
            }
            $blacklist_due_to_low_balance=$this->advertiserBalances($Advertisers);
            $finalArray=array();
            foreach($result as $ad){
                if(in_array($ad['advKey'],$blacklist_due_to_low_balance)){
                    //BLACKLIST AD
                }else{
                    $finalArray[]=$ad;
                }
            }
            return $finalArray;
        }
        return null;
    }

}