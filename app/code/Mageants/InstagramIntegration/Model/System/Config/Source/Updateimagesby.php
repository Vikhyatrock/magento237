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
 * Class Updateimagesby
 * @package Mageants\InstagramIntegration\Model\System\Config\Source
 */
class Updateimagesby implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $updateImageByArray = [
            ['label' => __('User'), 'value' => 'user'],
            ['label' => __('Hashtag'), 'value' => 'hashtag'],
        ];

        return $updateImageByArray;
    }
}
