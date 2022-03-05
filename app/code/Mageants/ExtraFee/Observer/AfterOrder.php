<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Observer;

use \Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;

/**
 * Order Gift observer for place order event
 */
class AfterOrder implements ObserverInterface
{

    /**
     * remove cookie after place order
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (isset($_COOKIE['shippingExtraFeeLabel'])) {
            unset($_COOKIE['shippingExtraFeeLabel']);
            setcookie('shippingExtraFeeLabel', '', time() - 3600, '/'); // empty value and old timestamp
        }
        
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_cookieManager=$objectManager->create("\Magento\Framework\Stdlib\CookieManagerInterface");
        $_cookieManager->deleteCookie(
            "shippingExtraFeeLabel"
        );
        $_cookieManager->deleteCookie(
            "shippingExtrafeeIds"
        );
        $_cookieManager->deleteCookie(
            "orderExtrafeeAmount"
        );
        $_cookieManager->deleteCookie(
            "orderExtraFeeLabel"
        );
    }
}
