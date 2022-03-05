<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Gallery;

use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection;
use Mageants\InstagramIntegration\Model\ResourceModel\InstagramCarousel\Collection as carouselCollection;
use Magento\Framework\Controller\Result\JsonFactory;
use Mageants\InstagramIntegration\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\HTTP\Client\Curl;

/**
 * Class Instapopup
 * @package Mageants\InstagramIntegration\Controller\Gallery
 */
class Instapopup extends \Magento\Framework\App\Action\Action
{
    protected $curlClient;

    /**
     * @param Context $context
     * @param ProductRepositoryInterfaceFactory $productRepositoryFactory
     * @param StoreManagerInterface $storeInterface
     * @param ProductRepository $productRepository
     * @param JsonFactory $resultJsonFactory
     * @param Data $helperdata
     * @param Collection $instagramCollection
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        \Magento\Store\Model\StoreResolver $storeResolver,
        StoreManagerInterface $storeInterface,
        ProductRepository $productRepository,
        JsonFactory $resultJsonFactory,
        Data $helperdata,
        Collection $instagramCollection,
        carouselCollection $carouselCollection,
        Curl $curl
    ) {
        $this->_instagramCollection = $instagramCollection;
        $this->_carouserlCollection = $carouselCollection;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_helperData = $helperdata;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->_storeManager = $storeInterface;
        $this->_productRepository = $productRepository;
        $this->curlClient = $curl;
        $this->storeResolver = $storeResolver;

        return parent::__construct($context);
    }

    /**
     * Image popup Manager controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $storeId = $this->getCurrentStoreId();
        $userdata=$this->_getInstagramData();
        $showPopup = $this->getShowPopupWith();
        $user = [];
        $content="";
        if (!empty($userdata) && is_array($userdata) || $userdata instanceof Countable) {
            for ($i=0; $i <1; $i++) {
                $user=$userdata[$i]['user'];
            }
        }
        $result = $this->_resultJsonFactory->create();
        $id = $this->getRequest()->getParam('Id');

        $collection = $this->_instagramCollection
                    ->addFieldToFilter('id', $id);
        $instaData = $collection->getData();
        
        foreach ($instaData as $data) {
            $carouselcollection = $this->_carouserlCollection->addFieldToFilter('instagram_data_id', $data['id']);
            $carouselData = $carouselcollection->getData();

            $content .= '<div id="popup-content">
                <div id="prevbtn" onclick="prevnextpopup(1);"></div>
                <div id="nextbtn" onclick="prevnextpopup(2);"></div>
                <div id="popup-leftbar">';

            if ($data['title']) {
                $content .= '<p class="image-title"><a href="'.$data['link'].'" target="_blank" >'.$data['title'].'</a></p>';
            }

            if (!empty($carouselData)) {
                foreach ($carouselData as $carouselDatas) {
                    $content .='<img class="instaSlides" alt="" src="'.$carouselDatas["insta_carousel_large"].'">';
                }
                if ($this->isShowNavigationPopup()) {
                    $content .='<i onclick="plusDivs(-1)" class="fa fa-arrow-circle-left" aria-hidden="true"></i>
                        <i onclick="plusDivs(+1)" class="fa fa-arrow-circle-right" aria-hidden="true"></i>';
                }
            } else {
                $content .='<img alt="" src="'.$data["insta_media_large"].'">';
            }
                $content .='<span id="titletext"></span></div>
                <div id="popup-rightbar">';
            if (!empty($user)) {
                $content .='<div class="main-user-div"><div class="user"><div class="userimage">
                <img src="'.$user['profile_picture'].'" alt="'.$user['username'].'"></div>
                <div class="userlink"><a target="_blank"
                href="https://www.instagram.com/'.$user['username'].'">'.$user['username'].'</a>
                </div></div></div>';
            }
                $content .='<div class="productlinktitle">
                
                <h3>'.$this->_helperData->getProductLinkTitle($storeId).'</h3></div>';
            if ($showPopup=="titlelink") {
                $content .= '<ul class="alltitleul">';
            } else {
                $content .= '<ul class="alltitleul prditem">';
            }
            if ($data['position_top1'] || $data['position_left1']) {
                if ($showPopup=="titlelink") {
                    $content .= '<li class="'.$data['position_top1'].'-'.$data['position_left1'].'">';
                    $content .= '<div id="prditemdiv1">
                    <a href="'.$data['url_title1'].'" target="_blank">
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title1"].'</span>
                    </a></div>';
                    $content .= '</li>';
                }
                if ($showPopup=="productblock" && $data["product_id_1"]) {
                    $product1 = $this->_productRepositoryFactory->create()->getById($data["product_id_1"]);
                    $productImgURL1 = $this->getMediaUrl()."catalog/product".$product1->getData('thumbnail');
                            
                    $producturl1 = $this->_productRepository->getById($data["product_id_1"]);
                    $purl1 = $producturl1->getUrlModel()->getUrl($producturl1);

                    $content .= '<li class="'.$data['position_top1'].'-'.$data['position_left1'].'">';
                    $content .= '<div id="prditemdiv1"><a>
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title1"].'</span>
                    </a></div><a href="'.$purl1.'" target="_blank"
                    title="'.$data["title1"].'" class="prdblocka">
                    <img src="'.$productImgURL1.'">
                    <span id="prdname1">'.$data["title1"].'</span></a>';
                    $content .= '</li>';
                }
            }
            if ($data['position_top2'] || $data['position_left2']) {
                if ($showPopup=="titlelink") {
                    $content .= '<li class="'.$data['position_top2'].'-'.$data['position_left2'].'">';
                    $content .= '<div id="prditemdiv2">
                    <a href="'.$data['url_title2'].'" target="_blank">
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title2"].'</span>
                    </a></div>';
                    $content .= '</li>';
                }
                if ($showPopup=="productblock" && $data["product_id_2"]) {
                    $product2 = $this->_productRepositoryFactory->create()->getById($data["product_id_2"]);
                    $productImgURL2 = $this->getMediaUrl()."catalog/product".$product2->getData('thumbnail');
                    $producturl2 = $this->_productRepository->getById($data["product_id_2"]);
                    $purl2 = $producturl2->getUrlModel()->getUrl($producturl2);
                            
                    $content .= '<li class="'.$data['position_top2'].'-'.$data['position_left2'].'">';
                    $content .= '<div id="prditemdiv2"><a>
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title2"].'</span></a></div>
                    <a href="'.$purl2.'" target="_blank" title="'.$data["title2"].'"
                    class="prdblocka"><img src="'.$productImgURL2.'">
                    <span id="prdname2">'.$data["title2"].'</span></a>';
                    $content .= '</li>';
                }
            }
            if ($data['position_top3'] || $data['position_left3']) {
                if ($showPopup=="titlelink") {
                    $content .= '<li class="'.$data['position_top3'].'-'.$data['position_left3'].'">';
                    $content .= '<div id="prditemdiv3">
                    <a href="'.$data['url_title3'].'" target="_blank">
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title3"].'</span></a></div>';
                    $content .= '</li>';
                }
                if ($showPopup=="productblock" && $data["product_id_3"]) {
                    $product3 = $this->_productRepositoryFactory->create()->getById($data["product_id_3"]);
                    $productImgURL3 = $this->getMediaUrl()."catalog/product".$product3->getData('thumbnail');
                            
                    $producturl3 = $this->_productRepository->getById($data["product_id_3"]);
                    $purl3 = $producturl3->getUrlModel()->getUrl($producturl3);

                    $content .= '<li class="'.$data['position_top3'].'-'.$data['position_left3'].'">';
                    $content .= '<div id="prditemdiv3"><a>
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title3"].'</span></a></div>
                    <a href="'.$purl3.'" target="_blank" title="'.$data["title3"].'"
                    class="prdblocka"><img src="'.$productImgURL3.'">
                    <span id="prdname3">'.$data["title3"].'</span></a>';
                    $content .= '</li>';
                }
            }
            if ($data['position_top4'] || $data['position_left4']) {
                if ($showPopup=="titlelink") {
                    $content .= '<li class="'.$data['position_top4'].'-'.$data['position_left4'].'">';
                    $content .= '<div id="prditemdiv4">
                    <a href="'.$data['url_title4'].'" target="_blank">
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title4"].'</span></a></div>';
                    $content .= '</li>';
                }
                if ($showPopup=="productblock" && $data["product_id_4"]) {
                    $product4 = $this->_productRepositoryFactory->create()->getById($data["product_id_4"]);
                    $productImgURL4 = $this->getMediaUrl()."catalog/product".$product4->getData('thumbnail');
                            
                    $producturl4 = $this->_productRepository->getById($data["product_id_4"]);
                    $purl4 = $producturl4->getUrlModel()->getUrl($producturl4);

                    $content .= '<li class="'.$data['position_top4'].'-'.$data['position_left4'].'">';
                    $content .= '<div id="prditemdiv4">
                    <a><span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title4"].'</span></a></div>
                    <a href="'.$purl4.'" target="_blank" title="'.$data["title4"].'"
                    class="prdblocka"><img src="'.$productImgURL4.'">
                    <span id="prdname4">'.$data["title4"].'</span></a>';
                    $content .= '</li>';
                }
            }
            if ($data['position_top5'] || $data['position_left5']) {
                if ($showPopup=="titlelink") {
                    $content .= '<li class="'.$data['position_top5'].'-'.$data['position_left5'].'">';
                    $content .= '<div id="prditemdiv5">
                    <a href="'.$data['url_title5'].'" target="_blank">
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title5"].'</span></a></div>';
                    $content .= '</li>';
                }
                if ($showPopup=="productblock" && $data["product_id_5"]) {
                    $product5 = $this->_productRepositoryFactory->create()->getById($data["product_id_5"]);
                    $productImgURL5 = $this->getMediaUrl()."catalog/product".$product5->getData('thumbnail');
                            
                    $producturl5 = $this->_productRepository->getById($data["product_id_5"]);
                    $purl5 = $producturl5->getUrlModel()->getUrl($producturl5);

                    $content .= '<li class="'.$data['position_top5'].'-'.$data['position_left5'].'">';
                    $content .= '<div id="prditemdiv5">
                    <a><span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title5"].'</span></a></div>
                    <a href="'.$purl5.'" target="_blank" title="'.$data["title5"].'"
                    class="prdblocka"><img src="'.$productImgURL5.'">
                    <span id="prdname5">'.$data["title5"].'</span></a>';
                    $content .= '</li>';
                }
            }
            if ($data['position_top6'] || $data['position_left6']) {
                if ($showPopup=="titlelink") {
                    $content .= '<li class="'.$data['position_top6'].'-'.$data['position_left6'].'">';
                    $content .= '<div id="prditemdiv6">
                    <a href="'.$data['url_title6'].'" target="_blank">
                    <span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title6"].'</span></a></div>';
                    $content .= '</li>';
                }
                if ($showPopup=="productblock" && $data["product_id_6"]) {
                    $product6 = $this->_productRepositoryFactory->create()->getById($data["product_id_6"]);
                    $productImgURL6 = $this->getMediaUrl()."catalog/product".$product6->getData('thumbnail');
                            
                    $producturl6 = $this->_productRepository->getById($data["product_id_6"]);
                    $purl6 = $producturl6->getUrlModel()->getUrl($producturl6);

                    $content .= '<li class="'.$data['position_top6'].'-'.$data['position_left6'].'">';
                    $content .= '<div id="prditemdiv6">
                    <a><span class="number">&nbsp;</span>
                    <span class="numbertitle">'.$data["title6"].'</span></a></div>
                    <a href="'.$purl6.'" target="_blank" title="'.$data["title6"].'"
                    class="prdblocka"><img src="'.$productImgURL6.'">
                    <span id="prdname6">'.$data["title6"].'</span></a>';
                    $content .= '</li>';
                }
            }
                   $content .= '</ul>
                   <div id="instatitle">
                   <h3>'.$this->_helperData->getInstagramCaptionTitle($storeId).'</h3>'.$data["insta_caption"].'</div>
                   </div>
                   </div>';
        }
        $result->setData($content);
        return $result;
    }

    /**
     * @return string|int
     */
    public function getAccessToken()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getAccessToken($storeId);
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
     * @return string
     */
    public function getShowPopupWith()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getShowPopupWith($storeId);
    }

    /**
     * Return url of media
     *
     * @return string
     */
    public function getMediaUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @return string
     */
    public function getUpdateBy()
    {
        $storeId = $this->getCurrentStoreId();
        return $this->_helperData->getUpdateBy($storeId);
    }

    /**
     * Return array of instagram
     *
     * @return array
     */
    protected function _getInstagramData()
    {
        $accessToken = $this->getAccessToken();

        $apiURL= "https://api.instagram.com/v1/users/self/media/recent/?access_token=".$accessToken;
        $this->curlClient->get($apiURL);
        $result = $this->curlClient->getBody();
                       
        $data=$this->_helperData->jsonDecode($result);
        $data=(array)$data;
        
        $dt = [];
        if (isset($data["data"])) {
            foreach ($data["data"] as $test) {
                $dt[] = $test;
            }
        }

        return $dt;
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
