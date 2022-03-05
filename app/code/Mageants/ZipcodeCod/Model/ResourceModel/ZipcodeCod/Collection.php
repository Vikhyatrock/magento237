<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Model\ResourceModel\ZipcodeCod;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';

    /**
     * Define resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Mageants\ZipcodeCod\Model\ZipcodeCod::class,
            \Mageants\ZipcodeCod\Model\ResourceModel\ZipcodeCod::class
        );
    }
}
