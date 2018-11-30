<?php

namespace EMP\Emailplatform\Observer;

use Magento\Framework\Event\ObserverInterface;
use EMP\Emailplatform\Logger\Logger;
use EMP\Emailplatform\Helper\Config as Helper;
use EMP\Emailplatform\Model\Emailplatform;
use Magento\Framework\Message\ManagerInterface;

class SaveConfig implements ObserverInterface {

    
    protected $_subscriber;

    protected $_helper;

    protected $_logger;
    
    protected $_emailplatform;
    
    protected $_messageManager;

    /**
     * @param Subscriber $subscriber
     * @param Helper $helper
     * @param LoggerInterface $logger
     */
    public function __construct(Helper $helper, Logger $logger, Emailplatform $emailplatform, ManagerInterface $messageManager) {
        $this->_helper = $helper;
        $this->_logger = $logger;
        $this->_emailplatform = $emailplatform;
        $this->_messageManager = $messageManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        
        $api_status = $this->_emailplatform->CheckApiCredentials();
        
        if($api_status !== true){
            $this->_messageManager->addError($api_status);
        } else {
            $this->_messageManager->addSuccess('Connection to eMailPlatform API was succesful.');
        }
        
        $listid = $this->_helper->getConfigSubscribe('listid');
        
        if($listid == 0){
            $this->_messageManager->addWarning('Please select list from eMailPlatform');
        }
        
        $mobile_subscribe = $this->_helper->getConfigSubscribe('mobile_subscribe');
        
        if($mobile_subscribe){
            
            $lists = $this->_emailplatform->GetEmailplatformLists();
            
            if(!empty($lists[$listid]['sms_prefix'])){
                $this->_helper->setConfigSubscribe('mobile_prefix', $lists[$listid]['sms_prefix']);
            } else {
                $this->_helper->setConfigSubscribe('mobile_subscribe', 0);
                $this->_helper->setConfigSubscribe('mobile_prefix', 0);
                $this->_messageManager->addWarning('Not able to activate mobile subscription: Please choose mobile prefix on the list inside eMailPlatform');
            }
            
        }
        
        return $this;
        
    }

}
