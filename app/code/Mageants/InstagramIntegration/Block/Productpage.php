<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Block;

use Mageants\InstagramIntegration\Helper\Data;
use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection;

/**
 * Class Productpage
 * @package Mageants\InstagramIntegration\Block
 */
class Productpage extends \Magento\Framework\View\Element\Template
{

    /**
     * Instagram constructor
     * @param \Mageants\InstagramIntegration\Helper\Data $helperdata
     * @param \Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection $instagramCollection
     * @param \Magento\Framework\View\Element\Template\Context $context
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Store\Model\StoreResolver $storeResolver,
        Collection $instagramCollection,
        Data $helperdata
    ) {
        parent::__construct($context);
        $this->_helperData = $helperdata;
        $this->storeResolver = $storeResolver;
        $this->_instagramCollection = $instagramCollection;
    }

    /**
     * @return bool
     */
    public function isEnableInstagramIntegration()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->isEnableInstagramIntegration($storeId);
    }
    
    /**
     * @return bool
     */
    public function isShowOnProductPage()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->isShowOnProductPage($storeId);
    }

    /**
     * @return string
     */
    public function getInstagramFeedTitle()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getInstagramFeedTitle($storeId);
    }

    public function getInstagramLogoFile()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getInstagramLogoFile($storeId);
    }
    
    /**
     * @return int
     */
    public function getNoOfImagesShowOnProductPage()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getNoOfImagesShowOnProductPage($storeId);
    }

    /**
     * @return bool
     */
    public function isShowNavigationPopup()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->isShowNavigationPopup($storeId);
    }

    /**
     * @return int
     */
    public function getShowPopupWith()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getShowPopupWith($storeId);
    }

    /**
     * @return bool
     */
    public function isMoreViewSection()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->isMoreViewSection($storeId);
    }

    /**
     * @return bool
     */
    public function isProductDetailSection()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->isProductDetailSection($storeId);
    }

    /**
     * @return array
     */
    public function getInstagramCollection()
    {
    
        $collection = $this->_instagramCollection->addFieldToFilter('insta_status', 1)
            ->addFieldToFilter('update_by', 'self')
            ->addFieldToFilter('store_id', $this->getCurrentStoreId());
        if ($this->getNoOfImagesShowOnProductPage()) {
            $collection->getSelect()
                   ->limit($this->getNoOfImagesShowOnProductPage());
        }
        $collection->load();
        
        return $collection;
    }

    /**
     * @return bool
     */
    public function isShowOnProductPageDefault()
    {
        return $this->_helperData->isShowOnProductPage(0);
    }

    /**
     * @return string
     */
    public function getInstagramFeedTitleDefault()
    {
        return $this->_helperData->getInstagramFeedTitle(0);
    }

    /**
     * @return int
     */
    public function getNoOfImagesShowOnProductPageDefault()
    {
        return $this->_helperData->getNoOfImagesShowOnProductPage(0);
    }

    /**
     * @return bool
     */
    public function isShowNavigationPopupDefault()
    {
        return $this->_helperData->isShowNavigationPopup(0);
    }

    /**
     * @return int
     */
    public function getShowPopupWithDefault()
    {
        return $this->_helperData->getShowPopupWith(0);
    }

    /**
     * @return bool
     */
    public function isMoreViewSectionDefault()
    {
        return $this->_helperData->isMoreViewSection(0);
    }

    /**
     * @return bool
     */
    public function isProductDetailSectionDefault()
    {
        return $this->_helperData->isProductDetailSection(0);
    }

    /**
     * @return array
     */
    public function getInstagramCollectionDefault()
    {
        $this->_instagramCollection->clear()->getSelect()->reset(\Zend_Db_Select::WHERE);
        
        $collectionDefault = $this->_instagramCollection->addFieldToFilter('insta_status', 1)
            ->addFieldToFilter('update_by', 'self')
            ->addFieldToFilter('store_id', 0);
        if ($this->getNoOfImagesShowOnProductPageDefault()) {
            $collectionDefault->getSelect()
                   ->limit($this->getNoOfImagesShowOnProductPageDefault());
        }
        $collectionDefault->load();
        
        return $collectionDefault;
    }

    /**
     * Returns the current store id, if it can be detected or default store id
     *
     * @return int|string
     */
    public function getCurrentStoreId()
    {
        return $this->storeResolver->getCurrentStoreId();
    }
}
