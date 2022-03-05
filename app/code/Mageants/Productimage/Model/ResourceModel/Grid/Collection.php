<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Model\ResourceModel\Grid;
 
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Mageants\Productimage\Model\Grid::class,
            \Mageants\Productimage\Model\ResourceModel\Grid::class
        );
    }
}
