<?php
namespace Mageants\Customoptionimage\Model\Config\Source;

class View implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Show image when selected')],
            ['value' => 1, 'label' => __('Show all images')]
        ];
    }
}
