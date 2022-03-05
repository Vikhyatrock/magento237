<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Controller\Adminhtml\Category;

use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;

class VideosGrid extends \Magento\Backend\App\Action
{
/**
 * @var \Magento\Backend\Helper\Js
 */
    protected $_jsHelper;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @var \Webspeaks\BannerSlider\Model\ResourceModel\Slide\CollectionFactory
     */
    protected $_videoCollectionFactory;

    /**
     * \Magento\Backend\Helper\Js $jsHelper
     * @param \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
     * @param Action\Context $context
     */
    public function __construct(
        Action\Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Mageants\ImageGallery\Model\ResourceModel\Video\CollectionFactory $videoCollectionFactory
    ) {
        parent::__construct($context);
        $this->_jsHelper = $jsHelper;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->_videoCollectionFactory = $videoCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_ImageGallery::save');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultLayout = $this->_resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('imagegallery.category.edit.tab.videos')
                     ->setInVideo($this->getRequest()->getPost('video', null));
        return $resultLayout;
    }
}
