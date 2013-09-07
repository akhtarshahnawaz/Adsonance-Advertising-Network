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

    public function getVerifiedAds(){
        $this->db->select('advLogin.pkey as advKey,currency,clicks,cpm,date,campkeyAd,advLogin.status as userStatus,advCampaign.status as campStatus,advAd.status as adStatus,startDate,endDate,adKeyStats,budget,budgetPeriod,currency,advAd.pkey as adId,advCampaign.pkey as campId,title,image,advAd.name as name,link,description,advAd.pkey as pkey');
        $this->db->select_sum('clicks');
        $this->db->select_sum('cpm');
        $this->db->from('advLogin');
        $this->db->where('advLogin.status',1);
        $this->db->where('advCampaign.status',2);
        $this->db->where('advAd.status',2);

        $this->db->join('advCampaign', 'advCampaign.advKeyCamp = advLogin.pkey','left');
        $this->db->join('advAd', 'advAd.campkeyAd = advCampaign.pkey','left');
        $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');

        $this->db->group_by(array('advAd.pkey','date'));
        $query=$this->db->get();
        $result=$query->result_array();

        $this->deleteOutTimedAds($result);
        $this->patchPointsSpendOnAds($result);

        $splitedAds=$this->splitOnBudgetPeriod($result);
        $dailyBudgetAds=$splitedAds['dailyAds'];
        $lifetimeBudgetAds=$splitedAds['lifetimeAds'];


        $dailyBudgetAds=$this->todayAdsBudget($dailyBudgetAds);
        $lifetimeBudgetAds=$this->lifetimeAdsBudget($lifetimeBudgetAds);

        $this->patchSpendOnCampaign($dailyBudgetAds);
        $this->patchSpendOnCampaign($lifetimeBudgetAds);

        $this->validateBudget($dailyBudgetAds);
        $this->validateBudget($lifetimeBudgetAds);

        return array('dailyAds'=>$dailyBudgetAds,'lifetimeAds'=>$lifetimeBudgetAds);
    }

    public function deleteOutTimedAds(&$Ads){
        foreach($Ads as $key=>$row){
            $startDate=createTimeStamp($row['startDate']);
            if($row['endDate'] == 'N/A'){
                $endDate='N/A';
            }else{
                $endDate=createTimeStamp($row['endDate']);
            }
            if($startDate<=timestampToday() && ($endDate>=timestampToday() || $endDate=='N/A')){
                //Leave as it is
            }else{
                unset($Ads[$key]);
            }
        }
    }


    public function patchPointsSpendOnAds(&$Ads){
        foreach($Ads as $key=>$row){
            $cpmPoints=$row['cpm']*$this->config->item('pointPerImpression');
            $clickPoints=$row['clicks']*$this->config->item('pointPerClick');
            $Ads[$key]['cpm']=$cpmPoints;
            $Ads[$key]['clicks']=$clickPoints;
        }
    }


    public function splitOnBudgetPeriod($result){
        $dailyAds=array();
        $lifetimeAds=array();
        foreach($result as $row){
            if($row['budgetPeriod']=='daily'){
                $dailyAds[]=$row;
            }elseif($row['budgetPeriod']=='lifetime'){
                $lifetimeAds[]=$row;
            }
        }
        return array('dailyAds'=>$dailyAds,'lifetimeAds'=>$lifetimeAds);
    }


    public function todayAdsBudget($dailyBudgetAds){
        $dailyAds=array();
        foreach($dailyBudgetAds as $row){
            if($row['date']==dateToday()){
                $dailyAds[$row['adId']]=$row;
            }else{
                $row['cpm']=0;
                $row['clicks']=0;
                $dailyAds[$row['adId']]=$row;
            }
        }
        return $dailyAds;
    }

    public function lifetimeAdsBudget($lifetimeBudgetAds){
        $lifetimeAds=array();
        foreach($lifetimeBudgetAds as $row){
            if(isset($lifetimeAds[$row['adId']])){
                $lifetimeAds[$row['adId']]['cpm']+=$row['cpm'];
                $lifetimeAds[$row['adId']]['clicks']+=$row['clicks'];
            }else{
                $lifetimeAds[$row['adId']]=$row;
            }
        }

        return $lifetimeAds;
    }


    function patchSpendOnCampaign(&$Ads){
        $spendOnCampaign=array();
        foreach($Ads as $row){
            if(isset($spendOnCampaign[$row['campId']])){
                $spendOnCampaign[$row['campId']]['cpm']+=$row['cpm'];
                $spendOnCampaign[$row['campId']]['clicks']+=$row['clicks'];
            }else{
                $spendOnCampaign[$row['campId']]=array(
                    'cpm'=>$row['cpm'],
                    'clicks'=>$row['clicks']
                );
            }
        }

        foreach($Ads as $okey=>$orow){
            foreach($spendOnCampaign as $ikey=>$irow){
                if($orow['campId']==$ikey){
                    $Ads[$okey]['cpm']=$irow['cpm'];
                    $Ads[$okey]['clicks']=$irow['clicks'];
                }
            }
        }
    }



    public function validateBudget(&$ads){
        foreach($ads as $key=>$row){
            if($row['currency']=='USD'){
                $spend=($row['cpm']+$row['clicks'])*$this->config->item('usdMultiplier');
                $multiplier=$this->config->item('usdMultiplier');
            }elseif($row['currency']=='INR'){
                $spend=($row['cpm']+$row['clicks'])*$this->config->item('inrMultiplier');
                $multiplier=$this->config->item('inrMultiplier');
            }

            if($spend>$row['budget']){
                unset($ads[$key]);
            }else{
                $remainingBudget=$row['budget']-$spend;
                $ads[$key]['remainingPoints']=$remainingBudget/$multiplier;
            }
        }
    }



    public function getAds(){
        $ads=$this->getVerifiedAds();
        $dailyAds=$ads['dailyAds'];
        $lifetimeAds=$ads['lifetimeAds'];

        $advertisers=array();

        foreach($dailyAds as $row){
         $advertisers[]=$row['advKey'];
        }
        foreach($lifetimeAds as $row){
            $advertisers[]=$row['advKey'];
        }

        if(!empty($advertisers)){
        $advertiserBalances=$this->advertiserBalances($advertisers);
        $this->patchAdvertiserBalances($dailyAds,$advertiserBalances);
        $this->patchAdvertiserBalances($lifetimeAds,$advertiserBalances);

        return array('dailyAds'=>$dailyAds,'lifetimeAds'=>$lifetimeAds);
        }else{
            return null;
        }
    }


    public function patchAdvertiserBalances(&$ads,$advertiserBalances){
        foreach($ads as $key=>$value){
            foreach($advertiserBalances as $advertiser){
                if($value['advKey']==$advertiser['advertiser']){
                    $ads[$key]['points']=$advertiser['points'];
                    $ads[$key]['remainingBalance']=$advertiser['remainingBalance'];
                }
            }
        }
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