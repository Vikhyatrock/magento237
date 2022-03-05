<?php
namespace Mageants\Customoptionimage\Model\ResourceModel\OptionData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    public function _construct()
    {
        $this->_init(
            'Mageants\Customoptionimage\Model\OptionData',
            'Mageants\Customoptionimage\Model\ResourceModel\OptionData'
        );
    }
}
