<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Adminhtml\Newimage;

use Mageants\InstagramIntegration\Model\InstagramFactory;
use Mageants\InstagramIntegration\Model\InstagramCarouselFactory;
use Mageants\InstagramIntegration\Helper\Data;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Filesystem\Driver\Http;

/**
 * Class Update
 * @package Mageants\InstagramIntegration\Controller\Adminhtml\Newimage
 */
class Update extends \Magento\Framework\App\Action\Action
{
    protected $curlClient;

    protected $fileHttp;

    /**
     * @param Context $context
     * @param InstagramFactory $instagram
     * @param Data $helperdata
     * @param ResultFactory $result
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        InstagramFactory $instagram,
        Data $helperdata,
        InstagramCarouselFactory $carouselFactory,
        Curl $curl,
        Http $http
    ) {
        $this->_instagram = $instagram;
        $this->_carouselFactory = $carouselFactory;
        $this->_resultFactory = $context->getResultFactory();
        $this->_helperData = $helperdata;
        $this->_messageManager = $context->getMessageManager();
        $this->_storeManager = $storeManager;
        $this->curlClient = $curl;
        $this->fileHttp = $http;
        parent::__construct($context);
    }

    /**
     * update image controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $storeId = $this->getStoreId();
        //if ($this->getUpdateBy()=="user") {
        $datas = $this->_getInstagramData();
                
            if ($datas) {
                foreach ($datas as $data) {
                    $collection = $this->_instagram->create()->getCollection()
                    ->addFieldToFilter('insta_media_id', $data['id'])
                    ->addFieldToFilter('store_id', $storeId);
                    if (empty($collection->getData())) {

                        $instamodel = $this->_instagram->create();
                        $instamodel->addData([
                            "insta_media_id" => $data['id'],
                            "insta_media_thumbnail" => $data['media_url'],
                            "insta_media_medium" => $data['media_url'],
                            "insta_media_large" => $data['media_url'],
                            "update_by" => 'self',
                            "insta_type" => $data['media_type'],
                            "store_id" => $storeId,
                            ]);
                        $saveData = $instamodel->save();
                    }
                }
            
                $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            } else {
                $this->_messageManager->addError(__('Please Enter Access Token into Configuration Setting.'));
                $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
                $resultRedirect->setUrl($this->_redirect->getRefererUrl());
                return $resultRedirect;
            }
        
        return $resultRedirect;
    }
 
    /**
     * Check Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_InstagramIntegration::new_image');
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        $storeId = 0;
        if ($this->getRequest()->getParam('store') != '') {
            $storeId = $this->getRequest()->getParam('store');
        }
        return $storeId;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        $storeId = $this->getStoreId();
        return $this->_helperData->getAccessToken($storeId);
    }

     /**
     * Return array of instagram
     *
     * @return array
     */
    protected function _getInstagramData()
    {
        if ($this->getAccessToken()) {
            
            // Get image IDs
            $mediaIds = "https://graph.instagram.com/me/media?fields=id,caption&access_token=".$this->getAccessToken();
            $this->curlClient->get($mediaIds);
            $resultMedia = $this->curlClient->getBody();
            
            $mediaData = $this->_helperData->jsonDecode($resultMedia);
            $mediaData=(array)$mediaData;
            
            $instaData = array();
            $ids = array();
            
            foreach ($mediaData['data'] as $v1) {
                if (isset($v1['id'])) {
                    $ids[] = $v1['id'];
                    $apiURL= "https://graph.instagram.com/".$v1['id']."?fields=id,media_type,media_url,username,timestamp&access_token=".$this->getAccessToken();

                    $this->curlClient->get($apiURL);
                    $result = $this->curlClient->getBody();
                    
                    $data = $this->_helperData->jsonDecode($result);
                    $instaData[] =(array)$data;
                }
            }
            return $instaData;
        }
        return false;
    }
}
