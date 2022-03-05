<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Model\ResourceModel\StoreCredit;
 
/**
 * Collection class of store credit
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Mageants\StoreCredit\Model\StoreCredit',
            'Mageants\StoreCredit\Model\ResourceModel\StoreCredit'
        );
    }
}
