<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Model\Config;
 
/**
 * Emailaction class for return array
 */
class Emailaction implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return Array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'admin_add_from_balance', 'label' => __('Administrator adds to store credit balance')],
            ['value' => 'admin_remove_from_balance', 'label' => __('Administrator removes from store credit balance')],
            ['value' => 'order_refund', 'label' => __('Order refund (paid with store credit)')],
            ['value' => 'order_place', 'label' => __('Order place (paid with store credit)')],
            ['value' => 'order_cancelation', 'label' => __('Order cancelation (paid with store credit)')]
        ];
    }
}
