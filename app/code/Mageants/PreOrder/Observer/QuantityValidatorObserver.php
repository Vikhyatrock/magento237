<?php
/**
 * Product inventory data validator
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageants\PreOrder\Observer;

use Magento\Framework\Event\ObserverInterface;

class QuantityValidatorObserver implements ObserverInterface
{
    /**
     * @var \Magento\CatalogInventory\Model\Quote\Item\QuantityValidator $quantityValidator
     */
    protected $quantityValidator;

    /**
     * @param \Magento\CatalogInventory\Model\Quote\Item\QuantityValidator $quantityValidator
     */
    public function __construct(
        \Mageants\PreOrder\Model\Quote\Item\QuantityValidator $quantityValidator
    ) {
        $this->quantityValidator = $quantityValidator;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->quantityValidator->validate($observer);
    }
}
