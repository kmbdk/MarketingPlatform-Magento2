<?php

namespace EMP\Emailplatform\Model\Config;

use EMP\Emailplatform\Model\Emailplatform as Emailplatform;
use Magento\Store\Model\StoreManagerInterface as StoreManagerInterface;
use EMP\Emailplatform\Logger\Logger;

class Lists implements \Magento\Framework\Option\ArrayInterface {

    protected $_storeManager;
    protected $_emailplatform;
    protected $_logger;
    
    protected $_lists = array();
    protected $_options;

    public function __construct(Emailplatform $emailplatform, StoreManagerInterface $storemanagerinterface, Logger $loggerinterface) {
        $this->_emailplatform = $emailplatform;
        $this->_storeManager = $storemanagerinterface;
        $this->_logger = $loggerinterface;
    }

    public function toOptionArray($isMultiselect = false) {
        
        if (!$this->_options) {
            
            // set current store
            $StoreID = $this->_storeManager->getStore()->getId();
            $this->_storeManager->setCurrentStore($StoreID);
            
            // Get lists from eMailPlatform by Model
            $result = $this->_emailplatform->GetEmailplatformLists();
                        
            if (!is_array($result) OR empty($result)) {
                $this->_logger->info($result.' or no lists in eMailPlatform');
            } else {
                foreach ($result as $item) {
                    $this->_lists[] = array(
                        'value' => $item['listid'],
                        'label' => $item['name']
                    );
                }
            }
            
            $this->_options = $this->_lists;
            
        }
        
        $options = $this->_options;
        if (!$isMultiselect) {
            array_unshift($options, array(
                'value' => '',
                'label' => __('--Please Select--')
            ));
        }
        
        $this->_storeManager->setCurrentStore(0);
        
        return $options;
        
    }

}
