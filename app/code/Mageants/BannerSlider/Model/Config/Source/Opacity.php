<?php

namespace Mageants\BannerSlider\Model\Config\Source;

class Opacity implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => '0', 'label' => __('0')],
            ['value' => '0.1', 'label' => __('0.1')],
            ['value' => '0.2', 'label' => __('0.2')],
            ['value' => '0.3', 'label' => __('0.3')],
            ['value' => '0.4', 'label' => __('0.4')],
            ['value' => '0.5', 'label' => __('0.5')],
            ['value' => '0.6', 'label' => __('0.6')],
            ['value' => '0.7', 'label' => __('0.7')],
            ['value' => '0.8', 'label' => __('0.8')],
            ['value' => '0.9', 'label' => __('0.9')],
            ['value' => '1', 'label' => __('1')],
        ];
    }
}
