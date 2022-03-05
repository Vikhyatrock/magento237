<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Model\Total\Quote;

use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

/**
 * Customstorecredits class for store credit update grand total session
 */
class Customstorecredits extends AbstractTotal
{
    
    /**
     * price currency
     *
     * @var \Magento\Framework\Pricing\PriceCurrencyInterface
     */
    protected $_priceCurrency;
    
    /**
     * checkout session
     *
     * @var \Magento\Checkout\Model\Session
     */
    protected $_checkoutSession;
    
    /**
     * fees
     *
     * @var  String
     */
    protected $_fees;

    /**
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository
    ) {
        $this->_checkoutSession=$checkoutSession;
        $this->_priceCurrency = $priceCurrency;
        $this->_fees=0;
        $this->_scopeConfig = $scopeConfig;
        $this->_quoteRepository = $quoteRepository;
        $store_config_enable = $this->_scopeConfig->getValue('store_credit/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if ($store_config_enable == '0') {
            $this->_checkoutSession->unsStoreCreditbalance();
        }

        if ($this->_checkoutSession->getStoreCreditbalance()!=''):
            $this->_fees=$this->_checkoutSession->getStoreCreditbalance();
        endif;
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $items = $shippingAssignment->getItems();
        if (!count($items)) {
            return $this;
        }

        $this->_checkoutSession = $objectManager->create(\Magento\Checkout\Model\Session::class);
        $this->_fees=0;
        $store_config_enable = $this->_scopeConfig->getValue('store_credit/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        if ($this->_checkoutSession->getStoreCreditbalance()!='' && $store_config_enable != '0'):
            $this->_fees=$this->_checkoutSession->getStoreCreditbalance();
        endif;

        $discount = $this->_fees;
        //if(empty($quote->getCustomDiscount())){
            $total->addTotalAmount('storecredit', -$discount);
            $total->addBaseTotalAmount('storecredit', -$discount);
            $quote->setCustomDiscount(-$discount);
        //}
        return $this;
    }

    /**
     * @param Magento\Quote\Model\Quote\Address $total
     */
    protected function clearValues(Address\Total $total)
    {
        $total->setTotalAmount('subtotal', 0);
        $total->setBaseTotalAmount('subtotal', 0);
        $total->setTotalAmount('tax', 0);
        $total->setBaseTotalAmount('tax', 0);
        $total->setTotalAmount('discount_tax_compensation', 0);
        $total->setBaseTotalAmount('discount_tax_compensation', 0);
        $total->setTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setBaseTotalAmount('shipping_discount_tax_compensation', 0);
        $total->setSubtotalInclTax(0);
        $total->setBaseSubtotalInclTax(0);
    }
    
    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @param Address\Total $total
     * @return array|null
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        
        return [
            'code' => 'storecredit',
            'title' => 'storecredit',
            'value' => -$this->_fees
        ];
    }

    /**
     * Get Subtotal label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Store Credit');
    }
}
