<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Block\Adminhtml\Update;

/**
 * Class Update
 * @package Mageants\InstagramIntegration\Block\Adminhtml\Update
 */
class Update extends \Magento\Backend\Block\Widget\Container
{
 
    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        $addButtonProps = [
            'label' => __('Update List'),
            'class' => 'update primary',
            'on_click' => sprintf("location.href = '%s';", $this->getUpdateUrl()),
        ];
        $this->addButton('insta_update_list', $addButtonProps);
 
        return parent::_prepareLayout();
    }
 
   /**
    * Get URL for back (reset) button
    *
    * @return string
    */
    public function getUpdateUrl()
    {
        $store['store'] = $this->getRequest()->getParam('store');
        return $this->getUrl('*/newimage/update', $store);
    }
}
