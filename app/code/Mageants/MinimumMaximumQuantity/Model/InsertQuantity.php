<?php 
namespace Mageants\MinimumMaximumQuantity\Model;
class InsertQuantity extends \Magento\Framework\Model\AbstractModel{
	public function _construct(){
		$this->_init("Mageants\MinimumMaximumQuantity\Model\ResourceModel\InsertQuantity");
	}
}

/*class InsertQuantity extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
	const CACHE_TAG = 'mageplaza_helloworld_post';

	protected $_cacheTag = 'mageplaza_helloworld_post';

	protected $_eventPrefix = 'mageplaza_helloworld_post';

	protected function _construct()
	{
		$this->_init('Mageplaza\HelloWorld\Model\ResourceModel\Post');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}*/
 ?>