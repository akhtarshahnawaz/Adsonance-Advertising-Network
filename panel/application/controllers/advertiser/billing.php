<?php

class Billing extends CI_Controller
{
    function __construct(){
        parent::__construct();
    }

    public function index(){
        if($this->session->userdata('loggedIn')){
            $data=$this->input->post();
            $this->load->model('advertiser/mspend');
            if($data && $data['daterange']){
                $data=explode(' - ',$data['daterange']);
                $this->load->model('advertiser/mbilling');
                $data['billingSummary']=$this->mbilling->billingSummary($data[0],$data[1]);
                $data['range']=$data[0].' - '.$data[1];
                $data['balances']=$this->mspend->balances($this->session->userdata('key'));
                $data['spendGraphData']=$this->mspend->dailySpendGraphData();
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/index',$data);
                $this->load->view('advertiser/structs/footer');
            }else{
                $to=dateToday();
                $from=explode('/',$to);
                $from=$from[0].'/01/'.$from[2];
                $this->load->model('advertiser/mbilling');
                $data['billingSummary']=$this->mbilling->billingSummary($from,$to);
                $data['range']=$from.' - '.$to;
                $data['balances']=$this->mspend->balances($this->session->userdata('key'));
                $data['spendGraphData']=$this->mspend->dailySpendGraphData();
                if($this->session->flashdata('notification')){
                    $data['alertType']=$this->session->flashdata('alertType');
                    $data['notification']=$this->session->flashdata('notification');
                }
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/index',$data);
                $this->load->view('advertiser/structs/footer');
            }        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function addfund(){
        if($this->session->userdata('loggedIn')){

            $data=$this->input->post();
            if($data){
                switch($data['pay_method']){
                    case 'paypal':
                        redirect('advertiser/billing/paypal');
                        break;

                    case 'wireTransfer':
                        redirect('advertiser/billing/wireTransfer');
                        break;

                    case 'ccavenue':
                        redirect('advertiser/billing/ccavenue');
                        break;

                    default:
                        echo '';
                }


            }else{
                $data['currency']=$this->session->userdata('currency');
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/selectPayMethod',$data);
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function paypal_notification(){
        // PayPal settings
        $paypal_email = $this->config->item('paypal_email');
        $return_url = 'https://adsonance.com/panel/index.php/advertiser/billing/paypal_message/success';
        $cancel_url = 'https://adsonance.com/panel/index.php/advertiser/billing/paypal_message/cancelled';
        $notify_url = 'https://adsonance.com/panel/index.php/advertiser/billing/paypal_notification';
        $PostData=$this->input->post();

// Check if paypal request or response
        if (!isset($PostData["txn_id"]) && !isset($PostData["txn_type"])){

// Firstly Append paypal account to querystring
            $querystring= "?business=".urlencode($paypal_email)."&";


//loop for posted values and append to querystring
            foreach($PostData as $key => $value){
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }

// Append paypal return addresses
            $querystring .= "return=".urlencode(stripslashes($return_url))."&";
            $querystring .= "cancel_return=".urlencode(stripslashes($cancel_url))."&";
            $querystring .= "notify_url=".urlencode($notify_url);

// Append querystring with custom field
            $querystring .= "&custom=".$this->session->userdata('key');

// Redirect to paypal IPN
            header('location:https://www.paypal.com/cgi-bin/webscr'.$querystring);
            exit();

        }else{
// Response from PayPal
            $req = 'cmd=_notify-validate';
            foreach ($PostData as $key => $value) {
                $value = urlencode(stripslashes($value));
                $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
                $req .= "&$key=$value";
            }
            $data['item_name']			= $PostData['item_name'];
            $data['item_number'] 		= $PostData['item_number'];
            $data['payment_status'] 	= $PostData['payment_status'];
            $data['payment_amount'] 	= $PostData['mc_gross'];
            $data['payment_currency']	= $PostData['mc_currency'];
            $data['txn_id']				= $PostData['txn_id'];
            $data['receiver_email'] 	= $PostData['receiver_email'];
            $data['payer_email'] 		= $PostData['payer_email'];
            $data['custom']             = $PostData['custom'];

            $valid=file_get_contents('https://www.paypal.com/cgi-bin/webscr?'.$req);
            if (!$valid) {
            // HTTP ERROR
            } else {
                if (strcmp($valid, "VERIFIED") == 0) {
            // Validate payment (Check unique txnid & correct price)
                    $this->load->model('advertiser/mbilling');
                    $valid_txnid=$this->mbilling->checkPaypalTransId($data['txn_id']);
            // PAYMENT VALIDATED & VERIFIED!
                    if($valid_txnid){
                        $orderid=$this->mbilling->addPaypalPayment($data);
                        if($orderid){
                            mail('sakhtar0092@gmail.com','Fund added to Paypal from Adsonance',"Your Adsonance Paypal Account has been funded with ".$data['payment_amount'].$data['payment_currency']." having Payment Id: ".$orderid." and transaction ID: ".$data['txn_id']." and funded by ".$data['payer_email']);
                            //'Payment has been made & successfully inserted into the Database'
                        }else{
                            mail('sakhtar0092@gmail.com','Adsonance Paypal: Transaction details database insertion error',"Error occured while adding transaction details to database for transaction ID: ".$data['txn_id']." && Payer's Email: ".$data['payer_email']);
                            // Error inserting into DB
                        }
                    }else{
                        mail('sakhtar0092@gmail.com','Adsonance Paypal error',"Some error in paypal payment");
                        // Payment made but data has been changed'
                    }
                }else if (strcmp($valid, "INVALID") == 0) {
                    mail('sakhtar0092@gmail.com','Adsonance Paypal error',"Some error in paypal payment : Getting INVALID response on re-validation of transaction data");
                    // Invalid Payment- E-mail admin or alert user
                }
            }
        }
    }




    public function paypal(){
        if($this->session->userdata('loggedIn')){
            $this->load->model('advertiser/mbilling');
            $data['userInfo']=$this->mbilling->getUser();
            $data['currency']=$this->session->userdata('currency');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/structs/header');
            $this->load->view('advertiser/billing/paypal',$data);
            $this->load->view('advertiser/structs/footer');
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }


    public function paypal_message($message){
        if($message=='success'){
            $this->session->set_flashdata('notification', '<strong>Success!</strong> Your account has been funded successfully');
            $this->session->set_flashdata('alertType', 'alert-success');
        }elseif($message=='cancelled'){
            $this->session->set_flashdata('notification', '<strong>Cancelled!</strong> Cancelled paypal account funding');
            $this->session->set_flashdata('alertType', 'alert-error');
        }
        redirect('advertiser/index');

    }

    public function wireTransfer(){
        if($this->session->userdata('loggedIn')){
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/structs/header');
            $this->load->view('advertiser/billing/wireTransfer');
            $this->load->view('advertiser/structs/footer');
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }

    public function ccavenue(){
        if($this->session->userdata('loggedIn')){
            $data=$this->input->post();
            if($data['amount']){
                $this->load->model('advertiser/mbilling');
                $data['userInfo']=$this->mbilling->getUser();
                $data['amount']=$data['amount'];
                $data['currency']=$this->session->userdata('currency');
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/ccavenue',$data);
                $this->load->view('advertiser/structs/footer');
            }else{
                $data['currency']=$this->session->userdata('currency');
                $this->load->view('advertiser/structs/head');
                $this->load->view('advertiser/structs/header');
                $this->load->view('advertiser/billing/amount',$data);
                $this->load->view('advertiser/structs/footer');
            }
        }else{
            $data['status']=array('state'=>false,'data'=>'Not Logged In!');
            $this->load->view('advertiser/structs/head');
            $this->load->view('advertiser/main/login',$data);
            $this->load->view('advertiser/structs/footer');
        }
    }

    public function ccavenue_notification(){
        $this->load->helper('ccavenueLibrary');
        $this->config->load('ccavenue');

        $WorkingKey = $this->config->item('workingKey');
        $Merchant_Id= $_REQUEST['Merchant_Id'];
        $Amount= $_REQUEST['Amount'];
        $Order_Id= $_REQUEST['Order_Id'];
        $Merchant_Param= $_REQUEST['Merchant_Param'];
        $Checksum= $_REQUEST['Checksum'];
        $AuthDesc=$_REQUEST['AuthDesc'];

        $Checksum = verifyChecksum($Merchant_Id, $Order_Id , $Amount,$AuthDesc,$Checksum,$WorkingKey);
        $userInfo=explode('@@@',$Order_Id);

        $this->load->model('advertiser/mbilling');

        $data['custom']=$Merchant_Param;
        $data['payment_amount']=$Amount;
        $data['txn_id']=$Order_Id;



        if($Checksum=="true" && $AuthDesc=="Y")
        {
            $data['payment_status']='SUCCESS';
            $insertId=$this->mbilling->addPaypalPayment($data);
            mail('sakhtar0092@gmail.com','Fund added to CCAvenue from Adsonance status: SUCCESS',"Your Adsonance CCAvenue Account has been funded with ".$Amount." INR having Email Id: ".$userInfo[1]."with insert ID: ".$insertId);

            $this->session->set_flashdata('notification', '<strong>Successful!</strong> Transaction successfull. Funds are succesfully added to your account.');
            $this->session->set_flashdata('alertType', 'alert-success');
            redirect('advertiser/index');

        }
        else if($Checksum=="true" && $AuthDesc=="B")
        {
            $data['payment_status']='PENDING';
            $insertId=$this->mbilling->addPaypalPayment($data);
            mail('sakhtar0092@gmail.com','Fund added to CCAvenue from Adsonance status: PENDING',"Your Adsonance CCAvenue Account has been funded with ".$Amount." INR having Email Id: ".$userInfo[1]."with insert ID: ".$insertId." but status is pending.");

            $this->session->set_flashdata('notification', '<strong>Successful!</strong> Transaction queued. Funds will be added to your account as soon as payment processor processes your payment.');
            $this->session->set_flashdata('alertType', 'alert-success');
            redirect('advertiser/index');
        }
        else if($Checksum=="true" && $AuthDesc=="N")
        {
            $this->session->set_flashdata('notification', '<strong>Cancelled!</strong> Transaction declined.');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('advertiser/index');
        }
        else
        {
            $this->session->set_flashdata('notification', '<strong>Cancelled!</strong> Security Error. Illegal access detected');
            $this->session->set_flashdata('alertType', 'alert-error');
            redirect('advertiser/index');
        }
    }

}