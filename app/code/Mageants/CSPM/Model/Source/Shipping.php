<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model\Source;
use Magento\Framework\Option\ArrayInterface;

class Shipping implements ArrayInterface
{
    /**
     * website
     *
     * @var \Magento\Store\Model\Store
     */
    protected $_website;

    /**
     * @param \Magento\Store\Model\Store $store
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Config $shipconfig
    ){     
          $this->shipconfig=$shipconfig;
            $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return Array
     */  
    public function toOptionArray()
    {
                $activeCarriers = $this->shipconfig->getActiveCarriers();
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                foreach($activeCarriers as $carrierCode => $carrierModel)
                 {
                       $options = array();
                       if( $carrierMethods = $carrierModel->getAllowedMethods() )
                       {
                           foreach ($carrierMethods as $methodCode => $method)
                           {
                                
                                 $methods[]=array('value'=>$carrierCode,'label'=>$methodCode);
                           }
                       }
                }
               return $methods;        

    }
}