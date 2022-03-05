<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Model\System\Config\Source;

use Magento\Framework\Option\ArrayInterface;

/**
 * Class Showpopup
 * @package Mageants\InstagramIntegration\Model\System\Config\Source
 */
class Showpopup implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $showPopupArray = [
            ['label' => __('Title with Links'), 'value' => 'titlelink'],
            ['label' => __('Product Blocks'), 'value' => 'productblock'],
        ];

        return $showPopupArray;
    }
}
