<?php


class Mindex extends CI_Model
{
    function __construct(){
        parent::__construct();
    }

    public function login($redirectURI=null){
        if(isset($redirectURI)){
            $redirect=$redirectURI;
        }else{
            $redirect=$this->config->item('Facebook-App-Url');
        }
        /*Setup API*/
        $user_FID = $this->facebook->getUser();

        /*if user has not authenticated already*/
        if (!$user_FID) {
            $loginUrl = $this->facebook->getLoginUrl(array(
                'scope' => $this->config->item('Facebook-Scope'),
                'redirect_uri' => $redirect
            ));
            print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
        }else{
            /*if user has already authenticated check if proper permissions are given*/
            /* $permissions_list = $this->facebook->api('/me/permissions');
        $permissions_needed = $this->config->item('Facebook-Permission-Needed');
        foreach($permissions_needed as $perm) {
            if( !isset($permissions_list['data'][0][$perm]) || $permissions_list['data'][0][$perm] != 1 ) {
                $loginUrl = $this->facebook->getLoginUrl(array(
                    'scope' => $this->config->item('Facebook-Scope'),
                    'redirect_uri' => $this->config->item('Facebook-App-Url'),
                ));
                print('<script> top.location.href=\'' . $loginUrl . '\'</script>');
            }}*/


            /*Check if user is stored in Database*/
            $user_ID=$this->get_UserID_by_FID($user_FID);
            if($user_ID==false){
                $user_FINFO=$this->facebook->api('/me?fields=first_name,last_name,email,website','GET');
                $this->addUser($user_FID,$user_FINFO);
                $sessiondata = array(
                    'user_FID' => $user_FID,
                    'user_ID'=>$user_ID,
                    'type' => 'publisher',
                    'access_token' => $this->facebook->getAccessToken(),
                    'totalFriends'=>$this->totalFriends(),
                    'PubloggedIn' => TRUE
                );
                $this->session->set_userdata($sessiondata);
                return true;
            }else{
                /*If Proper Permissions are given and user is stored in database then START SESSION - SET SESSION DATA and return true*/
                $sessiondata = array(
                    'user_FID' => $user_FID,
                    'user_ID'=>$user_ID,
                    'type' => 'publisher',
                    'access_token' => $this->facebook->getAccessToken(),
                    'totalFriends'=>$this->totalFriends(),
                    'PubloggedIn' => TRUE
                );
                $this->session->set_userdata($sessiondata);
                return true;
            }
        }
    }



    public function totalFriends(){
        $friendslist=$this->friendsList();
        return count($friendslist['data']);
    }


    public function friendsList(){
        $friends  = $this->facebook->api('/me/friends');
        return $friends;
    }


    function get_UserID_by_FID($user_FID){
        if($user_FID){
        $this->db->where('facebookId',$user_FID);
        $query=$this->db->get('pubLogin',1);
        $result=$query->result_array();
        if(!empty($result)){
            return $result[0]['pkey'];
        }else{
            return false;
        }
        }
    }



    public function addUser($userId,$userData){
        $data = array(
            'facebookId'=>$userId
        );
        $this->db->insert('pubLogin',$data);
        $insertID=$this->db->insert_id();

        if(isset($userData['first_name'])){
            $firstname=$userData['first_name'];
        }else{
            $firstname='';
        }
        if(isset($userData['last_name'])){
            $lastname=$userData['last_name'];
        }else{
            $lastname='';
        }
        if(isset($userData['email'])){
            $email=$userData['email'];
        }else{
            $email='';
        }
        if(isset($userData['website'])){
            $website=$userData['website'];
        }else{
            $website='';
        }

        $data = array(
            'pubKeyInfo'=>$insertID,
            'firstname' => $firstname ,
            'lastname' => $lastname ,
            'email' => $email,
            'phone' => '',
            'website' => $website,
            'address' => ''
        );
        $this->db->insert('pubInfo',$data);
    }

    public function getUser($userId){
        $this->db->where('facebookId',$userId);
        $query=$this->db->get('pubLogin');
        $data= $query->result_array();
        $this->db->where('pubKeyInfo',$data[0]['pkey']);
        $query=$this->db->get('pubInfo');
        return $query->result_array();
    }




}