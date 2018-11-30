<?php

namespace EP\Emailplatform\Observer;

use Magento\Framework\Event\ObserverInterface;
use EP\Emailplatform\Logger\Logger;
use Magento\Newsletter\Model\Subscriber;
use EP\Emailplatform\Helper\Config as Helper;
use EP\Emailplatform\Model\Emailplatform;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

class SubscribeOnCheckout implements ObserverInterface {

    
    protected $_subscriber;

    protected $_helper;

    protected $_logger;
    
    protected $_emailplatform;
    
    protected $_messageManager;
    
    protected $_dataPersistor;
        
    /**
     * @param Subscriber $subscriber
     * @param Helper $helper
     * @param LoggerInterface $logger
     */
    public function __construct(Subscriber $subscriber, Helper $helper, Logger $logger, Emailplatform $emailplatform, ManagerInterface $messageManager, DataPersistorInterface $dataPersistor) {
        $this->_subscriber = $subscriber;
        $this->_helper = $helper;
        $this->_logger = $logger;
        $this->_emailplatform = $emailplatform;
        $this->_messageManager = $messageManager;
        $this->_dataPersistor = $dataPersistor;
    }

    public function execute(\Magento\Framework\Event\Observer $observer) {
        
        if ($this->_helper->getConfigGeneral('enabled')) {
            
            // Set dataPersistor to later catch if checkout subscription already ran
            $this->_dataPersistor->set('newsletter_checkout_subscribe', true);
            
            $quote = $observer->getQuote();
            
            if ($quote->getNewsletterSubscribe()) {
                $email = 'undefined';
                try {
                    
                    $email = $quote->getCustomerEmail();
                    $firstname = $quote->getBillingAddress()->getFirstname();
                    $lastname = $quote->getBillingAddress()->getLastname();
                    $mobile = $quote->getBillingAddress()->getTelephone();
                    
                    if($this->_subscriber->subscribe($email)){
                    
                        $request = $this->_emailplatform->subscribe($email, $mobile, $firstname, $lastname);
                        
                        if(is_int($request)){
                            $this->_logger->info('Subscriber '.$email.' was succesful added to eMailPlatform - SubscriberID: '.$request);
                        } else {
                            $this->_logger->info('Error: '.$request);
                        }
                        
                    }
                    
                } catch (\Exception $e) {
                    $this->_logger->error($e->getMessage() . 'to ' . $email);
                }
            }
        }
        
        return $this;
        
    }

}
