<?php
namespace Mageants\Customoptionimage\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CustomOptionData extends AbstractDb
{
    public function _construct()
    {
        $this->_init('catalog_product_option_type_value', 'option_type_id');
    }

    public function deleteOptionValue($vlId)
    {
        $this->getConnection()->delete($this->getMainTable(), ['option_type_id=?' => $vlId,]);
    }
}
