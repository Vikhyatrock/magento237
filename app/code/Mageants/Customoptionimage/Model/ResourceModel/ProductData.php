<?php
namespace Mageants\Customoptionimage\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductData extends AbstractDb
{
    public function _construct()
    {
        $this->_init('catalog_product_entity', 'sku');
    }
}
