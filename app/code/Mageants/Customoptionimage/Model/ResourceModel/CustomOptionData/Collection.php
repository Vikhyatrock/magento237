<?php
namespace Mageants\Customoptionimage\Model\ResourceModel\CustomOptionData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            'Mageants\Customoptionimage\Model\CustomOptionData',
            'Mageants\Customoptionimage\Model\ResourceModel\CustomOptionData'
        );
    }
}
