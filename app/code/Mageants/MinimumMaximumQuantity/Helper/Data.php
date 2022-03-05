<?php
namespace Mageants\MinimumMaximumQuantity\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

     public function __construct(
       \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
       \Magento\Store\Model\StoreManagerInterface $storeManager
     )
     {
        $this->scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
     }
  
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getConfigValue($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()       
        );
    }

    public function isExtensionEnable()
    {

  		  return $this->getConfigValue('quantityselect/general/enable');
    }

    public function isMinimumQuantity(){

   		 return $this->getConfigValue('quantityselect/general/display_min_quantity');
    }

    public function isMaximumQuantity(){

   	  return $this->getConfigValue('quantityselect/general/display_max_quantity');
   }
}