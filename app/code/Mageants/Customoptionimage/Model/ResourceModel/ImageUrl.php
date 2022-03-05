<?php
namespace Mageants\Customoptionimage\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ImageUrl extends AbstractDb
{
    public function _construct()
    {
        $this->_init('mageants_catalog_product_option_type_image', 'image_id');
    }
}
