<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model\Config\Source;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Option\ArrayInterface;
use Magento\Catalog\Helper\Category;

/**
 * Return customer List for CSPM
 */
class Customerlist implements ArrayInterface
{
    /**
	 * Group collection
	 *
	 * @var \Magento\Customer\Model\ResourceModel\Group\Collection
	 */
    protected $_groupcollection;
	/**
	 * @param \Magento\Customer\Model\ResourceModel\Group\Collection
	 */
    public function __construct(\Magento\Customer\Model\ResourceModel\Group\Collection $groupCollection)
    {
    	$this->_groupcollection=$groupCollection;
    }
	
	/**
	 * Prepare Option Array 
	 *
	 * @return Array
	 */
	public function toOptionArray()
    {
		$groupOptions = $this->_groupcollection->toOptionArray();
		foreach ($groupOptions as $group)
        {
			$ret[] = [
                'value' => $group['value'],
                'label' => $group['label']
            ];
        }
		return $ret;
    }
}
