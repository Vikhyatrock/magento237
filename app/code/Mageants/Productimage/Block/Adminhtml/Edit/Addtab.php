<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Block\Adminhtml\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Addtab extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('image_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Edit Image'));
    }
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'edit_manage',
            [
                'label' => __('Edit Image'),
                'title' => __('Edit Image'),
                'active' => true,
            ]
        );
        return parent::_beforeToHtml();
    }
}
