<?php

class Mspend extends CI_Model
{
    function __construct(){
        parent::__construct();
    }


    /*
     * Returns Total Spend on each days with number of days given as argument
     * FORMAT:
     * array(date=>spend , date2=>spend , daten=>spend)
     * */

    public function dailySpend($days){
        $finalArray=array();

        $this->db->select('advKeyPayment,date,transType');
        $this->db->select_sum('amount');
        $this->db->where('transType','spend');
        $this->db->where('advKeyPayment',$this->session->userdata('key'));
        $this->db->from('advPayment');
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

        foreach($finalArray as $key=>$value){
            if($value['date']==dateToday()){
                $date='Today';
            }else{
                $date=dateToString($value['date']);
            }
            $finalArray[$key]['date']=$date;
        }

        return $finalArray;
    }




    public function balances(){
        $this->db->where('advKeyPayment',$this->session->userdata('key'));
        $query=$this->db->get('advPayment');
        $result=$query->result_array();

        $finalArray=array(
            'totalDesposit'=>0,
            'totalSpend'=>0,
            'remainingBalance'=>0
        );
        foreach($result as $row){
            if($row['transType']=='deposit'){
                $finalArray['totalDesposit']=$finalArray['totalDesposit']+$row['amount'];
            }elseif($row['transType']=='spend'){
                $finalArray['totalSpend']=$finalArray['totalSpend']+$row['amount'];
            }
        }

        $finalArray['remainingBalance']=$finalArray['totalDesposit']-$finalArray['totalSpend'];
        return $finalArray;
    }



    /*
     * Creates Daily Spend Graph Data for Billing page
     * */

    public function dailySpendGraphData(){
        $data=$this->dailySpend(30);
        foreach($data as $key=>$row){
            if($row['date']=='Today'){
                $data[$key]['date']=dateToString(dateToday());
            }
        }
        $returnString='[';
        foreach($data as $row){
            $returnString.='[ gd('.str_replace('/',',',stringToDate($row['date'])).'),'.$row['amount'].'],';
        }
        $returnString=substr_replace($returnString,"",-1);
        $returnString.=']';
        $returnString='[{ label: "Daily Spend Graph" , data: '.$returnString.'}]';
        return $returnString;
    }


}
