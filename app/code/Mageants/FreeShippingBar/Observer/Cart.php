<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;

class Cart implements ObserverInterface
{
    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        Session $checkoutSession
    ) {
        $this->cart = $cart;
        $this->_checkoutSession = $checkoutSession;
    }
    public function getTotalCartPrice()
    {
        $quote = $this->cart->getQuote();
        $quote->setTotalsCollectedFlag(false)->collectTotals();
        return $quote->getData('subtotal');
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->getTotalCartPrice();
    }
}
