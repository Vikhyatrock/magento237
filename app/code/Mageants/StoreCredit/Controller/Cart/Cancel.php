<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Controller\Cart;

use Magento\Framework\View\Result\PageFactory;

/**
 * Cancel the Store Credit in checkout page
 */
class Cancel extends \Magento\Framework\App\Action\Action
{
    /**
     * @var $_checkoutSession
     */
    protected $_checkoutSession;
    /**
     * @var $storecreditfactory
     */
    protected $storecreditfactory;
    
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_storecreditfactory = $storecreditfactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data=$this->getRequest()->getPostValue();
        
        if ($data['cancel_store_credit'] == '1') {
            $this->_checkoutSession->unsStoreCreditbalance();
            $this->_checkoutSession->getQuote()->collectTotals()->save();
        }
    }
}
