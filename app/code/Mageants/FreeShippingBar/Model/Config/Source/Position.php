<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Position implements OptionSourceInterface
{
    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            '0' => __('The top of the page'),
            '1' => __('Fixed bar at the top of the page'),
            '2' => __('top of the content'),
            '3' => __('Fixed bar at the bottom of the page')
        ];
        return $options;
    }
}
