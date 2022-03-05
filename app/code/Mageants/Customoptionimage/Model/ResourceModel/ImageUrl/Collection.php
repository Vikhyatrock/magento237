<?php
namespace Mageants\Customoptionimage\Model\ResourceModel\ImageUrl;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            'Mageants\Customoptionimage\Model\ImageUrl',
            'Mageants\Customoptionimage\Model\ResourceModel\ImageUrl'
        );
    }
}
