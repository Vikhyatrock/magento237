<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Model;

use Magento\Framework\Exception\LocalizedException as CoreException;

/**
 * Pricing Model class
 */
class Pricing extends \Magento\Framework\Model\AbstractModel
{
    /**
     * init Model class
     */
    protected function _construct()
    {
        $this->_init(\Mageants\MultiStoreViewPricing\Model\ResourceModel\Pricing::class);
    }
}
