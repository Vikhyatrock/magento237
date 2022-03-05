<?php 
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model\ResourceModel\Cspm;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
/*
 * Collection class for Csmp
 */
class Collection extends AbstractCollection
{
	/**
     * @var string
     */
    protected $_idFieldName = 'entity_id';

	/**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Mageants\CSPM\Model\Cspm', 'Mageants\CSPM\Model\ResourceModel\Cspm');
    }
}