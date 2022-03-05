<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            '0' => __('Template Default'),
            '1' => __('Template 1'),
            '2' => __('Template 2'),
            '3' => __('Template 3'),
            '4' => __('Template 4'),
            '5' => __('Template 5'),
            '6' => __('Template 6')
        ];
        return $options;
    }
}
