<?php
namespace Mageants\Customoptionimage\Model;

use Magento\Framework\Model\AbstractModel;

class CustomOptionData extends AbstractModel
{
    public function _construct()
    {
        $this->_init('Mageants\Customoptionimage\Model\ResourceModel\CustomOptionData');
    }
}
