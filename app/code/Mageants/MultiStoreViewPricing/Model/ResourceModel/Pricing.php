<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Pricing ResourceModel class
 */
class Pricing extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Init resource Model
     */
    protected function _construct()
    {
        $this->_init('multi_store_view_pricing', 'id');
    }
}
