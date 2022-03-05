<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Helper;

use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection;
use Mageants\InstagramIntegration\Model\ResourceModel\InstagramCarousel\Collection as carouselCollection;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\App\State;

/**
 * Class Data
 * @package Mageants\InstagramIntegration\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        StoreManagerInterface $storeInterface,
        State $state,
        ProductRepository $productRepository,
        Collection $instagramCollection,
        carouselCollection $carouselCollection,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Json\Helper\Data $jsonData,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_instagramCollection = $instagramCollection;
        $this->_carouserlCollection = $carouselCollection;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->_storeManager = $storeInterface;
        $this->state = $state;
        $this->_productRepository = $productRepository;
        $this->_request = $request;
        $this->jsonHelper = $jsonData;
        $this->scopeConfig = $scopeConfig;
    }
    
    /**
     * @return bool|string
     */
    public function getConfig($config_path, $storeId)
    {
        return $this->scopeConfig->getValue($config_path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @return bool
     */
    public function isEnableInstagramIntegration($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/enable_frontend', $storeId);
    }

    /**
     * @return string
     */
    public function getInstagramFeedTitle($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/instagram_feed_title', $storeId);
    }

    public function getInstagramLogoFile($storeId)
    {
        $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $InstaUploadedFile = $this->getConfig('instagramintegration/instagram_integration/instagram_file_upload', $storeId);
        if (!empty($InstaUploadedFile)) {
            $InstaLogoPath = $mediaUrl.'Instagram_Image/'.$InstaUploadedFile;
            return $InstaLogoPath;
        } else {
            return null;
        }
    }
    
    /**
     * @return string
     */
    public function getProductLinkTitle($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/product_link_title', $storeId);
    }

    /**
     * @return string
     */
    public function getInstagramCaptionTitle($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/insta_caption_title', $storeId);
    }

    /**
     * @return bool
     */
    public function isShowOnHomePage($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/showon_homepage', $storeId);
    }

    /**
     * @return int
     */
    public function getNoOfImagesShowOnHomePage($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/noofimage_homepage', $storeId);
    }

    /**
     * @return bool
     */
    public function isShowOnProductPage($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/showon_productpage', $storeId);
    }

    /**
     * @return int
     */
    public function getNoOfImagesShowOnProductPage($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/noofimage_productpage', $storeId);
    }

    /**
     * @return bool
     */
    public function isMoreViewSection($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/moreview_section', $storeId);
    }

    /**
     * @return bool
     */
    public function isProductDetailSection($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/product_details', $storeId);
    }

    /**
     * @return string
     */
    public function getAccessToken($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/instagram_accesstoken', $storeId);
    }

    /**
     * @return int
     */
    public function getNoOfImagesFetch($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/noofimage_fetch', $storeId);
    }

    /**
     * @return bool
     */
    public function isShowNavigationPopup($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/shownavigation_popup', $storeId);
    }

    /**
     * @return bool
     */
    public function getShowPopupWith($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/showpopup', $storeId);
    }

    /**
     * @return int
     */
    public function getNoOfImagesShowInstagramPage($storeId)
    {
        return $this->getConfig('instagramintegration/instagram_integration/noofshow_instagrampage', $storeId);
    }

    /**
     * @return int
     */
    public function jsonDecode($result)
    {
        return $this->jsonHelper->jsonDecode($result);
    }
}
