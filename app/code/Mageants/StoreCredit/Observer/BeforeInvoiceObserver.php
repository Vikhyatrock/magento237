<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * BeforeInvoiceObserver class for store credit amount added in grand total in adminhtml invoice
 */
class BeforeInvoiceObserver implements ObserverInterface
{
  /**
   * @var $messageManager
   */
    protected $messageManager;

  /**
   * @param \Magento\Framework\Message\ManagerInterface $messageManager
   */
    public function __construct(
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        $this->messageManager = $messageManager;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $invoice = $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $grand_total = $invoice->getGrandTotal();
        $base_grand_total = $invoice->getBaseGrandTotal();
        $invoice->setStoreCreditTotal($order->getdata('store_credit_amount'));
        $invoice->setGrandTotal($grand_total - $order->getdata('store_credit_amount'));
        $invoice->setBaseGrandTotal($base_grand_total - $order->getdata('store_credit_amount'));
    }
}
