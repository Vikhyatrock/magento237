<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Helper;

/**
 * Data class for Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    /**
     * scope config
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;
    
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * @return Boolean
     */

    public function priceScope()
    {
        return $this->_scopeConfig->getValue('catalog/price/scope', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
