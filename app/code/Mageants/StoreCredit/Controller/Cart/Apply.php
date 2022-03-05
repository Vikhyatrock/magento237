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
 * Apply the Store Credit in checkout page
 */
class Apply extends \Magento\Framework\App\Action\Action
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
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Cart $cart
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_storecreditfactory = $storecreditfactory;
        $this->_scopeConfig = $scopeConfig;
        $this->cart = $cart;
        //$this->_quoteRepository = $quoteRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $result_return = $this->resultJsonFactory->create();
        $data=$this->getRequest()->getPostValue();
        
        //available store credit balance
        $id = $this->_customerSession->getCustomerId();
        $storecreditcollection = $this->_storecreditfactory->create()->getCollection()
               ->addFieldToSelect('new_balance')
               ->addFieldToFilter('customer_id', $id)
               ->setOrder(
                   'id',
                   'DESC'
               )
                ->setPageSize(1)
                ->getLastItem();
        $amount_data = $storecreditcollection->getData();
        
        $quote_subtotal_amount = $this->_checkoutSession->getQuote()->getSubtotal();
        $quote_shipping_amount = $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingAmount();
        $quote_tax_amount = $this->_checkoutSession->getQuote()->getShippingAddress()->getBaseTaxAmount();
        $apply_store_credit_on_shipping = $this->_scopeConfig->getValue('store_credit/general/apply_on_shipping', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $apply_store_credit_on_tax = $this->_scopeConfig->getValue('store_credit/general/apply_on_tax', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        if ($data['apply_amount'] != 0 && $data['apply_amount'] < 0) {
            $data['apply_amount'] = $data['apply_amount']*-1;
        }
        if ($data['apply_amount'] != 0 && !empty($amount_data['new_balance']) && $amount_data['new_balance'] >= $data['apply_amount']) {
            if ($quote_subtotal_amount >= $data['apply_amount']) {
                $this->_checkoutSession->setStoreCreditbalance($data['apply_amount']);
                $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$data['apply_amount']);
            }
            if ($quote_subtotal_amount < $data['apply_amount']) {
                if ($apply_store_credit_on_shipping == '0' && $apply_store_credit_on_tax == '0') {
                    $this->_checkoutSession->setStoreCreditbalance($quote_subtotal_amount);
                    $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$quote_subtotal_amount);
                } elseif ($apply_store_credit_on_shipping == '1' && $apply_store_credit_on_tax == '0') {
                    $subtotal_with_shipping = $quote_subtotal_amount + $quote_shipping_amount;
                    if ($subtotal_with_shipping >= $data['apply_amount']) {
                        $this->_checkoutSession->setStoreCreditbalance($data['apply_amount']);
                        $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$data['apply_amount']);
                    } else {
                        $this->_checkoutSession->setStoreCreditbalance($subtotal_with_shipping);
                        $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$subtotal_with_shipping);
                    }
                } elseif ($apply_store_credit_on_tax == '1' && $apply_store_credit_on_shipping == '0') {
                    $subtotal_with_tax = $quote_subtotal_amount + $quote_tax_amount;
                    if ($subtotal_with_tax >= $data['apply_amount']) {
                        $this->_checkoutSession->setStoreCreditbalance($data['apply_amount']);
                        $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$data['apply_amount']);
                    } else {
                        $this->_checkoutSession->setStoreCreditbalance($subtotal_with_tax);
                        $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$subtotal_with_tax);
                    }
                } elseif ($apply_store_credit_on_tax == '1' && $apply_store_credit_on_shipping == '1') {
                    $subtotal_with_shipping_tax = $quote_subtotal_amount + $quote_shipping_amount + $quote_tax_amount;
                    if ($subtotal_with_shipping_tax >= $data['apply_amount']) {
                        $this->_checkoutSession->setStoreCreditbalance($data['apply_amount']);
                        $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$data['apply_amount']);
                    } else {
                        $this->_checkoutSession->setStoreCreditbalance($subtotal_with_shipping_tax);
                        $this->_checkoutSession->setStoreCreditleftbalance($amount_data['new_balance']-$subtotal_with_shipping_tax);
                    }
                }
            }
            $this->_checkoutSession->getQuote()->collectTotals()->save();
        } else {
            if (empty($amount_data['new_balance'])) {
                $error= "";
                $result=[0=>'1',1=>$error];
                return $result_return->setData($result);
            } else {
                $this->_checkoutSession->unsStoreCreditbalance();
                $error= "<span style='color:#f00'>You have Only $".$amount_data['new_balance']." Store Credit</span>";
                $result=[0=>'1',1=>$error];
                return $result_return->setData($result);
            }
        }
    }
}
