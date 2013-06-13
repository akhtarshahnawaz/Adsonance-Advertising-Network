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




}
