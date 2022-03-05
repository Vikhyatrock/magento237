<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Observer;

class PreorderStatus implements \Magento\Framework\Event\ObserverInterface
{
    public function __construct(
        \Magento\Sales\Model\Order $order,
        \Mageants\PreOrder\Block\Preorder $preOrder
    ) {
         $this->_order= $order;
         $this->_preorder = $preOrder;
    }


    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        
        $event = $observer->getEvent();
        $orderIds = $event->getOrderIds();
        $order_id = $orderIds[0];
        $orderdata = $this->_order->load($order_id);
        $change_status = $this->_preorder->getChangeStatus();
        $items = $orderdata->getAllItems();
        $preorder_product = 0;
        $comman_product = 0;
        foreach ($items as $item) {
            
            $additionalOptions = $item->getProductOptions('product_options');
            // echo "<pre>";
            // var_dump($additionalOptions);
                            
            if (isset($additionalOptions['additional_options'])) {
                // if (is_array($additionalOptions['additional_options']) || $additionalOptions['additional_options'] instanceof Countable) {
                $preorder_product++;
            } else {
                $comman_product++;
            }
            // }
        }
        
        if ($preorder_product > 0 && $comman_product > 0) {
            if ($change_status == 1) {
                $orderdata = $this->_order->load($order_id);
                $orderdata->setState("new")->setStatus("pending_preorder");
                $orderdata->save();
            } else {
                $orderdata = $this->_order->load($order_id);
                $orderdata->setState("new")->setStatus("preorder");
                $orderdata->save();
            }
        } elseif ($preorder_product > 0) {
            $orderdata = $this->_order->load($order_id);
            $orderdata->setState("new")->setStatus("preorder");
            $orderdata->save();
        }
    }
}
