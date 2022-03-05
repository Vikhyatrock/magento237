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
class AfterOrderPlaced implements ObserverInterface
{

    const ORDER_EXTRAFEE_AMOUNT = 'orderExtrafeeAmount';
    const ORDER_EXTRAFEE_LABEL = 'orderExtraFeeLabel';
    const SHIPPING_EXTRAFEE_IDS = 'shippingExtrafeeIds';
    const SHIPPING_EXTRAFEE_LABEL = 'shippingExtraFeeLabel';
    const CODFEE = 'codFee';
    const MANDATORY_SHIPPING_EXTRAFEE = 'mandatoryShippingExtraFee';
    const MANDATORY_SHIPPING_AMOUNT = 'mandatoryShippingAmount';
    const MANDATORY_ORDER_EXTRAFEE = 'mandatoryOrderExtraFee';
    const MANDATORY_ORDER_EXTRAFEE_IDS = 'mandatoryOrderExtraFeeIdsStr';
    const ORDER_EXTRAFEE_IDS = 'orderExtrafeeId';
    /**
     * remove cookie after place order
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $cookieManager = $objectManager->get('Magento\Framework\Stdlib\CookieManagerInterface');
        $extraFeeHelper = $objectManager->get('Mageants\ExtraFee\Helper\Data');
        $orderFeeIds = $extraFeeHelper->getOrderFeeIds();
        $sessionManager = $objectManager->get('Magento\Framework\Session\SessionManagerInterface');
        $cookieMetadataFactory = $objectManager->get('Magento\Framework\Stdlib\Cookie\CookieMetadataFactory')
                                               ->createCookieMetadata()
                                               ->setPath($sessionManager->getCookiePath())
                                               ->setDomain($sessionManager->getCookieDomain());
        
        $shippingExtrafeeIds = $cookieManager->getCookie('shippingExtrafeeIds');
        $mandatoryShippingAmount = $cookieManager->getCookie('mandatoryShippingAmount');
        $extraFeeComment = $cookieManager->getCookie('extraFeeComment');

        /*if($shippingExtrafeeIds != null && $shippingExtrafeeIds!=''){
            $order->setShippingAmount($order->getShippingAmount() + $shippingExtrafeeIds);
        }
        else{*/
            $order->setShippingAmount($order->getShippingAmount());
        /*}
        if($mandatoryShippingAmount){
            $order->setShippingAmount($order->getShippingAmount() + $mandatoryShippingAmount);
        }
        else{
            $order->setShippingAmount($order->getShippingAmount());
        }*/
        $model = $objectManager->create('Mageants\ExtraFee\Model\ExtraFee');
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        $feesIds = explode(',', $orderFeeIds);
        $efeeids = [];
        $feePrice=0;
        $price=0;
        foreach ($feesIds as $feesId) {
            if ($feesId) {
                $ExtraFeeData=$model->load($feesId);
                if ($ExtraFeeData->getType()=='Percentage') {
                    $amount=($price*$ExtraFeeData->getAmount())/100;
                    $feePrice = $feePrice+$amount;
                } else {
                    $feePrice =  $feePrice+ $ExtraFeeData->getAmount();
                }
                $efeeids[] = $feesId.':'.$feePrice;
            }
            
        }
        $categorylable=$extraFeeHelper->getCategoryFeeLabels();
        $productlable=$extraFeeHelper->getProductFeeLabels();
        $efeeidsStr = implode(',', $efeeids);
        $order->setData('efeeids', $feePrice);
        $order->setData('categoryfeelable', $categorylable['catelable']);
        $order->setData('productfeelable', $productlable['prdlable']);
        $order->setData('categoryfeeapplyprdid', $categorylable['prdid']);
        $order->setData('productfeeapplyprdid', $productlable['prdid']);
        $order->setData('extrafeecomment', $extraFeeComment);
            $order->save();
            
        $cookieManager->deleteCookie(self::ORDER_EXTRAFEE_AMOUNT, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::ORDER_EXTRAFEE_LABEL, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::SHIPPING_EXTRAFEE_IDS, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::SHIPPING_EXTRAFEE_LABEL, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::CODFEE, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::MANDATORY_SHIPPING_EXTRAFEE, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::MANDATORY_SHIPPING_AMOUNT, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::MANDATORY_ORDER_EXTRAFEE, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::MANDATORY_ORDER_EXTRAFEE_IDS, $cookieMetadataFactory);
        $cookieManager->deleteCookie(self::ORDER_EXTRAFEE_IDS, $cookieMetadataFactory);
        $cookieManager->deleteCookie('extraFeeComment', $cookieMetadataFactory);
        $cookieManager->deleteCookie('categoryExtraFeeLabel', $cookieMetadataFactory);
    }
}
