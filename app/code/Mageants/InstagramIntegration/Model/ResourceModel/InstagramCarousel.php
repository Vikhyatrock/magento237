<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Model\ResourceModel;

/**
 * Class InstagramCarousel
 * @package Mageants\InstagramIntegration\Model\ResourceModel
 */
class InstagramCarousel extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialization
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init("instagram_carousel_media", "id");
    }
}
