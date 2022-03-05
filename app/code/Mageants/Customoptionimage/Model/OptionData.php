<?php
namespace Mageants\Customoptionimage\Model;

use Magento\Framework\Model\AbstractModel;

class OptionData extends AbstractModel
{
    public function _construct()
    {
        $this->_init('Mageants\Customoptionimage\Model\ResourceModel\OptionData');
    }
}
