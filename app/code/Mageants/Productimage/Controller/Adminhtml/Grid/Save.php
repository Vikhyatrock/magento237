<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Mageants\Productimage\Model\GridFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Store\Model\ScopeInterface;

class Save extends \Magento\Backend\App\Action
{
    public $gridFactory;
    protected $_fileUploaderFactory;
    protected $_modelBlogFactory;
    protected $resultPageFactory;
    protected $_sessionManager;
 
    public function __construct(
        Context $context,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Filesystem $filesystem,
        GridFactory $modelBlogFactory,
        PageFactory $resultPageFactory,
        SessionManagerInterface $sessionManager,
        ResultFactory $resultFactory,
        array $data = []
    ) {
        parent::__construct($context);
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->_resultFactory = $resultFactory;
        $this->_modelBlogFactory = $modelBlogFactory;
        $this->resultPageFactory = $resultPageFactory;
        $this->_sessionManager = $sessionManager;
        $this->_filesystem = $filesystem;
    }

    public function execute()
    {
        $BlogModel = $this->_modelBlogFactory->create();
        $data = $this->getRequest()->getPost();
        $store_id = $data['store_id'];
        $mag_store_id = implode(",", $store_id);
       
        static $temp = 0;
        $resultRedirect = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        if ($data['image']) {
            $count = 0;
            foreach ($data['image'] as $image) {
                if (!empty($image['name'])) {
                    $BlogModel          = $this->_modelBlogFactory->create();
                    $data               = $this->getRequest()->getPost();
                    $date               = date('Y-m-d h:i:sa');
                    if (isset($image['tmp_name'])) {
                        $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get('Mageants\Productimage\GridImageUpload');
                        $this->imageUploader->moveFileFromTmp($image['name']);
                        $imageurl = 'theme/images/'.$image['name'];
                    } else {
                        $url= $image['url'];
                        $prefix = "media/";
                        $index = strpos($url, $prefix) + strlen($prefix);
                        $imageurl = substr($url, $index);
                    }
                    if ($data) {
                        $BlogModel->setData('status', $data['status']);
                        $BlogModel->setData('image', $imageurl);
                        $BlogModel->setData('product_sku', $data['productsku']);
                        $BlogModel->setData('customer_email', $data['email']);
                        $BlogModel->setData('created_at', $date);
                        $BlogModel->setData('updated_at', $date);
                        $BlogModel->setData('store_id', $mag_store_id);

                        if ($BlogModel->save()) {
                            $temp = 1;
                        }
                    }
                    $count++;
                }
            }
            if ($temp == 1) {
                $this->inlineTranslation->resume();
                $this->messageManager->addSuccess(__('The Data Successfully Submitted!'));
                return $this->_redirect('*/*/index');
            }
        }
    }
 
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_Productimage::save');
    }
}
