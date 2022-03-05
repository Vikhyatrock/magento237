<?php

namespace Mageants\ImageGallery\Model\Config;

class Modes implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Retrieve option array
     *
     * @return string[]
     */
    public static function getOptionArray()
    {
        return [
            'fade' => __('Fade'),
            'horizontal' => __('Horizontal'),
            'vertical' => __('Vertical'),
        ];
    }

    /**
     * Retrieve option array with empty value
     *
     * @return string[]
     */
    public function getAllOptions()
    {
        $result = [];

        foreach (self::getOptionArray() as $index => $value) {
            $result[] = ['value' => $index, 'label' => $value];
        }

        return $result;
    }

    /**
     * Retrieve option text by option value
     *
     * @param string $optionId
     * @return string
     */
    public function getOptionText($optionId)
    {
        $options = self::getOptionArray();

        return isset($options[$optionId]) ? $options[$optionId] : null;
    }
    
    public function toOptionArray()
    {
        return $this->getOptionArray();
    }
}
