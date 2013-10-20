<?php

class Mindex extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        $this->db->select('advKeyCamp,advCampaign.pkey,advCampaign.name,advCampaign.budget,advCampaign.budgetPeriod,advCampaign.startDate,advCampaign.endDate,advCampaign.status,date,campKeyAd,adKeyStats,advCampaign.name');
        $this->db->select_sum('clicks');
        $this->db->select_sum('cpm');
        $this->db->where('advKeyCamp',$this->session->userdata('key'));
        $this->db->from('advCampaign');
        $this->db->join('advAd', 'advAd.campKeyAd = advCampaign.pkey','left');
        $this->db->join('adStatistics', 'adStatistics.adKeyStats = advAd.pkey','left');
        $this->db->group_by("pkey");
        $query=$this->db->get();
        $result=$query->result_array();
        return $result;
    }



    public function generateSignupErrors($data){
        /* Generating errors based on Input Data*/
        $error='<strong>There were some problems with your request</strong></br> ';
        $status='no';
        //Validating Email Address
        if($data['inputEmail']==''){
            $error.="Missing e-mail address.</br>";
            $status='yes';
        }else{
            if(!filter_var($data['inputEmail'], FILTER_VALIDATE_EMAIL)){
                $error.="Invalid e-mail address.</br>";
                $status='yes';
            }
        }
        //Validating Password
        if($data['inputPassword']==''){
            $error.="Please enter your password.</br>";
            $status='yes';
        }elseif(mb_strlen($data['inputPassword'])<8){
            $error.="Password must have at least 8 characters.</br>";
            $status='yes';
        }else{
            if($data['inputPassword']!= $data['inputReEnterPassword']){
                $error.="Please check that your passwords match.</br>";
                $status='yes';
            }
        }
        //Validating Currency
        if($data['inputCurrency']==''){
            $error.="You must select a preferred currency. </br>";
            $status='yes';
        }
        //Validating First Name
        if($data['inputFirstname']==''){
            $error.="You must provide a name.</br>";
            $status='yes';
        }
        //Validating Phone
        if($data['inputPhone']==''){
            $error.="You must provide a phone number.</br>";
            $status='yes';
        }
        return array(
            'status'=>$status,
            'error'=>$error
        );
    }


    public function signup($data){
        $error=$this->generateSignupErrors($data);
        /* Checking whether we should input data */
        if($error['status']=='yes'){
            $returnArray=array(
                'errors'=>$error['error'],
                'data'=>$data,
                'status'=>'inputError'
            );
            return $returnArray;
        }else{
            $checkEmail=$this->checkEmail($data['inputEmail']);
            if($checkEmail){
                $returnArray=array(
                    'errors'=>'This e-mail address is already registered with us.',
                    'data'=>$data,
                    'status'=>'inputError'
                );
                return $returnArray;
            }else{
                $insertUser=array(
                    'username'=> strtolower($data['inputEmail']),
                    'password'=> sha1($data['inputPassword']),
                    'currency'=>$data['inputCurrency'],
                    'status'=>'0'
                );
                $this->db->insert('advLogin',$insertUser);
                $customerId=$this->db->insert_id();
                $insertInfo=array(
                    'advKeyInfo'=>$customerId,
                    'firstname'=> $data['inputFirstname'],
                    'lastname'=> $data['inputLastname'],
                    'company'=> $data['inputCompany'],
                    'designation'=> $data['inputDesignation'],
                    'email'=> strtolower($data['inputEmail']),
                    'phone'=> $data['inputPhone'],
                    'website'=> $data['inputWebsite'],
                    'address'=> $data['inputPostalZip'].'$~$'.$data['inputCountry'].'$~$'.$data['inputCity'].'$~$'.$data['inputStreetApp']
                );
                $status=$this->db->insert('advInfo',$insertInfo);
                if($status){
                    $returnArray=array(
                        'data'=>$data,
                        'status'=>'dataInserted'
                    );
                    return $returnArray;
                }else{
                    $returnArray=array(
                        'data'=>$data,
                        'status'=>'dataInsertionProblem'
                    );
                    return $returnArray;
                }
            }
        }
    }


    public function checkEmail($email){
        $this->db->where('username',strtolower($email));
        $query=$this->db->get('advLogin',1);
        $result=$query->result_array();
        if(!empty($result)){
            return true;
        }else{
            return false;
        }
    }

    public function sendSignupMail($data){
        $this->load->library('encrypt');
        $encryptedEmail=urlencode($this->encrypt->encode(strtolower($data['inputEmail'])));
        $verificationLink=site_url('advertiser/index/verifyUser').'/'.$encryptedEmail;

        $message="Dear,".ucfirst(strtolower($data['inputFirstname'].' '.$data['inputLastname']))." \r\n";
        $message.="Thank you for choosing Adsonance.com \r\n \r\n";
        $message.="Click on link below to verify your Adsonance.com Account \r\n";
        $message.=$verificationLink;
        $message.=" \n\n For any query related to account contact us at: \r\nE-Mail: support@adsonance.com \r\nPhone: +91-9810344604 \r\nWebsite: http://www.adsonance.com";

        $this->load->library('email');
        $this->email->from('support@adsonance.com', 'Adsonance.com');
        $this->email->reply_to('support@adsonance.com', 'Adsonance.com');
        $this->email->to($data['inputEmail']);
        $this->email->subject('Confirm your Adsonance.com account');
        $this->email->message($message);
        $result=$this->email->send();
        if($result){
            return true;
        }else{
            $this->setEmailCannotBeSend($data);
            return false;
        }
    }

    public function verifyUser($encodedUrlVerificationCode=null){
        $this->load->library('encrypt');
        $verificationCode=urldecode($encodedUrlVerificationCode);
        $Email=$this->encrypt->decode($verificationCode);
        if($Email){
            $this->db->where('username',$Email);
            $query=$this->db->get('advLogin');
            $result=$query->result_array();
            if(!empty($result)){
                if($result['0']['status']==0){
                    $updateArray=array(
                        'status'=>'1'
                    );
                    $this->db->where('username',$Email);
                    $updateStatus=$this->db->update('advLogin',$updateArray);
                    if($updateStatus){
                        mail('sakhtar0092@gmail.com','New verified user at Adsonance','A new user having email '.$Email.' has registered and verified on adsonance.com');
                        $this->session->set_flashdata('notification', "<strong>Hello! </strong> Your accout is verified.Now you can Login.");
                        $this->session->set_flashdata('alertType', 'alert-success');
                        redirect('/advertiser/index/login/', 'refresh');
                    }
                }
            }
        }
        return false;
    }


    public function setEmailCannotBeSend($data){
        $updateArray=array(
            'status'=>'Problem Sending E-mail'
        );
        $this->db->where('username',strtolower($data['inputEmail']));
        $this->db->update('advLogin',$updateArray);
    }



    public function login($data){
        $this->db->where('username',$data['username']);
        $this->db->where('password',sha1($data['password']));
        $this->db->where('status !=',0);
        $query=$this->db->get('advLogin',1);
        return $query->result_array();
    }


    public function sendPasswordResetEmail($email){
        $this->db->where('username',strtolower($email));
        $query=$this->db->get('advLogin');
        $result=$query->result_array();
        if(!empty($result)){
            $this->sendResetMail($email);
            $this->session->set_flashdata('notification', "<strong>Password reset link send! </strong> Open password reset link send to you by Email to proceed further.");
            $this->session->set_flashdata('alertType', 'alert-success');
            redirect('/advertiser/index/resetPassword/', 'refresh');
        }else{
            $this->session->set_flashdata('notification', "<strong>Sorry! </strong> This email is not registered with us.");
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/advertiser/index/resetPassword/', 'refresh');
        }

    }


    public function sendResetMail($email){
        $this->load->library('encrypt');
        $encryptedEmail=urlencode($this->encrypt->encode(strtolower($email)));
        $verificationLink= site_url('advertiser/index/resetPassword').'/'.$encryptedEmail;

        $message="Hello, \r\n";
        $message.="You requested to reset your account Password on Adsonance.com \r\n \r\n";
        $message.="Click on link below to reset your Account Password \r\n";
        $message.=$verificationLink;
        $message.=" \n\n If you haven't requested for Password reset then just ignore this message. \r\n";
        $message.=" \n\n For any query related to account contact us at: \r\nE-Mail: support@adsonance.com \r\nPhone: +91-9810344604 \r\nWebsite: http://www.adsonance.com";

        $this->load->library('email');
        $this->email->from('admin@adsonance.com', 'Adsonance.com Password');
        $this->email->reply_to('admin@adsonance.com', 'Adsonance.com');
        $this->email->to($email);
        $this->email->subject('Password reset detail Adsonance.com account');
        $this->email->message($message);
        $result=$this->email->send();
        if($result){
            return true;
        }else{
            return false;
        }
    }


    public function checkPasswordResetLink($resetCode){
        $this->load->library('encrypt');
        $verificationCode=urldecode($resetCode);
        $Email=$this->encrypt->decode($verificationCode);
        if($Email){
            $this->db->where('username',$Email);
            $query=$this->db->get('advLogin');
            $result=$query->result_array();
            if(!empty($result)){
                return true;
            }else{
                return false;
            }
        }
    }

    public function updatePassword($resetCode,$data){
        if($data['inputNewPassword']==$data['inputVerifyPassword']){
            if(mb_strlen($data['inputNewPassword'])<8){
                $this->session->set_flashdata('notification', "<strong>Sorry! </strong> Password must have at least of 8 characters");
                $this->session->set_flashdata('alertType', 'alert-error');
                redirect('/advertiser/index/resetPassword/'.'/'.$resetCode, 'refresh');
            }else{
                $this->load->library('encrypt');
                $verificationCode=urldecode($resetCode);
                $Email=$this->encrypt->decode($verificationCode);

                $updateArray=array(
                    'password'=>sha1($data['inputNewPassword'])
                );
                $this->db->where('username',$Email);
                $this->db->update('advLogin',$updateArray);
                $this->sendPasswordUpdateEmail($Email);

                $this->session->set_flashdata('notification', "<strong>Success! </strong> Password Updated Succesfully");
                $this->session->set_flashdata('alertType', 'alert-success');
                redirect('/advertiser/index/resetPassword/'.'/'.$resetCode, 'refresh');
            }
        }else{
            $this->session->set_flashdata('notification', "<strong>Sorry! </strong> Password Doesn't Match");
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('/advertiser/index/resetPassword/'.'/'.$resetCode, 'refresh');
        }
    }

    public function sendPasswordUpdateEmail($email){
        $message="Hello, \r\n";
        $message.="You Password has been successfully updated on Adsonance.com \r\n \r\n";
        $message.="If you have not updated your Password then contact immediately at support@adsonance.com \r\n";
        $message.=" \n\n For any query related to account contact us at: \r\nE-Mail: support@adsonance.com \r\nPhone: +91-9810344604 \r\nWebsite: http://www.adsonance.com";

        $this->load->library('email');
        $this->email->from('admin@adsonance.com', 'Adsonance.com Password');
        $this->email->reply_to('admin@adsonance.com', 'Adsonance.com');
        $this->email->to($email);
        $this->email->subject('Password Updated Succesfully Adsonance.com');
        $this->email->message($message);
        $result=$this->email->send();
        if($result){
            return true;
        }else{
            return false;
        }
    }

}
