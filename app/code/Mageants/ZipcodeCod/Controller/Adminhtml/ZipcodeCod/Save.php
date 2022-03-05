<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Controller\Adminhtml\ZipcodeCod;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\TestFramework\ErrorLog\Logger;
// use Magento\Backend\Model\Session;
use Mageants\ZipcodeCod\Helper\Data;

class Save extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Backend\Helper\Js
     */
    public $jsHelper;

    public $zipCodeModel;

    public $backendSession;

    /**
     * \Magento\Backend\Helper\Js $jsHelper
     * @param Action\Context $context
     */
    public function __construct(
        Context $context,
        \Magento\Backend\Helper\Js $jsHelper,
        Data $helperData,
        \Mageants\ZipcodeCod\Model\ZipcodeCod $zipCodeModel
    ) {
        $this->_jsHelper = $jsHelper;
        $this->_zipCodeModel = $zipCodeModel;
        $this->helperData = $helperData;
        $this->_backendSession = $context->getSession();
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_ZipcodeCod::zipcodecod_grid');
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $id = $this->getRequest()->getParam('id');
        // print_r($id);
        // exit;

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        
        if ($data) {
            // print_r($data['zipcode']);
            $existZipCode = $this->helperData->getzipcode($data['zipcode']);
            $idZipCode = $this->helperData->getzipcodeID($id);
            // echo $idZipCode;

            if (!isset($id)) {
                if (!empty($existZipCode)) {
                    $this->messageManager->addError(__('Zip Code <b>"'.$data['zipcode'].'"</b> Already Exist.'));

                    $this->_backendSession->setPageData($data);
                    return $this->_redirect('*/*/edit');
                }
            }
            if (isset($id)) {
                if ($data['zipcode'] != $idZipCode) {
                    $existZipCode = $this->helperData->getzipcode($data['zipcode']);
                    if (!empty($existZipCode)) {
                        $this->messageManager->addError(__('Zip Code <b>"'.$data['zipcode'].'"</b> Already Exists.'));
                        
                        return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
                    }
                }
            }
            /** @var \Mageants\ZipcodeCod\Model\ZipcodeCod $model */
            $model = $this->_zipCodeModel;
            
            if (isset($data['stores'])) {
                if (in_array('0', $data['stores'])) {
                    $data['store_id'] = '0';
                } else {
                    $data['store_id'] = implode(",", $data['stores']);
                }
                unset($data['stores']);
            }
            
            // exit();
            if ($id) {
                $model->load($id);
            }
            $model->setData($data);
            try {
                $model->save();

                $this->messageManager->addSuccess(__('You saved this Item.'));
                $this->_backendSession->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the item.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}
