<?php 
namespace Mageants\MinimumMaximumQuantity\Model\ResourceModel;
class InsertQuantity extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
 public function _construct(){
 $this->_init("mageants_minmaxqty","id");
 }
}


/*class InsertQuantity extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
	protected function _construct()
	{
		$this->_init('mageants_minmaxqty', 'id');
	}
}*/
 ?>