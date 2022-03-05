<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Productimage\Block;

use Magento\Framework\View\Element\Template\Context;
use Mageants\Productimage\Model\GridFactory;
use Magento\Framework\UrlInterface;

class Productimage extends \Magento\Framework\View\Element\Template
{
    protected $helperData;
    public function __construct(
        Context $context,
        GridFactory $test,
        \Magento\Framework\Registry $registry,
        \Mageants\Productimage\Helper\Data $helperData,
        \Magento\Customer\Model\Session $session,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
    ) {
        $this->_test = $test;
        $this->_storeManager = $context->getStoreManager();
        $this->session = $session;
        $this->filterProvider = $filterProvider;
        $this->_urlInterface = $context->getUrlBuilder();
        $this->_registry = $registry;
        $this->helperData = $helperData;
        parent::__construct($context);
    }
    public function getCustomerCollection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\Session');
        return $customerSession;
    }
    public function getImageCollection()
    {
        $storeManager = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Store\Model\StoreManagerInterface');
        $storeid = $storeManager->getStore()->getStoreId();
            
        // $collection = $this->_test->create()->getCollection()->addFieldToFilter('status', 1)->addFieldToSelect('image')->addFieldToSelect('product_sku')->load();

        $collection = $this->_test->create()->getCollection()
                        ->addFieldToFilter('status', 1)
                        ->addFieldToSelect('image')
                        ->addFieldToSelect('product_sku')
                        ->addFieldToFilter('store_id', [0,$storeid])
                        ->load();
        return $collection;
    }
    public function isCustomerLogin()
    {
        if ($this->getCustomerCollection()->isLoggedIn()) {
            return true;
        }
    }
    public function getCustomerEmail()
    {
        if ($this->getCustomerCollection()->isLoggedIn()) {
            return $this->getCustomerCollection()->getCustomer()->getEmail();
        }
    }
    public function getCustomerName()
    {
        if ($this->getCustomerCollection()->isLoggedIn()) {
            return $this->getCustomerCollection()->getCustomer()->getName();
        }
    }
    public function _prepareLayout()
    {
        parent::_prepareLayout();

        return $this;
    }
    public function getFormAction()
    {
        return $this->getUrl('product_image_customer/index/save');
    }
    
    public function getCurrentProduct()
    {
        return $this->_registry->registry('current_product');
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
    public function getCustomTitle()
    {
        return $this->filterProvider->getPageFilter()->filter($this->helperData->getCustomTitle());
    }
    public function allowGuest()
    {
        return $this->helperData->allowGuest();
    }
    public function emailRequired()
    {
        return $this->helperData->emailRequired();
    }
    public function getNavigationOptions()
    {
        return $this->helperData->getNavigationOptions();
    }
    public function getslideSpeed()
    {
        return $this->helperData->getslideSpeed();
    }
    public function getimageonSlide()
    {
        return $this->helperData->getimageonSlide();
    }
    public function getimageuploadLimit()
    {
        return $this->helperData->getimageuploadLimit();
    }
    public function gethimageDimenstion()
    {
        return $this->helperData->gethimageDimenstion();
    }
    public function getvimageDimenstion()
    {
        return $this->helperData->getvimageDimenstion();
    }
    public function gethimageZoom()
    {
        return $this->helperData->gethimageZoom();
    }
    public function getvimageZoom()
    {
        return $this->helperData->getvimageZoom();
    }
}
