<?php
 /**
 * @category  Mageants BannerSlider
 * @package   Mageants_BannerSlider
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@Mageants.com>
 */
 
namespace Mageants\BannerSlider\Model;
 
class Slides extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Model Initialization
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
		
		$this->_init('Mageants\BannerSlider\Model\ResourceModel\Slides');
    }
	
    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
    
    public function getProducts(\Mageants\BannerSlider\Model\Slides $object)
    {
        $tbl = $this->getResource()->getTable("mageants_bannerslider_slides");
        $select = $this->getResource()->getConnection()->select()->from(
            $tbl,
            ['product_ids']
        )
        ->where(
            'id = ?',
            (int)$object->getId()
        );
        
        $productIds = $this->getResource()->getConnection()->fetchCol($select);
        $productIds = (is_array($productIds) && count($productIds)) ? explode(',', $productIds[0]) : [];
        return $productIds;
    }

}
