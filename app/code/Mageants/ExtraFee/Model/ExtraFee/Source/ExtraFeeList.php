<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Model\ExtraFee\Source;

/**
 * Return Extra fee list
 */
class ExtraFeeList extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    
    /**
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Catalog\Model\Category $categories
     */
    public function __construct(
        \Mageants\ExtraFee\Model\ExtraFee $feeCollection
    ) {
        $this->_collection = $feeCollection;
    }
  
    /**
     * @return Array
     */
    public function getAllOptions()
    {
        $collection = $this->_collection->getCollection()->addFieldToFilter('apply_to', 'Product')
        ->addFieldToFilter('estatus', 'Enabled');
        $options=[];
        foreach ($collection as $fee) {
            $feeData=$fee->getData();
            $options[$feeData['id']]=['value'=>$feeData['id'],'label'=>__($feeData['feesname'])];
        }
        return $options;
    }
}
