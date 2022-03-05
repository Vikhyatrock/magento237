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
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Store\Model\ScopeInterface;

class Update extends \Magento\Backend\App\Action
{
    public $gridFactory;
    protected $_fileUploaderFactory;
    protected $_modelBlogFactory;
    protected $resultPageFactory;
    protected $_sessionManager;

    public function __construct(
        Context $context,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Mageants\Productimage\Helper\Data $helper,
        array $data = []
    ) {
        parent::__construct($context);
        $this->resource = $resource;
        $this->_helper = $helper;
        $this->productRepository = $productRepository;
        $this->_fileUploaderFactory = $fileUploaderFactory;
    }

    public function execute()
    {
        $data = $this->_request->getParams();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            /** @var \Mageants\RestrictProductsByCustomerGroup\Model\RPCG $model */
            $model = $this->_objectManager->create('Mageants\Productimage\Model\Grid');
            $id = $this->getRequest()->getParam('id');
            
            if (isset($data['store_id'])) {
                if (in_array('0', $data['store_id'])) {
                    $data['store_id'] = implode(",", $data['store_id']);
                } else {
                    $data['store_id'] = implode(",", $data['store_id']);
                }
            }

            if ($id) {
                $model->load($id);
            }
            if (isset($data['image'][0]['tmp_name'])) {
                $this->imageUploader = \Magento\Framework\App\ObjectManager::getInstance()->get('Mageants\Productimage\GridImageUpload');
                $this->imageUploader->moveFileFromTmp($data['image'][0]['name']);
                $result = $data['image'][0]['name'];
            } else {
                $url= $data['image'][0]['url'];
                $prefix = "media/";
                $index = strpos($url, $prefix) + strlen($prefix);
                $result = substr($url, $index);
            }
            $data['image'] = $result;
        
            $model->setData($data);
            try {
                
                $product = $this->productRepository->get($data['product_sku']);
                $vars = [];
                $vars['product_name'] = $product->getName();
                $vars['customer_email'] = $data['customer_email'];
                if ($data['status'] == 1) {
                    $vars['status'] = 'approve';
                } else {
                    $vars['status'] = 'reject';
                }
                
                $model->save();
                $this->_helper->sendMail($vars);
                $this->messageManager->addSuccess(__('You saved this Product Image.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }

                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Product Image.'.$e->getMessage()));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_Productimage::update');
    }
}
