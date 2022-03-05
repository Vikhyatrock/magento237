<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Fonts implements OptionSourceInterface
{
    /**
     * Get Grid row status type labels array.
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            'Roboto' => __('Roboto'),
            'Open Sans' => __('Open+Sans'),
            'Lato' => __('Lato'),
            'Ubuntu' => __('Ubuntu'),
            'Noto Sans' => __('Noto+Sans'),
            'Poppins' => __('Poppins'),
            'Noto Serif' => __('Noto+Serif'),
            'Inconsolata' => __('Inconsolata')
        ];
        return $options;
    }
}
