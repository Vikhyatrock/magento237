<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Order\Creditmemo\Create;

use Magento\Sales\Model\Order\Creditmemo;

/**
 * Storecredit class for store credit amount update on adminhtml creditmemo
 */
class Storecredit extends \Magento\Sales\Block\Adminhtml\Totals
{
    /**
     * @var Creditmemo|null
     */
    protected $_creditmemo;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Controller\Adminhtml\Order\CreditmemoLoader $creditmemoLoader,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->creditmemoLoader = $creditmemoLoader;
        $this->_scopeConfig = $scopeConfig;
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $registry, $adminHelper, $data);
    }
    
    public function getCreditmemo()
    {
        if ($this->_creditmemo === null) {
            if ($this->hasData('creditmemo')) {
                $this->_creditmemo = $this->_getData('creditmemo');
            } elseif ($this->_coreRegistry->registry('current_creditmemo')) {
                $this->_creditmemo = $this->_coreRegistry->registry('current_creditmemo');
            } elseif ($this->getParentBlock() && $this->getParentBlock()->getCreditmemo()) {
                $this->_creditmemo = $this->getParentBlock()->getCreditmemo();
            }
        }
        return $this->_creditmemo;
    }

    public function getSource()
    {
        return $this->getCreditmemo();
    }

    public function getStorecreditamount($return_refund_auto = false, $refund_store_credit = 0)
    {
        $refund_auto = $this->_scopeConfig->getValue('store_credit/general/refund_auto', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $store_credit_amount = 0;
        if (!empty($this->creditmemoLoader->getCreditmemo())) {
            $creditmemo_Data = $this->creditmemoLoader->getCreditmemo();
            if (array_key_exists("store_credit_return_amount", $creditmemo_Data)) {
                $store_credit_amount = $creditmemo_Data['store_credit_return_amount'];
            }
            if (array_key_exists("return_to_store_credit", $creditmemo_Data)) {
                $refund_auto = $creditmemo_Data['return_to_store_credit'];
            } else {
                $refund_auto = '0';
            }
        }
        if (empty($store_credit_amount) && $refund_auto == '1') {
            $store_credit_amount = $this->getSource()->getGrandTotal();
        } else {
            if ($store_credit_amount == 0) {
                $orderId = $this->getSource()->getOrderId();
                $order = $this->orderRepository->get($orderId);
                $store_credit_amount = $order->getdata('store_credit_amount');
            }
        }
        if ($return_refund_auto == true) {
            return $refund_auto;
        }
        if ($refund_store_credit == 1 && $store_credit_amount == 0) {
            $store_credit_amount = $this->getSource()->getGrandTotal();
        }
        return $store_credit_amount;
    }
    
    protected function _initTotals()
    {
        parent::_initTotals();
        $store_config_enable = $this->_scopeConfig->getValue('store_credit/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $refund_auto = $this->_scopeConfig->getValue('store_credit/general/refund_auto', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        if (!empty($this->creditmemoLoader->getCreditmemo())) {
            $creditmemo_Data = $this->creditmemoLoader->getCreditmemo();
            if (array_key_exists("return_to_store_credit", $creditmemo_Data)) {
                $refund_auto = $creditmemo_Data['return_to_store_credit'];
            } else {
                $refund_auto = '0';
            }
        }
        $this->addTotal(
            new \Magento\Framework\DataObject(
                [
                    'code' => 'adjustment_positive',
                    'value' => $this->getSource()->getAdjustmentPositive(),
                    'base_value' => $this->getSource()->getBaseAdjustmentPositive(),
                    'label' => __('Adjustment Refund'),
                ]
            )
        );
        $this->addTotal(
            new \Magento\Framework\DataObject(
                [
                    'code' => 'adjustment_negative',
                    'value' => $this->getSource()->getAdjustmentNegative(),
                    'base_value' => $this->getSource()->getBaseAdjustmentNegative(),
                    'label' => __('Adjustment Fee'),
                ]
            )
        );
        if ($store_config_enable == '1') {
            $this->addTotal(
                new \Magento\Framework\DataObject(
                    [
                        'code' => 'store_credit_return_amount',
                        'value' => -$this->getStorecreditamount(),
                        'base_value' => -$this->getStorecreditamount(),
                        'label' => __('Store Credit'),
                    ]
                )
            );
        }
        $this->_totals['grand_total'] = new \Magento\Framework\DataObject(
            [
                'code' => 'grand_total',
                'strong' => true,
                'value' => $this->getSource()->getGrandTotal() -  $this->getStorecreditamount(false, $refund_auto),
                'base_value' => $this->getSource()->getBaseGrandTotal() - $this->getStorecreditamount(false, $refund_auto),
                'label' => __('Grand Total'),
                'area' => 'footer',
            ]
        );
        return $this;
    }
}
