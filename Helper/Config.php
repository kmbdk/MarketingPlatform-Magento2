<?php

namespace EP\Emailplatform\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface as ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper {
    
    protected $_configWriter;

    protected $_scopeConfig;
    /**
     * Get module settings
     *
     * @param $key
     * @return mixed
     */
    
    public function __construct(
        \Magento\Framework\App\Config\Storage\WriterInterface $configWriter,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_configWriter = $configWriter;
        $this->_scopeConfig = $scopeConfig;
    }
    
    public function getConfigGeneral($key) {
        return $this->_scopeConfig->getValue(
            'ep_emailplatform/general/' . $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE 
        );
    }

    public function getConfigSubscribe($key) {
        return $this->_scopeConfig->getValue(
            'ep_emailplatform/subscribe/' . $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE 
        );
    }

    public function getConfigCheckout($key) {
        return $this->_scopeConfig->getValue(
            'ep_emailplatform/checkout/' . $key, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    public function setConfigGeneral($key, $value)
    {
        $this->_configWriter->save('ep_emailplatform/general/'.$key,  $value, ScopeInterface::SCOPE_TYPE_DEFAULT, 0);
    }
    
    public function setConfigSubscribe($key, $value)
    {
        $this->_configWriter->save('ep_emailplatform/subscribe/'.$key,  $value, ScopeInterface::SCOPE_TYPE_DEFAULT, 0);
    }
    
    public function setConfigCheckout($key, $value)
    {
        $this->_configWriter->save('ep_emailplatform/checkout/'.$key,  $value, ScopeInterface::SCOPE_TYPE_DEFAULT, 0);
    }

}
