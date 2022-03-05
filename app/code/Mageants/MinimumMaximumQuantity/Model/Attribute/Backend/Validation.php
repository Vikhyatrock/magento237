<?php

namespace Mageants\MinimumMaximumQuantity\Model\Attribute\Backend;

/**
 * Class YourAttribute
 */
class Validation extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{

    /**
     * @var int $minimumValueLength
     */
    protected $minimumValueLength = 0;
    
    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return $this
     */
    public function afterLoad($object)
    {
        // your after load logic

        return parent::afterLoad($object);
    }

    /**
     * @param \Magento\Framework\DataObject $object
     *
     * @return $this
     */
    public function beforeSave($object)
    {
        $this->validateLength($object);

        return parent::beforeSave($object);
    }

    /**
     * Validate length
     *
     * @param \Magento\Framework\DataObject $object
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function validateLength($object)
    {
        /** @var string $attributeCode */
        $attributeCode = $this->getAttribute()->getAttributeCode();
        /** @var int $value */
        $value = $object->getData($attributeCode);
        
        /** @var int $minimumValueLength */
        $minimumValueLength = $this->getMinimumValueLength();
       if (!empty($value)) {
            if(is_numeric($value)){
                if ($value <= $minimumValueLength) {
                    if ($attributeCode == 'minimum_quantity') {
                        $attributeName = 'Minimum Quantity';
                    } else {
                        $attributeName = 'Maximum Quantity';
                    }
                    throw new \Magento\Framework\Exception\LocalizedException(
                        __('The value of attribute "%1" must be greater than %2', $attributeName, $minimumValueLength)
                    );
                }
            }
            if(!is_numeric($value)){
                if ($attributeCode == 'minimum_quantity') {
                    $attributeName = 'Minimum Quantity';
                } else {
                    $attributeName = 'Maximum Quantity';
                }
                 throw new \Magento\Framework\Exception\LocalizedException(
                    __('The value of attribute "%1" was not numeric', $attributeName, $value)
                );
            }

            if(!ctype_digit($value)){
                if ($attributeCode == 'minimum_quantity') {
                    $attributeName = 'Minimum Quantity';
                } else {
                    $attributeName = 'Maximum Quantity';
                }
                 throw new \Magento\Framework\Exception\LocalizedException(
                    __('The value of attribute "%1" was not Integer', $attributeName, $value)
                );
            }
        }

        return true;
    }

    /**
     * Get minimum attribute value length
     * 
     * @return int
     */
    public function getMinimumValueLength()
    {
        return $this->minimumValueLength;
    }
}