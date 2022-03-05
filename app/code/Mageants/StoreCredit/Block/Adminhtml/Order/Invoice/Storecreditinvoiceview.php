<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Order\Invoice;

use Magento\Sales\Model\Order\Invoice;

/**
 * Storecreditinvoiceamount class for store credit view on adminhtml invoice
 */
class Storecreditinvoiceview extends \Magento\Sales\Block\Adminhtml\Order\Invoice\Totals
{
    /**
     * @var Invoice|null
     */
    protected $_invoice = null;
    /**
     * @var $orderRepository
     */
    protected $orderRepository;

    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepository
     * @param \Magento\Sales\Helper\Admin $adminHelper
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Sales\Helper\Admin $adminHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->orderRepository = $orderRepository;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context, $registry, $adminHelper, $data);
    }
    
    public function getOrderStorecreditAmount($amount = false)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $store_credit= $this->getSource()->getOrder()->getdata('store_credit_amount');
        if (!empty($store_credit)) {
            if ($amount == true) {
                return $store_credit;
            } else {
                $store_credit_amount = $priceHelper->currency($store_credit, true, false);
                return '-'.$store_credit_amount;
            }
        } else {
            return '0';
        }
    }

    public function getSource()
    {
        return $this->getInvoice();
    }
}
