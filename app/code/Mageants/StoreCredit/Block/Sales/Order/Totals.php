<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Sales\Order;

use Magento\Sales\Model\Order;

/**
 * Totals class for store credit amount display on front sales order view
 */
class Totals extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Order
     */
    protected $_order;
    /**
     * @var \Magento\Framework\DataObject
     */
    protected $_source;
    
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    
    public function getSource()
    {
        return $this->_source;
    }

    public function displayFullSummary()
    {
        return true;
    }
    
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $this->_order = $parent->getOrder();
        $this->_source = $parent->getSource();
        $title = 'Store Credit';
        $store = $this->getStore();
        if ($this->_order->getData('store_credit_amount')!=0) {
            $customAmount = new \Magento\Framework\DataObject(
                [
                        'code' => 'store_credit',
                        'strong' => false,
                        'value' => '-'.$this->_order->getData('store_credit_amount'),
                        'label' => __($title),
                    ]
            );
            $parent->addTotal($customAmount, 'store_credit');
        }
        return $this;
    }
    
    public function getStore()
    {
        return $this->_order->getStore();
    }
    
    public function getOrder()
    {
        return $this->_order;
    }
    
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }
   
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }
}
