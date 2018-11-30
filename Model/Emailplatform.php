<?php

namespace EMP\Emailplatform\Model;

use EMP\Emailplatform\Helper\Config as Helper;

class Emailplatform {

    protected $URL = 'https://api.mailmailmail.net/v1.1';
    
    protected $_helper;
    protected $_emailplatformlists;
    protected $_emailplatformfields;
    
    public function __construct(Helper $helper) {
        $this->_helper = $helper;
    }

    public function subscribe($email, $mobile = false, $firstname = '', $lastname = '') {
        
        $url = $this->URL.'/Subscribers/AddSubscriberToList';
        
        $double_optin = $this->_helper->getConfigSubscribe('double_optin');
        $listid = $this->_helper->getConfigSubscribe('listid');
        $mobile_subscribe = $this->_helper->getConfigSubscribe('mobile_subscribe');
        $firstname_fieldid = $this->_helper->getConfigSubscribe('firstname_fieldid');
        $lastname_fieldid = $this->_helper->getConfigSubscribe('lastname_fieldid');
        $mobile_prefix = $this->_helper->getConfigSubscribe('mobile_prefix');
        
        $add_to_autoresponders = true;
        $contactFields = array();
        
        if($double_optin ? $confirm = false : $confirm = true);
        
        if($firstname_fieldid != 0){
            $contactFields[] = array(
                'fieldid' => $firstname_fieldid,
                'value' => $firstname
            );
        }
        if($lastname_fieldid != 0){
            $contactFields[] = array(
                'fieldid' => $lastname_fieldid,
                'value' => $lastname
            );
        }
        if($mobile_subscribe == 0){
            $mobile_prefix == false;
            $mobile = false;
        }

        $params = array(
            'listid' => $listid,
            'emailaddress' => $email,
            'mobile' => $mobile,
            'mobilePrefix' => $mobile_prefix,
            'contactFields' => $contactFields,
            'add_to_autoresponders' => $add_to_autoresponders,
            'skip_listcheck' => false,
            'confirmed' => $confirm
        );
        
        return $this->MakePostRequest($url, $params);
        
        
    }

    public function GetEmailplatformLists() {

        if (!empty($this->_emailplatformlists)) {
            return $this->_emailplatformlists;
        }

        $url = $this->URL.'/Users/GetLists';
        $params = array();

        $this->_emailplatformlists = $this->MakeGetRequest($url, $params);

        return $this->_emailplatformlists;
    }

    public function GetEmailplatformFields() {

        if (!empty($this->_emailplatformfields)) {
            return $this->_emailplatformfields;
        }

        $url = $this->URL.'/Lists/GetCustomFields';
        $listid = $this->_helper->getConfigSubscribe('listid');
        
        if($listid == 0){
            return false;
        }
        
        $params = array(
            'listids' => $listid
        );

        $this->_emailplatformfields = $this->MakeGetRequest($url, $params);

        return $this->_emailplatformfields;
    }

    public function CheckApiCredentials() {
        $url = $this->URL.'/Test/TestUserToken';
        return $this->MakePostRequest($url);
    }

    private function GetHTTPHeader() {

        $username = $this->_helper->getConfigGeneral('api_username');
        $token = $this->_helper->getConfigGeneral('api_token');

        return array(
            "Accept: application/json; charset=utf-8",
            "ApiUsername: " . trim($username),
            "ApiToken: " . trim($token)
        );
    }

    private function DecodeResult($input = '') {
        return json_decode($input, TRUE);
    }

    public function MakeGetRequest($url = "", $fields = array()) {
        // open connection
        $ch = curl_init();
        if (!empty($fields)) {
            $url .= "?" . http_build_query($fields, '', '&');
        }
        // set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->GetHTTPHeader());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // disable for security
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        // execute post
        $result = curl_exec($ch);

        // close connection
        curl_close($ch);
        // return $result;
        return $this->DecodeResult($result);
    }

    public function MakePostRequest($url = "", $fields = array()) {
        try {

            // open connection
            $ch = curl_init();

            // add the setting to the fields
            // $data = array_merge($fields, $this->settings);
            $encodedData = http_build_query($fields, '', '&');

            // set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->GetHTTPHeader());
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
            // disable for security
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            // execute post
            $result = curl_exec($ch);

            // close connection
            curl_close($ch);

            return $this->DecodeResult($result);
        } catch (Exception $error) {
            return $error->GetMessage();
        }
    }

}
