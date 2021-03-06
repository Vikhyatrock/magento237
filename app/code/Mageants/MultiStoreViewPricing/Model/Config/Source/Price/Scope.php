<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Model\Config\Source\Price;

class Scope extends \Magento\Catalog\Model\Config\Source\Price\Scope
{
    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function toOptionArray()
    {
        return [
        ['value' => '0',
        'label' => __('Global')],
        ['value' => '1',
        'label' => __('Website')],
        ['value' => '2',
        'label' => __('Storeview')]];
    }
}
