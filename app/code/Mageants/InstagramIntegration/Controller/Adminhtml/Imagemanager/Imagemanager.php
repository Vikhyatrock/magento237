<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Adminhtml\Imagemanager;

/**
 * Class Info
 * @package Mageants\InstagramIntegration\Controller\Adminhtml\Imagemanager
 */
class Imagemanager extends \Magento\Backend\App\Action
{
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Image Manager controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_InstagramIntegration::manageimages_list');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Images'));
        return $resultPage;
    }
 
    /**
     * Check Permission.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_InstagramIntegration::approved_image_manage');
    }
}
