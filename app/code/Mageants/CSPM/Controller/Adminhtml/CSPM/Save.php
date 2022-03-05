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

/**
 * save CSPM Action
 */
class Save extends \Magento\Backend\App\Action
{
    /**
     * Managet Store
     *
     * @var \Mageants\CSPM\Model\Cspm
     */
    protected $_cspmModel;
    
	/**
	 * @param \Magento\Backend\Block\Template\Context 
	 * @param \Magento\Framework\App\Filesystem\DirectoryList
	 * @param \Mageants\CSPM\Model\Cspm
	 */
    public function __construct(Action\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Mageants\CSPM\Model\Cspm $cspmModel)
    {
        $this->directory_list = $directory_list;
        $this->_cspmModel = $cspmModel;
        parent::__construct($context);
    }

	
	/**
	 * {@inheritdoc}
	 */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Mageants_CSPM::save');
    }

	/**
	 * perform execute method for save Action
	 *
	 * @return $resultRedirect
	 */
    public function execute()
    {
        $data =$this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();
        if($data) 
        {
            $model=$this->_cspmModel;
            
            $collection=$this->_cspmModel->getCollection()
                        ->addFieldToFilter('cgid',$data['cgid'])
                        ->addFieldToFilter('website',$data['website']);
            if(sizeof($collection) > 0)
            {
                $data['entity_id']=$collection->getData()[0]['entity_id'];
            }
            if(isset($data['entity_id'])) 
            {
                $model->setId($data['entity_id']);
            }
            $sm="";
            if(isset($data['smethod']) && sizeof($data['smethod']) > 0 )
            {   
                $numItems = count($data['smethod']);
                $size=sizeof($data['smethod']);
                $i = 0;
                foreach ($data['smethod'] as $smethod) {
                    if($smethod=="0" && $size==1){$sm="0";}else{
                        if(++$i === $numItems){if($smethod!=="0"){$sm .= $smethod;}}else{if($smethod!=="0"){$sm .= $smethod.",";}}
                }
                }
            }
            
            $data['smethod']=$sm;
            $pm="";
            if(isset($data['pmethod']) && sizeof($data['pmethod']) > 0 )
            {
                $numItems = count($data['pmethod']);
                $size=sizeof($data['pmethod']);
                $i = 0;
                foreach ($data['pmethod'] as $pmethod) {
                    if($pmethod=="0" && $size==1){$pm="0";}else{
                    if(++$i === $numItems){if($pmethod!=="0"){$pm .= $pmethod;}}else{if($pmethod!=="0"){$pm .= $pmethod.",";}}
                    // if($size==1){$pm="0";}
                }
                }
            }
            $data['pmethod']=$pm;
            $model->setData($data);
            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved this Record.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the record.'));
            }

            $this->_getSession()->setFormData($data);
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
    
	
}
