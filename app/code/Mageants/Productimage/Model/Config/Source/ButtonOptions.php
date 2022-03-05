<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class ButtonOptions implements OptionSourceInterface
{
 
    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        $options = ['1' => __('Default(Navigations)'),'0' => __('Dots')];
        return $options;
    }
}
