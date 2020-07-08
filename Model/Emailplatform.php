<?php

namespace EMP\Emailplatform\Model;

use EMP\Emailplatform\Helper\Config as Helper;
use \Magento\Framework\HTTP\Client\Curl;
use EMP\Emailplatform\Logger\Logger;

class Emailplatform {

    protected $URL = 'https://api.mailmailmail.net/v1.1';
    protected $api_username;
    protected $api_token;
    
    protected $_helper;
    protected $_emailplatformlists;
    protected $_emailplatformfields;
    protected $_curl;
    protected $_logger;

    public function __construct(Helper $helper, Curl $curl, Logger $logger) {
        
        $this->_helper = $helper;
        $this->_curl = $curl;
        $this->_logger = $logger;
        
        $this->api_username = trim($this->_helper->getConfigGeneral('api_username'));
        $this->api_token = trim($this->_helper->getConfigGeneral('api_token'));
    }

    public function subscribe($email, $StoreId = null, $mobile = false, $firstname = '', $lastname = '') {

        $url = $this->URL . '/Subscribers/AddSubscriberToList';

        $double_optin = $this->_helper->getConfigSubscribe('double_optin');
        $listid = $this->_helper->getConfigSubscribe('listid', $StoreId);
        $mobile_subscribe = $this->_helper->getConfigSubscribe('mobile_subscribe');
        $firstname_fieldid = $this->_helper->getConfigSubscribe('firstname_fieldid');
        $lastname_fieldid = $this->_helper->getConfigSubscribe('lastname_fieldid');
        $mobile_prefix = $this->_helper->getConfigSubscribe('mobile_prefix');

        $add_to_autoresponders = true;
        $contactFields = array();

        if ($double_optin ? $confirm = false : $confirm = true);

        if ($firstname_fieldid != 0) {
            $contactFields[] = array(
                'fieldid' => $firstname_fieldid,
                'value' => $firstname
            );
        }
        if ($lastname_fieldid != 0) {
            $contactFields[] = array(
                'fieldid' => $lastname_fieldid,
                'value' => $lastname
            );
        }
        if ($mobile_subscribe == 0) {
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
    
    public function unsubscribe($email){
        
        $url = $this->URL . '/Subscribers/UnsubscribeSubscriberEmail';
        $listid = $this->_helper->getConfigSubscribe('listid');
        
        $params = array(
            'listid' => $listid,
            'emailaddress' => $email
        );
        
        return $this->MakePostRequest($url, $params);
        
    }

    public function GetEmailplatformLists() {

        if (!empty($this->_emailplatformlists)) {
            return $this->_emailplatformlists;
        }

        $url = $this->URL . '/Users/GetLists';
        $params = array();

        $this->_emailplatformlists = $this->MakeGetRequest($url, $params);

        return $this->_emailplatformlists;
    }

    public function GetEmailplatformFields() {

        if (!empty($this->_emailplatformfields)) {
            return $this->_emailplatformfields;
        }

        $url = $this->URL . '/Lists/GetCustomFields';
        $listid = $this->_helper->getConfigSubscribe('listid');

        if ($listid == 0) {
            return false;
        }

        $params = array(
            'listids' => $listid
        );

        $this->_emailplatformfields = $this->MakeGetRequest($url, $params);

        return $this->_emailplatformfields;
    }

    public function CheckApiCredentials() {
        $url = $this->URL . '/Test/TestUserToken';
        return $this->MakePostRequest($url);
    }

    private function DecodeResult($input = '') {
        return json_decode($input, TRUE);
    }

    public function MakeGetRequest($url = "", $fields = array()) {
        try {
            
            if (!empty($fields)) {
                $url .= "?" . http_build_query($fields, '', '&');
            }
            
            // Magento curl request
            $this->_curl->addHeader('Accept', 'application/json; charset=utf-8');
            $this->_curl->addHeader('ApiUsername', $this->api_username);
            $this->_curl->addHeader('ApiToken', $this->api_token);
            $this->_curl->get($url);
            
            return $this->DecodeResult($this->_curl->getBody());
            
        } catch (\Exception $e) {
            $this->_logger->info('Error Curl',['exception' => $e]);
            return 'Curl exception error';
        }
        
    }

    public function MakePostRequest($url = "", $fields = array()) {
        try {
                        
            $this->_curl->addHeader('Accept', 'application/json; charset=utf-8');
            $this->_curl->addHeader('ApiUsername', $this->api_username);
            $this->_curl->addHeader('ApiToken', $this->api_token);
            $this->_curl->post($url, $fields);
            
            return $this->DecodeResult($this->_curl->getBody());
            
            
        } catch (\Exception $e) {
            $this->_logger->info('Error Curl',['exception' => $e]);
            return 'Curl exception error';
        }
        
    }

}
