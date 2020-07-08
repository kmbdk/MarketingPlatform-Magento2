<?php

namespace EMP\Emailplatform\Observer;

use Magento\Framework\Event\ObserverInterface;
use EMP\Emailplatform\Logger\Logger;
use Magento\Newsletter\Model\Subscriber;
use EMP\Emailplatform\Helper\Config as Helper;
use EMP\Emailplatform\Model\Emailplatform;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Request\DataPersistorInterface;

class NewsletterSubscribe implements ObserverInterface {

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

        if(!$this->_dataPersistor->get('newsletter_checkout_subscribe')){
            
            $subscriber_status = $observer->getEvent()->getSubscriber()->getSubscriberStatus();
            $email = $observer->getEvent()->getSubscriber()->getSubscriberEmail();
            $StoreId = $observer->getEvent()->getSubscriber()->getStoreId();
            
            $this->_logger->info("Status: ".STATUS_SUBSCRIBED);
            
            if($subscriber_status != 3){
                
                $request = $this->_emailplatform->subscribe($email, $StoreId);
            
                if(is_int($request) && $request != 0){
                    $this->_logger->info("Subscriber $email was added to eMailPlatform - SubscriberID: $request");
                } else {
                    $this->_logger->info('Error: '.$request);
                }
                
            } else {
                
                $request = $this->_emailplatform->unsubscribe($email);
            
                if($request){
                    $this->_logger->info("Subscriber $email was unsubscribed from eMailPlatform");
                } else {
                    $this->_logger->info('Error: '.$request);
                }
                
            }
            
        } else {
            
            $this->_dataPersistor->clear('newsletter_checkout_subscribe');
            
        }

        return $this;
        
    }

}
