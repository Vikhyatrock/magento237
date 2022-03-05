<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Block\Adminhtml\Action\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

/**
 * Class Form
 * @package Mageants\InstagramIntegration\Block\Adminhtml\Action\Edit
 */
class Tabs extends WidgetTabs
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
        $this->setTitle(__('Image Information'));
    }
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'insta_info',
            [
                'label' => __('Image Setting'),
                'title' => __('Image Information'),
                'content' => $this->getLayout()->createBlock(
                    'Mageants\InstagramIntegration\Block\Adminhtml\Action\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );
        return parent::_beforeToHtml();
    }
}
