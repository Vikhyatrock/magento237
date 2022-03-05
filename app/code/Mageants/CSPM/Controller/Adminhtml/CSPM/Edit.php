<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Controller\Adminhtml\CSPM;

/**
 * Edit class for CSPM Form
 */
class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;

    /**
     * Session
     *
     * @var \Magento\Backend\Model\Session
     */
    protected $_session;

    /**
     * CSPM Model
     *
     * @var \Mageants\CSPM\Model\Cspm 
     */
    protected $_cspmModel;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     * @param \Mageants\CSPM\Model\Cspm $cspmModel
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry,
        \Mageants\CSPM\Model\Cspm $cspmModel
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        $this->_session = $context->getSession();
        $this->_cspmModel = $cspmModel;
        parent::__construct($context);
    }
    
    /**
     * inidaction
     * 
     * @return resultPage
     */   
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Mageants_CSPM::cspm_content')
            ->addBreadcrumb(__('CSPM'), __('CSPM'))
            ->addBreadcrumb(__('Manage CSPM'), __('Manage CSPM'));
        return $resultPage;
    }   
    
	/**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return true;
    }

    /**
     * CSPM Edit Action
     * 
     * @var \Magento\Framework\View\Result\PageFactory
     */
	public function execute()
    {
		// 1. Get ID and create model
        $id = $this->getRequest()->getParam('entity_id');
		
        $model = $this->_cspmModel;
		
		$registryObject = $this->_coreRegistry;
		
        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This row no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }
        // 3. Set entered data if was error when we do save
        $data = $this->_session->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
		$registryObject->register('CSPM_data', $model);
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit CSPM') : __('New CSPM'),
            $id ? __('Edit CSPM') : __('New CSPM')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Categories'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? "Edit CSPM" : __('New CSPM'));

        return $resultPage;		
    }
}
