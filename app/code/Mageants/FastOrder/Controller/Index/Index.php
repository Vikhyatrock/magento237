<?php
/**
 * @category Mageants FastOrder
 * @package Mageants_FastOrder
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FastOrder\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    protected $_helper;

    protected $_storeManager;
    
    /**
     * @param Context $context,
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory,
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Mageants\FastOrder\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\Customer\Source\Group $customerGroup
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->customerGroup = $customerGroup;
        parent::__construct($context);
    }
    
    /**
     * return page factory
     */
    public function execute()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $objectManager->create('Magento\Customer\Model\SessionFactory');
        $cust_group_conf = explode(',', $this->_helper->getFastOrderConfig('fastorder/general/enable_customer_group'));

        if (!in_array($customerSession->create()->getCustomerGroupId(), $cust_group_conf)) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            if (in_array(32000, $cust_group_conf)) {
                $resultPage = $this->_resultPageFactory->create();
                return $resultPage;
            } else {
                $resultRedirect->setPath($this->_storeManager->getStore()->getBaseUrl());
                return $resultRedirect;
            }
        }

        $resultPage = $this->_resultPageFactory->create();
        return $resultPage;
    }
}
