<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Controller\Adminhtml\Backend;

use Mageants\FreeShippingBar\Model\FreeShippingBarFactory;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
 
    /**
     * @param \Magento\Backend\App\Action\Context        $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        FreeShippingBarFactory $freeshippingbarfactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->freeshippingbarfactory = $freeshippingbarfactory;
    }
 
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $model = $this->freeshippingbarfactory->create();
        $model->load($id);
        $title = $model->getName();
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_FreeShippingBar::freeshippingbar');
        $resultPage->getConfig()->getTitle()->prepend(__($title));
        return $resultPage;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_FreeShippingBar::freeshippingbar');
    }
}
