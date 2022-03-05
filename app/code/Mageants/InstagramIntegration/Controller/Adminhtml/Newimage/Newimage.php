<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Adminhtml\Newimage;

/**
 * Class Newimage
 * @package Mageants\InstagramIntegration\Controller\Adminhtml\Newimage
 */
class Newimage extends \Magento\Backend\App\Action
{
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * New image controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_InstagramIntegration::instaimage_form');
        $resultPage->getConfig()->getTitle()->prepend(__('New Images'));

        return $resultPage;
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
}
