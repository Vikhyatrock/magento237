<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Block\Sales\Totals;

class Fee extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Mageants\Extrafee\Helper\Data
     */
    protected $_dataHelper;

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
        \Mageants\ExtraFee\Helper\Data $dataHelper,
        array $data = []
    ) {
        $this->_dataHelper = $dataHelper;
        parent::__construct($context, $data);
    }

    /**
     * Check if we nedd display full tax total info
     *
     * @return bool
     */
    public function displayFullSummary()
    {
        return true;
    }

    /**
     * Get data (totals) source model
     *
     * @return \Magento\Framework\DataObject
     */
    public function getSource()
    {
        return $this->_source;
    }

    public function getStore()
    {
        return $this->_order->getStore();
    }

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return array
     */
    public function getLabelProperties()
    {
        return $this->getParentBlock()->getLabelProperties();
    }

    /**
     * @return array
     */
    public function getValueProperties()
    {
        return $this->getParentBlock()->getValueProperties();
    }

    /**
     * @return $this
     */
    public function initTotals()
    {
        if($this->_dataHelper->getIsEnabled() == 1){
            $parent = $this->getParentBlock();
            $this->_order = $parent->getOrder();
            $this->_source = $parent->getSource();
            // $store = $this->getStore();
            
            $fee = new \Magento\Framework\DataObject(
                [
                    'code' => 'fee',
                    'strong' => false,
                    'value' => $this->_order->getFee(),
                    'label' => $this->_dataHelper->getFeeLabel(),
                ]
            );
            /*if($this->_order->getEfeeids() != 0){
                $orderfee = new \Magento\Framework\DataObject(
                    [
                        'code' => 'orderfee',
                        'strong' => false,
                        'value' => $this->_order->getEfeeids(),
                        'label' => 'Total Order Extra Fee',
                    ]
                );
                $parent->addTotal($orderfee, 'orderfee');
            }*/
            
            $parent->addTotal($fee, 'fee');

            return $this;
        }
    }
}
