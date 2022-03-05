<?php
 /**
 * @category  Mageants BannerSlider
 * @package   Mageants_BannerSlider
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@Mageants.com>
 */

namespace Mageants\BannerSlider\Model;

/**
 * Class Status
 * @package Mageants\BannerSlider\Model\Source
 */
class Height implements \Magento\Framework\Data\OptionSourceInterface
{

    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
                    ["value" => "","label" => __("Select Option")],
                    ["value" => 2,"label" => __("False")],
                    ["value" => 1,"label" => __("True")],
                ];
    }
}
