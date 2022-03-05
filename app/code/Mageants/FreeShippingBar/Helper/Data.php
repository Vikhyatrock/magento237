<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Helper;

use Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    public $backendUrl;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        FreeShippingBar $freeshippingbar,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        $this->freeshippingbar = $freeshippingbar;
        $this->_storeManager = $storeManager;
        $this->backendUrl=$backendUrl;
        parent::__construct($context);
    }
    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * @return bool|string
     */
    public function getConfig($config_path)
    {
        $storeCode=$this->getStoreCode();
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }

    public function isFreeShippingBarEnable()
    {
        return $this->getConfig('freeshippingbar/general/enable');
    }
    public function displayLocation()
    {
        $data = $this->freeshippingbar->getCollections();
        foreach ($data as $collection) {
            $position =  $collection->getPosition();
        }
        if ($position == 0) {
            return "-";
        }
    }

    public function getProductsGridUrl()
    {
         return $this->backendUrl->getUrl('freeshippingbar/backend/products', ['_current' => true]);
    }
}
