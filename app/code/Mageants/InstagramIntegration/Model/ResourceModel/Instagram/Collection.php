<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Model\ResourceModel\Instagram;

/**
 * Class Collection
 * @package Mageants\InstagramIntegration\Model\ResourceModel\Instagram
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';

    protected $_eventPrefix = 'mageants_instagramintegration_instagram_grid_collection';

    protected $_eventObject = 'instagram_grid_collection';

    /**
     * Define module
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(
            \Mageants\InstagramIntegration\Model\Instagram::class,
            \Mageants\InstagramIntegration\Model\ResourceModel\Instagram::class
        );
    }

    /**
     * Add approve image filter
     *
     * @return $this
     */
    public function addApproveFilter()
    {
        return $this;
    }
}
