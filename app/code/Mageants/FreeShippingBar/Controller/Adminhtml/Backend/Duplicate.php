<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Controller\Adminhtml\Backend;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Mageants\FreeShippingBar\Model\FreeShippingBarFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Store\Model\ScopeInterface;

class Duplicate extends \Magento\Backend\App\Action
{
    public $freeshippingbarfactory;
    protected $_fileUploaderFactory;
    protected $_modelBlogFactory;
    protected $resultPageFactory;
    protected $_sessionManager;
 
    public function __construct(
        Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        Filesystem $filesystem,
        FreeShippingBarFactory $freeshippingbarfactory,
        PageFactory $resultPageFactory,
        SessionManagerInterface $sessionManager,
        array $data = []
    ) {
        parent::__construct($context);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->_resultFactory = $context->getResultFactory();
        $this->freeshippingbarfactory = $freeshippingbarfactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_sessionManager = $sessionManager;
        $this->_filesystem = $filesystem;
    }

    public function execute()
    {
        $BlogModel = $this->freeshippingbarfactory->create();
        $id = $this->getRequest()->getParam('id');
        $data = $BlogModel->load($id)->getData();
        $BlogModel->unsetData();
        $data['id']= null;
        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($data) {
            $BlogModel->setData($data);
            if ($BlogModel->save()) {
                $this->inlineTranslation->resume();
                $this->messageManager->addSuccess(__('The Duplicate Data Successfully Created'));
                return $this->_redirect('*/*/index');
            }
        }
    }
 
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_FreeShippingBar::freeshippingbar');
    }
}
