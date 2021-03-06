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
            'pubPercent'=>$this->config->item('adminPublishPublisherPercentage')
        );
        $amount=$impressions*$spendConfig['pointPerImpression']*$spendConfig[$adsParentInfo['currency']];
        $publisherEarning=$impressions*$spendConfig['pointPerImpression']*$spendConfig['USD']*$spendConfig['pubPercent'];


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



        //Updating Publisher's Statistics Table
        $this->db->where('date',$date);
        $this->db->where('adType','Facebook Share');
        $this->db->where('pubKeyStats',$publisherKey);
        $query=$this->db->get('pubStatistics');
        $result=$query->result_array();

        if($result){
            $parameter=array(
                'cpm'=>$result[0]['cpm']+$impressions
            );
            $this->db->where('date',$date);
            $this->db->where('pubKeyStats',$publisherKey);
            $this->db->update('pubStatistics',$parameter);
        }else{
            $parameter=array(
                'pubKeyStats'=>$publisherKey,
                'adType'=>'Facebook Share',
                'date'=>$date,
                'cpm'=>$impressions,
                'clicks'=>0
            );
            $this->db->insert('pubStatistics',$parameter);
        }


        //Updating Publisher's Payment Table
        $this->db->where('date',$date);
        $this->db->where('pubKeyPayment',$publisherKey);
        $this->db->where('transType','earned');
        $this->db->where('description','Story Posting Earning');
        $query=$this->db->get('pubPayment');
        $result=$query->result_array();

        if($result){
            $parameter=array(
                'amount'=>$result[0]['amount']+$publisherEarning
            );
            $this->db->where('date',$date);
            $this->db->where('transType','earned');
            $this->db->where('description','Story Posting Earning');
            $this->db->where('pubKeyPayment',$publisherKey);
            $this->db->update('pubPayment',$parameter);
        }else{
            $parameter=array(
                'pubKeyPayment'=>$publisherKey,
                'date'=>$date,
                'amount'=>$publisherEarning,
                'transType'=>'earned',
                'description'=>'Story Posting Earning',
                'transMethod'=>'-'
            );
            $this->db->insert('pubPayment',$parameter);
        }

    }

}