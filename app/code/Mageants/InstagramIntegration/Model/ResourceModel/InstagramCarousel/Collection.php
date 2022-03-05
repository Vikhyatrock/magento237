<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Model\ResourceModel\InstagramCarousel;

/**
 * Class Collection
 * @package Mageants\InstagramIntegration\Model\ResourceModel\Instagram
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    const INSTAINTEGRATIONMODEL = 'Mageants\InstagramIntegration\Model\InstagramCarousel';

    const INSTAINTEGRATIONMODELCOLLECTION = 'Mageants\InstagramIntegration\Model\ResourceModel\InstagramCarousel';
    /**
     * Define module
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(self::INSTAINTEGRATIONMODEL, self::INSTAINTEGRATIONMODELCOLLECTION);
    }
}
