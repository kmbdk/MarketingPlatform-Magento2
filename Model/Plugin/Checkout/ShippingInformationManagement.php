<?php

namespace EMP\Emailplatform\Model\Plugin\Checkout;

use Magento\Quote\Model\QuoteRepository;
use Magento\Checkout\Model\ShippingInformationManagement as ShippingManagement;
use Magento\Checkout\Api\Data\ShippingInformationInterface;
use EMP\Emailplatform\Helper\Config as Helper;

class ShippingInformationManagement {

    protected $_helper;
    protected $_quoteRepository;

    public function __construct(
    QuoteRepository $quoteRepository, Helper $helper
    ) {
        $this->_quoteRepository = $quoteRepository;
        $this->_helper = $helper;
    }

    public function beforeSaveAddressInformation(ShippingManagement $subject, $cartId, ShippingInformationInterface $addressInformation) {
        
        $newsletterSubscribe = 0;
        
        if ($this->_helper->getConfigGeneral('enabled')) {
            
            if($this->_helper->getConfigCheckout('active')){

                if (($extAttributes = $addressInformation->getExtensionAttributes()) && $extAttributes->getNewsletterSubscribe()){
                    $newsletterSubscribe = 1;
                }
            
            }

            $quote = $this->_quoteRepository->getActive($cartId);
            $quote->setNewsletterSubscribe($newsletterSubscribe);
        }
        
    }

}
