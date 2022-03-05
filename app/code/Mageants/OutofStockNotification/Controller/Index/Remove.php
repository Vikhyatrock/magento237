<?php
/**
 * @category Mageants Out Of Stock Notification
 * @package Mageants_OutOfStockNotification
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\OutofStockNotification\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Remove extends \Magento\Framework\App\Action\Action
{
    

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $_request;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $_messageManager;

    /**
     * @var \Mageants\OutofStockNotification\Model\Stocknotification
     */
    protected $_stockNotification;
    
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Mageants\OutofStockNotification\Model\Stocknotification $stockNotification
     */
    public function __construct(
        Context $context,                          
        \Magento\Framework\App\Request\Http $request,
        \Mageants\OutofStockNotification\Model\Stocknotification $stockNotification,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
    \Magento\Framework\App\Cache\Frontend\Pool $cacheFrontendPool
    )
    {       
        $this->_request = $request;  
        $this->_stockNotification = $stockNotification;
        $this->_messageManager = $context->getMessageManager();
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context);
    }
    
	/**
	 * return redirect at My OutOfStock Subscription
	 */
    public function execute()
    {	
        $sku = $this->_request->getParam('sku');
        $customerId = $this->_request->getParam('customer_id');
        //var_dump($customerId);exit();
        $collection = $this->_stockNotification->getCollection()->addFieldToFilter('product_sku', $sku)->addFieldToFilter('customer_id', $customerId);
        $collectionSize = $collection->getSize();

        foreach ($collection as $key => $value) {
            try{
                $this->_stockNotification->load($value->getId())->delete();
            }catch(Exception $e){
                $this->messageManager->addError($e->getMessage());    
            }
        }
        
        $types = array('layout','full_page');
            foreach ($types as $type) {
                $this->_cacheTypeList->cleanType($type);
            }
            foreach ($this->_cacheFrontendPool as $cacheFrontend) {
                $cacheFrontend->getBackend()->clean();
            }

        $this->messageManager->addSuccess(__('You are Remove Notify Product successfully'));

        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
	    $resultRedirect->setUrl($this->_redirect->getRefererUrl());
	    return $resultRedirect;
    }
}
