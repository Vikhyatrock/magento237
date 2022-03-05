<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Controller\Adminhtml\CSPM;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
/**
 * index class for CSPM
 */
class Index extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_CSPM::cspm_cspm_index');
    }
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_CSPM::cspm_content');
        $resultPage->addBreadcrumb(__('CSPM'), __('CSPM'));
        $resultPage->addBreadcrumb(__('Manage CSPM'), __('Manage CSPM'));
        $resultPage->getConfig()->getTitle()->prepend(__('CSPM'));

        return $resultPage;
    }
}
