<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Model\ResourceModel\Pricing;

/**
 * Pricing model collection
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * init constructor
     */
    protected function _construct()
    {
        $classFirst = \Mageants\MultiStoreViewPricing\Model\Pricing::class;
        $classSecond = \Mageants\MultiStoreViewPricing\Model\ResourceModel\Pricing::class;
        $this->_init($classFirst, $classSecond);
    }
}
