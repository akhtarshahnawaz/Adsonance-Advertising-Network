<?php

class Mbilling extends CI_Model
{
    function __construct(){
        parent::__construct();
    }


    /*
     * Get Billing Details provided Range of Dates
     * */
    public function billingSummary($from,$to){
            $this->db->select('*');
            //$this->db->select_sum('amount');
            $this->db->where('advKeyPayment',$this->session->userdata('key'));
            $this->db->from('advPayment');
            //$this->db->group_by('date');
            $query=$this->db->get();
            $result=$query->result_array();

            $finalArray=array();
            foreach($result as $row){
                if(createTimeStamp($row['date'])>=createTimeStamp($from) && createTimeStamp($row['date'])<=createTimeStamp($to)){
                    $finalArray[]=$row;
                }
            }

            $timeStampArray=array();
            foreach($finalArray as $row){
                $timeStampArray[]=createTimeStamp($row['date']);
            }

            array_multisort($timeStampArray,$finalArray);

            foreach($finalArray as $key=>$value){
                $finalArray[$key]['date']=dateToString($value['date']);
            }

            return $finalArray;
    }


    public function getUser(){
        $userId=$this->session->userdata('key');
        $this->db->select('*');
        $this->db->from('advLogin');
        $this->db->where('advLogin.pkey',$userId);
        $this->db->join('advInfo','advInfo.advKeyInfo = advLogin.pkey','left');
        $query=$this->db->get();
        $result=$query->result_array();
        return $result[0];
    }

    public function checkPaypalTransId($transId){
        $this->db->where('transId',$transId);
        $query=$this->db->get('advPayment');
        $result=$query->result_array();
        if(!empty($result)){
            return false;
        }else{
            return true;
        }
    }

    public function addPaypalPayment($data){
        if(is_array($data)){
            $insertArray=array(
                'advKeyPayment'=>$data['custom'],
                'date'=>dateToday(),
                'transType'=>'deposit',
                'paymentMethod'=>'paypal',
                'description'=>'',
                'amount'=>$data['payment_amount'],
                'transId'=>$data['txn_id'],
                'transStatus'=>$data['payment_status']
            );
            $this->db->insert('advPayment',$insertArray);
            return $this->db->insert_id();
        }

    }

     public function addCcavenuePayment($data){
        if(is_array($data)){
            $insertArray=array(
                'advKeyPayment'=>$data['custom'],
                'date'=>dateToday(),
                'transType'=>'deposit',
                'paymentMethod'=>'Debit Card / Net Banking / Credit Card',
                'description'=>'',
                'amount'=>$data['payment_amount'],
                'transId'=>$data['txn_id'],
                'transStatus'=>$data['payment_status']
            );
            $this->db->insert('advPayment',$insertArray);
            return $this->db->insert_id();
        }

    }



}
