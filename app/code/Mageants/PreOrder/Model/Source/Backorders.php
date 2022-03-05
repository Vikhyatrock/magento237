<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Model\Source;

/**
 * Back orders source class
 * @api
 * @since 100.0.2
 */
class Backorders extends \Magento\CatalogInventory\Model\Source\Backorders
{
    /**
     * @return array
     */

    const PRE_ORDER = 4;

    public function toOptionArray()
    {
        return [
            ['value' => \Magento\CatalogInventory\Model\Stock::BACKORDERS_NO, 'label' => __('No Backorders')],
            [
                'value' => \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NONOTIFY,
                'label' => __('Allow Qty Below 0')
            ],
            [
                'value' => \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NOTIFY,
                'label' => __('Allow Qty Below 0 and Notify Customer')
            ],
            [
                'value' => self::PRE_ORDER,
                'label' => __('Allow Pre-Orders')
            ]
        ];
    }
}
