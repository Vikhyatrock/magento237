<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Block\Adminhtml;

use Mageants\InstagramIntegration\Model\InstagramFactory;
use Mageants\InstagramIntegration\Model\InstagramCarouselFactory;
use Mageants\InstagramIntegration\Model\ResourceModel\Instagram\Collection;
use Mageants\InstagramIntegration\Helper\Data;

/**
 * Class Newimage
 * @package Mageants\InstagramIntegration\Block\Adminhtml
 */
class Newimage extends \Magento\Framework\View\Element\Template
{
    /**
     * @param Context $context
     * @param Collection $instagramCollection
     * @param InstagramFactory $instagram
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Collection $instagramCollection,
        Data $helperdata,
        InstagramCarouselFactory $instagramCarouselFactory,
        InstagramFactory $instagram
    ) {
        $this->_instagram = $instagram;
        $this->_instagramCollection = $instagramCollection;
        $this->_instagramCarouselFactory = $instagramCarouselFactory;
        $this->_helperData = $helperdata;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function getInstaMedia()
    {
        $storeId = 0;
        if ($this->getRequest()->getParam('store') != '') {
            $storeId = $this->getRequest()->getParam('store');
        }
        
        $collection = $this->_instagramCollection->addFieldToFilter('insta_status', ['null' => true])
            ->addFieldToFilter('update_by', 'self')
            ->addFieldToFilter('store_id', $storeId);
        
        return $collection;
    }

    public function getCarouselMediaType($id)
    {
        $type = $this->_instagramCarouselFactory->create()->getCollection()
                    ->addFieldToSelect('insta_carousel_media_type')
                    ->addFieldToFilter('insta_media_id', ['eq' => $id]);
        return $type;
    }

    /**
     * @return string
     */
    public function getUpdateBy($storeId)
    {
        return $this->_helperData->getUpdateBy($storeId);
    }

    /**
     * @return string
     */
    public function getHashtag($storeId)
    {
        return $this->_helperData->getHashtag($storeId);
    }

    /**
     * Get store identifier
     *
     * @return  int
     */
    public function getStoreId()
    {
        $storeId = 0;
        if ($this->request->getParam('store') != '') {
            $storeId = $this->request->getParam('store');
        }
        return $storeId;
    }
}
