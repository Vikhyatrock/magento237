<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Block\Order\Item\Renderer;

class DefaultRenderer extends \Magento\Sales\Block\Order\Item\Renderer\DefaultRenderer
{
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->setTemplate('Mageants_ExtraFee::order/items/renderer/default.phtml');
        return $this;
    }
}
