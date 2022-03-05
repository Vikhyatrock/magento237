<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Controller\Adminhtml\Action;

use Mageants\InstagramIntegration\Model\InstagramFactory;
use Magento\Framework\Registry;

/**
 * Class Edit
 * @package Mageants\InstagramIntegration\Controller\Adminhtml\Action
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param InstagramFactory  $instagramFactory
     * @param PageFactory $resultPageFactory
     * @param SessionManagerInterface $coreSession
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        Registry $registry,
        InstagramFactory $instagramFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_instagramFactory = $instagramFactory;
        $this->_coreSession = $coreSession;
    }

    /**
     * Image edit controller page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $dataId = $this->getRequest()->getParam('id');

        $model= $this->_instagramFactory->create();
        $model->load($dataId);

        $this->_coreSession->start();
        
        $this->_coreSession->setInstaId($model->getInstaMediaId());

        $this->_coreRegistry->register('instagram', $model);
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_InstagramIntegration::manageimages_list');
        $resultPage->getConfig()->getTitle()->prepend(__('Edit Images'));
        
        return $resultPage;
    }
}
