<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Model;

/**
 * Class InstagramCarousel
 * @package Mageants\InstagramIntegration\Model
 */
class InstagramCarousel extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init("Mageants\InstagramIntegration\Model\ResourceModel\InstagramCarousel");
    }
}
