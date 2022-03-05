<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Model;

use Magento\Framework\Model\AbstractModel;

/**
 * StoreCredit Model class
 */
class StoreCredit extends AbstractModel
{
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Mageants\StoreCredit\Model\ResourceModel\StoreCredit');
    }
}
