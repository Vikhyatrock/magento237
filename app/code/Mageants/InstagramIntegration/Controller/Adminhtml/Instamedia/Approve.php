<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Adminhtml\Instamedia;

use Mageants\InstagramIntegration\Model\InstagramFactory;
use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Mageants\InstagramIntegration\Helper\Data;

/**
 * Class Approve
 * @package Mageants\InstagramIntegration\Controller\Adminhtml\Instamedia
 */
class Approve extends \Magento\Backend\App\Action
{
    /**
     * @param Context $context
     * @param InstagramFactory $instagramFactory
     * @param Collection $instagramCollection
     * @param JsonFactory $resultJsonFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        InstagramFactory $instagramFactory,
        Collection $instagramCollection,
        Data $helperdata,
        JsonFactory $resultJsonFactory,
        array $data = []
    ) {
        parent::__construct($context);
        $this->_instagramFactory = $instagramFactory;
        $this->_instagramCollection = $instagramCollection;
        $this->_resultJsonFactory = $resultJsonFactory;
        $this->_helperData = $helperdata;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        $mediaId = $this->getRequest()->getParam('mediaid');
        $storeId = $this->getRequest()->getParam('storeid');
        $status = $this->getRequest()->getParam('status');
        
        $collection = $this->_instagramCollection
            ->addFieldToFilter('insta_media_id', $mediaId)
            ->addFieldToFilter('store_id', $storeId);
        
        if (!empty($collection->getData())) {
            foreach ($collection as $collections) {
                $model = $this->_instagramFactory->create();
                $modelupdate=$model->load($collections->getId());
                $modelupdate->setInstaStatus($status);
                $savedata=$model->save();
            }
        }
        $result = $this->_resultJsonFactory->create();
        $response = 'Successfully Approved Image.';
        
        return $result->setData($response);
    }

    /**
     * @return string
     */
    public function getUpdateBy()
    {
        return $this->_helperData->getUpdateBy();
    }
}
