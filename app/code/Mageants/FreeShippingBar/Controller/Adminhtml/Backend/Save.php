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
use Magento\Framework\Filesystem\Driver\File;

class Save extends \Magento\Backend\App\Action
{
    public $freeshippingbarfactory;
    protected $_fileUploaderFactory;
    protected $_modelBlogFactory;
    protected $resultPageFactory;
    protected $_sessionManager;
    protected $_file;
 
    public function __construct(
        Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        Filesystem $filesystem,
        File $file,
        FreeShippingBarFactory $freeshippingbarfactory,
        PageFactory $resultPageFactory,
        SessionManagerInterface $sessionManager,
        \Magento\Framework\Stdlib\DateTime\Timezone $datetime,
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
        $this->_datetime = $datetime;
        $this->_filesystem = $filesystem;
        $this->_file = $file;
    }
    public function execute()
    {
        $BlogModel = $this->freeshippingbarfactory->create();
        $data = $this->getRequest()->getParams();
        $currentDateTime = $this->_datetime->date();
        $data['storeview']= implode(",", $data['storeview']);
        $data['specific_page_to_show'] = preg_replace('/\s+/', ',', $data['specific_page_to_show']);
        $data['specific_page_url'] = preg_replace('/\s+/', ',', $data['specific_page_url']);
        $data['exclude_page'] = preg_replace('/\s+/', ',', $data['exclude_page']);
        $data['exclude_page_url'] = preg_replace('/\s+/', ',', $data['exclude_page_url']);
        $data['customer_group']= implode(",", $data['customer_group']);
        if (array_key_exists("products", $data)) {
            $data['products']= str_replace("&", ",", $data['products']);
        }
        static $temp = 0;
        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath();
        if ($data) {
            $files = $this->getRequest()->getFiles();
            if (!empty($files['image']['tmp_name'])) {
                $uploader = $this->_fileUploaderFactory->create(['fileId' => $files['image']]);
                $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $uploader->save($path);
                $data['image'] = $files['image']['name'];
            } else {
                if (!empty($data['image'])) {
                    if (isset($data['image']['delete'])) {
                        if ($data['image']['delete'] == 1) {
                            if ($this->_file->isExists($path . $data['image']['value'])) {
                                $this->_file->deleteFile($path . $data['image']['value']);
                            }
                            $data['image'] = null;
                        }
                    } else {
                        $data['image'] = $data['image']['value'];
                    }
                }
            }
            if ($data['todate'] >= $currentDateTime->format('Y-m-d H:m:s')) {
                $BlogModel->setData($data);
                if ($BlogModel->save()) {
                    $this->inlineTranslation->resume();
                    $this->messageManager->addSuccess(__('The Data Successfully Saved'));
                    if ($this->getRequest()->getParam('back')) {
                        return $this->_redirect('*/*/edit', [ 'id' => $BlogModel->getId(), '_current' => true]);
                    } else {
                        return $this->_redirect('*/*/index');
                    }
                }
            } else {
                $this->inlineTranslation->resume();
                $this->messageManager->addError(__('%1 Record can not be Save,Increase(To Date)', $data['name']));
                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', [ 'id' => $data['id'], '_current' => true]);
                } else {
                    return $this->_redirect('*/*/index');
                }
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
