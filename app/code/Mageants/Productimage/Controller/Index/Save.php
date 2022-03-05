<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Productimage\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Mageants\Productimage\Model\GridFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Store\Model\ScopeInterface;

class Save extends Action
{
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
        \Magento\Customer\Model\Session $session,
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
        $this->session = $session;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPost();
        
        $storeManager = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Store\Model\StoreManagerInterface');
        $store_id = $storeManager->getStore()->getStoreId();
    
        static $temp = 0;
        $isEnable  = $this->scopeConfig->getValue('productimage/email/emailenable', ScopeInterface::SCOPE_STORE);
        $setTo  = $this->scopeConfig->getValue('productimage/email/emailreceiver', ScopeInterface::SCOPE_STORE);
        $setFrom  = $this->scopeConfig->getValue('productimage/email/identity', ScopeInterface::SCOPE_STORE);
        $resultRedirect     = $this->_resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $files = $this->getRequest()->getFiles();
        if (isset($files)) {
            try {
                foreach ($files['image'] as $image) {
                    if (!empty($image['name'])) {
                        $BlogModel = $this->_modelBlogFactory->create();
                      
                        $date = date('Y-m-d h:i:sa');
                        $status = 0;
                        $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('theme/images/');
                        $uploader = $this->_fileUploaderFactory->create(['fileId' => $image]);
                        if ($uploader->getFileExtension() == 'jpg' || $uploader->getFileExtension() == 'png' || $uploader->getFileExtension() == 'jpeg' || $uploader->getFileExtension() == 'JPG' || $uploader->getFileExtension() == 'PNG' || $uploader->getFileExtension() == 'JPEG') {
                            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'JPG', 'PNG', 'JPEG']);
                            $uploader->setAllowRenameFiles(false);
                            $uploader->setFilesDispersion(false);
                            $uploader->save($path);
                            // $filename = str_replace(" ", "_", $image['name']);
                            $imagepaths = substr($image['name'], 0, strpos($image['name'], '.'));
                            $imagepathextension = substr(strrchr($image['name'], '.'), 1);
                            $fileName='';
                            if (preg_match('/[A-Za-z0-9]/', $imagepaths)) {
                                $fileName=preg_replace('/[^A-Za-z0-9_.\-\/]+/i', '_', $image['name']);
                            } else {

                                 $fileName = 'file.'.$imagepathextension;
                            }
                            $imageurl = 'theme/images/'.$fileName;
                            if ($data) {
                                $BlogModel->setData('status', $status);
                                $BlogModel->setData('image', $imageurl);
                                $BlogModel->setData('product_sku', $data['productsku']);
                                $BlogModel->setData('customer_email', $data['email']);
                                $BlogModel->setData('created_at', $date);
                                $BlogModel->setData('updated_at', $date);
                                $BlogModel->setData('store_id', $store_id);

                                if ($BlogModel->save()) {
                                    $temp = 1;
                                }
                            }
                            $this->messageManager->addSuccess(__('You have successfully uploaded images. Please wait for approval!'));
                        } else {
                             $this->messageManager->addError(__('Disallowed File Type'));
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->messageManager->addError(__('Disallowed File Type'));
            }
            //Email Notify to Customer
            try {
                if ($temp == 1) {
                    $transport = $this->_transportBuilder->setTemplateIdentifier('productimage_email_emailtemplate')
                        ->setTemplateOptions(
                            [
                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                            ]
                        )
                        ->setTemplateVars([
                            'message'   => 'Thank You For Uploade Image for '.$data['productname'].', Please Wait until Approved Your Image.'])
                        ->setFrom($setFrom)
                        ->addTo($data['email'])
                        ->getTransport();
                        $transport->sendMessage();
                }
            } catch (\Exception $e) {
                        
            }

            //Email Notify to Admin
            if ($isEnable) {
                if ($temp == 1) {
                    try {
                        $transport = $this->_transportBuilder->setTemplateIdentifier('productimage_email_emailtemplate')
                        ->setTemplateOptions(
                            [
                                'area' => \Magento\Framework\App\Area::AREA_FRONTEND, // this is using frontend area to get the template file
                                'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                            ]
                        )
                        ->setTemplateVars([
                            'message'   => 'You have Received This E-mail because 
                                we\'ve been notified to you that customer had uploaded the images so kindly go for it and verify!'
                        ])
                        ->setFrom($setFrom)
                        ->addTo($setTo)
                        ->getTransport();
                        $transport->sendMessage();
                    } catch (\Exception $e) {
                        
                    }
                }
            }

            $this->inlineTranslation->resume();
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        } else {
            $resultRedirect->setUrl($this->_redirect->getRefererUrl());
            return $resultRedirect;
        }
    }
}
