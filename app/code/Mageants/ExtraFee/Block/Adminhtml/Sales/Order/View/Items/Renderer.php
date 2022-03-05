<?php
    /**
     * @category Mageants ExtraFee
     * @package Mageants_ExtraFee
     * @copyright Copyright (c) 2017 Mageants
     * @author Mageants Team <support@mageants.com>
     */

    namespace Mageants\ExtraFee\Block\Adminhtml\Sales\Order\View\Items;

class Renderer extends \Magento\Bundle\Block\Adminhtml\Sales\Order\View\Items\Renderer
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('Mageants_ExtraFee::order/view/items/renderer.phtml');
        return $this;
    }
}
