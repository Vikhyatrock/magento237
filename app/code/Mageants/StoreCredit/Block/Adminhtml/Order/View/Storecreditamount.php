<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Order\View;

/**
 * Storecreditinvoiceamount class for store credit amount display on adminhtml invoice
 */
class Storecreditamount extends \Magento\Framework\View\Element\Template
{
    /**
     * @var $orderRepository
     */
    protected $orderRepository;
    
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->orderRepository = $orderRepository;
        parent::__construct($context, $data);
    }

    public function getOrderStorecreditAmount()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->orderRepository->get($orderId);
        $store_credit_amount = $priceHelper->currency($order->getdata('store_credit_amount'), true, false);

        return '-'.$store_credit_amount;
    }
}
