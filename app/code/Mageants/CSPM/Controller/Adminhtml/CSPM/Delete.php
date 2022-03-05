<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Controller\Adminhtml\CSPM;
use Magento\Backend\App\Action;
use Magento\TestFramework\ErrorLog\Logger;
use Mageants\CSPM\Model\Cspm;
/**
 * CSPM Delete Action
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var CSPM $_cspm
     */
    protected $_cspm;

    /**
     * @param Context $context
     * @param Cspm $_cspm
     */
    public function __construct(Action\Context $context,Cspm $_cspm)
    {
        $this->_cspm = $_cspm;
        parent::__construct($context);
    }

	/**
	 * {@inheritdoc}
	 */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_CSPM::cspm_delete');
    }
	
	/**
	 * perform delete Action
	 *
	 * @return $resultRedirect
	 */ 
    public function execute()
    {
        /**
         * Delete single record from admin Grid
         */            
        $id = $this->getRequest()->getParam('entity_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->_cspm;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('The Record has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['entity_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a record to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}