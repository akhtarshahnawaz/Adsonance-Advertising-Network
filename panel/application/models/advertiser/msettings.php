<?php

class Msettings extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function getGeneralSettings(){
        $this->db->where('advKeyInfo',$this->session->userdata('key'));
        $query=$this->db->get('advInfo');
        $result=$query->result_array();
        if($result){
            return $result[0];
        }else{
            return false;
        }
    }


    public function updateGeneralSettings($data){
        $updateArray=array(
            'firstname'=>$data['inputFirstName'],
            'lastname'=>$data['inputLastName'],
            'company'=>$data['inputCompany'],
            'designation'=>$data['inputDesignation'],
            'phone'=>$data['inputPhone'],
            'website'=>$data['inputWebsite'],
            'address'=>$data['inputPostalZip'].'$~$'.$data['inputCountry'].'$~$'.$data['inputCity'].'$~$'.$data['inputStreetApp']
        );
        $this->db->where('advKeyInfo',$this->session->userdata('key'));
        return $this->db->update('advInfo',$updateArray);
    }


    public function getSecuritySettings(){
        $this->db->where('pkey',$this->session->userdata('key'));
        $query=$this->db->get('advLogin');
        $result=$query->result_array();
        if($result){
            return $result[0];
        }else{
            return false;
        }
    }



    public function updatePassword($data){
      $oldSettings=$this->getSecuritySettings();
        if($oldSettings['password']==sha1($data['inputCurrentPassword'])){
            if($data['inputNewPassword']==$data['inputReEnterPassword']){
                $updateArray=array(
                    'password'=>sha1($data['inputNewPassword'])
                    );
                $this->db->where('pkey',$this->session->userdata('key'));
                $this->db->update('advLogin',$updateArray);
                $this->session->set_flashdata('notification', '<strong>Success!</strong> Password Succesfully Changed');
                $this->session->set_flashdata('alertType', 'alert-success');
                return true;
            }else{
                $this->session->set_flashdata('notification', '<strong>Error!</strong> Please check that your passwords match.');
                $this->session->set_flashdata('alertType', 'alert-error');
                return false;
            }
        }else{
            $this->session->set_flashdata('notification', '<strong>Error!</strong> Incorrect Current Password');
            $this->session->set_flashdata('alertType', 'alert-error');
            return false;
        }
    }
}
