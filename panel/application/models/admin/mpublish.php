<?php

class Mpublish extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function getPublishers(){
        $query=$this->db->get('pubLogin');
        $result=$query->result_array();
        return $result;
    }

    public function getAdData($adId){
        $this->db->where('pkey',$adId);
        $query=$this->db->get('advAd');
        $result=$query->result_array();
        return $result[0];
    }

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



    public function getAds(){
        $dailyAds=$this->dailyBudgetAds();
        $lifetimeAds=$this->lifetimeBudgetAds();
        $whiteListedAds=$dailyAds+$lifetimeAds;
        if($whiteListedAds){
            $this->db->select('advAd.name as name,description,link,image,title,advLogin.pkey as advKey,advAd.pkey as pkey');
            $this->db->from('advLogin');
            $this->db->or_where_in('advAd.pkey',$whiteListedAds);
            $this->db->join('advCampaign', 'advCampaign.advKeyCamp = advLogin.pkey','left');
            $this->db->join('advAd', 'advAd.campkeyAd = advCampaign.pkey','left');
            $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
            $query=$this->db->get();
            $result=$query->result_array();

            $Advertisers=array();
            foreach($result as $row){
                $Advertisers[]=$row['advKey'];
            }
            $advertiserBalances=$this->advertiserBalances($Advertisers);
            foreach($result as $key=>$value){
                foreach($advertiserBalances as $advertiser){
                    if($value['advKey']==$advertiser['advertiser']){
                        $result[$key]['points']=$advertiser['points'];
                        $result[$key]['currency']=$advertiser['currency'];
                        $result[$key]['remainingBalance']=$advertiser['remainingBalance'];

                    }
                }
            }
            return $result;
        }
        return null;
    }


    public function advertiserBalances($advertisersArray){
        $this->db->select('currency,amount,transType,advKeyPayment');
        $this->db->select_sum('amount');
        $this->db->from('advLogin');
        $this->db->join('advPayment', 'advPayment.advKeyPayment = advLogin.pkey','left');
        $this->db->or_where_in('advKeyPayment',$advertisersArray);
        $this->db->group_by(array('advPayment.transType','advPayment.advKeyPayment'));
        $query=$this->db->get();
        $result=$query->result_array();
        $balances=array();
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
            $balances[]=array(
                'advertiser'=>$advertiser,
                'remainingBalance'=>$remaining,
                'currency'=>$currency,
                'points'=>$currencyToPoints
            );
        }
        return $balances;
    }

    public function getAdsUserAndCampaign($campKey){
        $this->db->select('advCampaign.name,advCampaign.pkey as campKey,currency,advKeyCamp');
        $this->db->from('advLogin');
        $this->db->join('advCampaign', 'advCampaign.advKeyCamp = advLogin.pkey','left');
        $this->db->having('campKey',$campKey);
        $query=$this->db->get();
        $result=$query->result_array();
        if($result){
            return $result[0];
        }else{
            return false;
        }
    }


    public function addStats($adsData,$postId,$publisherData){
        $adKey=$adsData['pkey'];
        $impressions=$publisherData['totalfriends'];
        $date=dateToday();
        $publisherKey=$publisherData['pkey'];
        $adsParentInfo=$this->getAdsUserAndCampaign($adsData['campkeyAd']);
        $spendConfig=array(
            'pointPerImpression'=>$this->config->item('pointPerImpression'),
            'USD'=>$this->config->item('usdMultiplier'),
            'INR'=>$this->config->item('inrMultiplier'),
            'pubPercent'=>$this->config->item('publisherPercentage')
        );
        $amount=$impressions*$spendConfig['pointPerImpression']*$spendConfig[$adsParentInfo['currency']];


        //Updating Buffer Table
        $parameter=array(
            'adKeyBuffer'=>$adKey,
            'pubKeyBuffer'=>$publisherKey,
            'sharedTo'=>$impressions,
            'postId'=>$postId,
            'timestamp'=>timestampToday()
        );
        $this->db->insert('adBuffer',$parameter);



        //Updating Advertiser Statistics Table
        $this->db->where('date',$date);
        $this->db->where('adKeyStats',$adKey);
        $query=$this->db->get('adStatistics');
        $result=$query->result_array();

        if($result){
            $parameter=array(
                'cpm'=>$result[0]['cpm']+$impressions
            );
            $this->db->where('date',$date);
            $this->db->where('adKeyStats',$adKey);
            $this->db->update('adStatistics',$parameter);

        }else{
            $parameter=array(
                'adKeyStats'=>$adKey,
                'date'=>$date,
                'cpm'=>$impressions,
                'clicks'=>0
            );
            $this->db->insert('adStatistics',$parameter);
        }




        //Updating Advertisers's Payment Table
        $this->db->where('date',$date);
        $this->db->where('advKeyPayment',$adsParentInfo['advKeyCamp']);
        $this->db->where('description','Spend on Campaign "'.$adsParentInfo['name'].'"');
        $query=$this->db->get('advPayment');
        $result=$query->result_array();

        if($result){
            $parameter=array(
                'amount'=>$result[0]['amount']+$amount
            );
            $this->db->where('date',$date);
            $this->db->where('advKeyPayment',$adsParentInfo['advKeyCamp']);
            $this->db->where('description','Spend on Campaign "'.$adsParentInfo['name'].'"');
            $this->db->update('advPayment',$parameter);
        }else{
            $parameter=array(
                'advKeyPayment'=>$adsParentInfo['advKeyCamp'],
                'description'=>'Spend on Campaign "'.$adsParentInfo['name'].'"',
                'date'=>$date,
                'amount'=>$amount,
                'transType'=>'spend',
                'paymentMethod'=>'-'
            );
            $this->db->insert('advPayment',$parameter);
        }




    }

}