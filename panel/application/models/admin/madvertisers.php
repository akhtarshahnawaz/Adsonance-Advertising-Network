<?php

class Madvertisers extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function getAdvertisers($advKey=null){
        if(isset($advKey)){
            $this->db->where('advKeyInfo',$advKey);
            $query=$this->db->get('advInfo');
            return $query->result_array();
        }else{
            $this->db->select('*');
            $this->db->from('advLogin');
            $this->db->join('advInfo', 'advInfo.advKeyInfo = advLogin.pkey','left');
            $query = $this->db->get();
            return $query->result_array();
        }
    }


    public function getAdvertiser($advKey){
        $this->db->where('pkey',$advKey);
        $query=$this->db->get('advLogin');
        $result=$query->result_array();
        return $result;
    }


    public function addfund($data){
        $inputArray=array(
            'advKeyPayment'=>$data['advKey'],
            'date'=>$data['inputPayDate'],
            'transType'=>'deposit',
            'paymentMethod'=>$data['inputPayMethod'],
            'description'=>$data['inputDescription'],
            'amount'=>$data['inputAmount'],
            'transId'=>$data['advKey'].'break'.timestampToday(),
            'transStatus'=>'SUCCESS'
        );
        if($this->db->insert('advPayment',$inputArray)){
            $this->session->set_flashdata('notification', 'Fund Added Succesfully');
            $this->session->set_flashdata('alertType', 'alert-success');
            redirect('/admin/advertisers/index', 'refresh');
        }
    }
}
