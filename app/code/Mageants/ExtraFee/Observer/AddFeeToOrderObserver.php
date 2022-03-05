<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddFeeToOrderObserver implements ObserverInterface
{
    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $_objectManager=\Magento\Framework\App\ObjectManager::getInstance();
        $_cookieManager=$_objectManager->create("Magento\Framework\Stdlib\CookieManagerInterface");
        $value=$_cookieManager->getCookie("orderExtrafeeAmount");
        $fname=$_cookieManager->getCookie("orderExtraFeeLabel");
        
        $quote = $observer->getQuote();
        $ExtrafeeFee = $quote->getFee();
        if (!$ExtrafeeFee) {
            return $this;
        }
        //Set fee data to order
        $order = $observer->getOrder();
        $order->setData('fee', $ExtrafeeFee);
        //$order->setData('efeeids',$fname);
        $order->setData('grand_total', $order->getGrandTotal());
        $_cookieManager->deleteCookie(
            "orderExtraFeeLabel"
        );
        return $this;
    }
}
