<?php

class Mbilling extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function postEarningGraphData($days){

        $finalArray=array();
        $this->db->select('pubKeyPayment,date,transType');
        $this->db->select_sum('amount');
        $this->db->where('transType','earned');
        $this->db->where('description','Story Posting Earning');
        $this->db->where('pubKeyPayment',$this->session->userdata('user_ID'));
        $this->db->from('pubPayment');
        $this->db->group_by('date');
        $query=$this->db->get();
        $result=$query->result_array();
        if($result){
            foreach($result as $row){
                if(createTimeStamp($row['date'])>(time()-$days*86400)){
                    $finalArray[]=$row;
                }
            }
        }

        //Inserting Null Data if No Data Found on a Day

        $today=timestampToday();
        $datesArray=array();
        for($i=0 ;$i < $days; $i++){
            $datesArray[]=timestampToDate($today-($i*86400));
        }

        foreach($datesArray as $date){
            $flag=0;
            foreach($finalArray as $row){
                if($row['date']==$date){
                    $flag=1;
                }
            }
            if($flag==0){
                $insertArray=array(
                    'date'=>$date,
                    'amount'=>0
                );
                $finalArray[]=$insertArray;

            }else{
                $flag=0;
            }
        }

       $timeStampArray=array();
       foreach($finalArray as $row){
           $timeStampArray[]=createTimeStamp($row['date']);
       }

       array_multisort($timeStampArray,SORT_DESC,$finalArray);

       //Creating Graph Data
       $returnString='[';
       foreach($finalArray as $row){
           $returnString.='[ gd('.str_replace('/',',',$row['date']).'),'.$row['amount'].'],';
       }
       $returnString=substr_replace($returnString,"",-1);
       $returnString.=']';
       return $returnString;

    }




    public function clickEarningGraphData($days){

        $finalArray=array();
        $this->db->select('pubKeyPayment,date,transType');
        $this->db->select_sum('amount');
        $this->db->where('transType','earned');
        $this->db->where('description','Story Click Earning');
        $this->db->where('pubKeyPayment',$this->session->userdata('user_ID'));
        $this->db->from('pubPayment');
        $this->db->group_by('date');
        $query=$this->db->get();
        $result=$query->result_array();
        if($result){
            foreach($result as $row){
                if(createTimeStamp($row['date'])>(time()-$days*86400)){
                    $finalArray[]=$row;
                }
            }
        }

        //Inserting Null Data if No Data Found on a Day

        $today=timestampToday();
        $datesArray=array();
        for($i=0 ;$i < $days; $i++){
            $datesArray[]=timestampToDate($today-($i*86400));
        }

        foreach($datesArray as $date){
            $flag=0;
            foreach($finalArray as $row){
                if($row['date']==$date){
                    $flag=1;
                }
            }
            if($flag==0){
                $insertArray=array(
                    'date'=>$date,
                    'amount'=>0
                );
                $finalArray[]=$insertArray;

            }else{
                $flag=0;
            }
        }

        $timeStampArray=array();
        foreach($finalArray as $row){
            $timeStampArray[]=createTimeStamp($row['date']);
        }

        array_multisort($timeStampArray,SORT_DESC,$finalArray);

        //Creating Graph Data
        $returnString='[';
        foreach($finalArray as $row){
            $returnString.='[ gd('.str_replace('/',',',$row['date']).'),'.$row['amount'].'],';
        }
        $returnString=substr_replace($returnString,"",-1);
        $returnString.=']';
        return $returnString;

    }


    public function combinedEarningGraphData($days){
        $postEarning=$this->postEarningGraphData($days);
        $clickEarning=$this->clickEarningGraphData($days);
        $returnString='[{ label: "Daily Story Posting Earning Graph" , data: '.$postEarning.'},{ label: "Daily Story Click Earning Graph" , data: '.$clickEarning.'}]';
        return $returnString;
    }








    public function totalEarning(){
        $this->db->select('pubKeyPayment,date,transType,amount');
        $this->db->where('pubKeyPayment',$this->session->userdata('user_ID'));
        $this->db->from('pubPayment');
        $query=$this->db->get();
        $result=$query->result_array();

        $totalEarning=0;

        foreach($result as $row){
            if($row['transType']=='earned'){
                $totalEarning+=$row['amount'];
            }elseif($row['transType']=='withdrawal'){
                $totalEarning-=$row['amount'];
            }
        }

        return $totalEarning;
    }

    public function transactions(){
        $this->db->select('*');
        $this->db->select_sum('amount');
        $this->db->where('pubKeyPayment',$this->session->userdata('user_ID'));
        $this->db->from('pubPayment');
        $this->db->group_by(array('date','transType','description'));
        $query=$this->db->get();
        $result=$query->result_array();

        $finalArray=array();

        foreach($result as $row){
            if(createTimeStamp($row['date'])>=(timestampToday()-30*86400)){
                $finalArray[]=$row;
            }
        }

        $timeStampArray=array();
        foreach($finalArray as $row){
            $timeStampArray[]=createTimeStamp($row['date']);
        }

        array_multisort($timeStampArray,SORT_DESC,$finalArray);

        return $finalArray;
    }



    public function pendingPayments(){
        $this->db->where('userId',$this->session->userdata('user_ID'));
        $this->db->where('userType','publisher');
        $this->db->where('status','pending');
        $query=$this->db->get('paymentProcessing');
        $result=$query->result_array();
        return $result;
    }


    public function mobileRechargeWithdrawal($data){
        $insertArray=array(
            'status'=>'pending',
            'userType'=>'publisher',
            'transactionType'=>'withdrawal',
            'userId'=>$this->session->userdata('user_ID'),
            'amount'=>$data['inputAmount'],
            'currency'=>'USD',
            'method'=>'Mobile Recharge',
            'Details'=>'{ Mobile Number: "'.$data['inputMobile'].'"}',
            'time'=>timestampToday()
        );
        return $this->db->insert('paymentProcessing',$insertArray);
    }
}
