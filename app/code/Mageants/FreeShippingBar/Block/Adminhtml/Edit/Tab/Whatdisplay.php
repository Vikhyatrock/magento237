<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Block\Adminhtml\Edit\Tab;

use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Cms\Ui\Component\Listing\Column\Cms\Options;

class Whatdisplay extends \Magento\Backend\Block\Widget\Form\Generic implements
    \Magento\Backend\Block\Widget\Tab\TabInterface
{
    
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
    /**
     * @var Magento\Cms\Ui\Component\Listing\Column\Cms\Options
     */

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param   \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        Config $wysiwygConfig,
        \Magento\Config\Model\Config\Structure\Element\Dependency\FieldFactory $fieldFactory,
        \Mageants\FreeShippingBar\Model\FreeShippingBarFactory $freeshippingbarfactory,
        \Mageants\FreeShippingBar\Model\Config\Source\Options $options,
        \Mageants\FreeShippingBar\Model\Config\Source\Fonts $fonts,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_fieldFactory = $fieldFactory;
        $this->freeshippingbarfactory = $freeshippingbarfactory;
        $this->_options = $options;
        $this->fonts = $fonts;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        if ($this->getRequest()->getParam('id')) {
            $id = $this->getRequest()->getParam('id');
            $model = $this->freeshippingbarfactory->create()->load($id);
        } else {
            $model = $this->freeshippingbarfactory->create();
        }
        $isElementDisabled = false;
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('What To Display')]);
        $htmlIdPrefix = $form->getHtmlIdPrefix();
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id', 'value' => 'id']);
        }
        $fieldset->addField(
            'goal',
            'text',
            [
             'name' => 'goal',
             'label' => __('Goal'),
             'title' => __('Name'),
             'required' => true,
             'note' => 'Enter the free shipping threshold. Buyers whose orders reach this amount will receive delivery
              for free(It must be same for all)'
            ]
        );
        $fieldset->addField(
            'first_message',
            'text',
            [
             'name' => 'first_message',
             'label' => __('First Message'),
             'title' => __('First Message'),
             'value'  => __('Free Shipping for order over {{goal}}'),
             'required' => true,
             'note' => 'Enter the initial message in the free shipping bar displayed to buyers'
            ]
        );
        $fieldset->addField(
            'below_goal_message',
            'text',
            [
             'name'  => 'below_goal_message',
             'label' => __('Below Goal Message'),
             'title' => __('Below Goal Message'),
             'value'  => __('Only {{below_goal}} away for free shipping'),
             'required' => true,
             'note' => 'Enter the second message when buyers\' orders haven\'t reached the goal'
            ]
        );
        $fieldset->addField(
            'achive_goal_message',
            'text',
            [
             'name' => 'achive_goal_message',
             'label' => __('Achive Goal Message'),
             'title' => __('Achive Goal Message'),
             'value' => __('Congrats! You have got free shipping'),
             'required' => true,
             'note' => 'Enter the congratulation message when buyers\' orders reach the goal'
            ]
        );
        $clickable = $fieldset->addField(
            'clickable',
            'select',
            [
                'label' => 'Clickable',
                'name'  => 'clickable',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => false,
                'values'=> [1 => 'Yes' , 0 =>'No'],
                'note' => 'If Yes, the bar can be clicked to link to a different URL'
                ]
        );
        $linkurl = $fieldset->addField(
            'link_url',
            'text',
            [
             'name' => 'link_url',
             'label' => __('Link Url'),
             'title' => __('Link Url'),
             'required' => true,
             'note' => 'This can be the link to the Free Shipping policy page'
            ]
        );
        $openinnewpage = $fieldset->addField(
            'open_in_new_page',
            'select',
            [
                'label' => 'Open In New Page',
                'name'  => 'open_in_new_page',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => false,
                'values'=> [1 => 'Yes' , 0 =>'No'],
                 'note' => 'Select Yes to open the link in a new tab'
                ]
        );
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class)
            ->addFieldMap($clickable->getHtmlId(), $clickable->getName())
            ->addFieldMap($linkurl->getHtmlId(), $linkurl->getName())
            ->addFieldMap($openinnewpage->getHtmlId(), $openinnewpage->getName())
            ->addFieldDependence($linkurl->getName(), $clickable->getName(), 1)
            ->addFieldDependence($openinnewpage->getName(), $clickable->getName(), 1)
        );
        $fieldset = $form->addFieldset('new_fieldset', ['legend' => __('Design Template')]);
        $fieldset->addType('color', \Mageants\FreeShippingBar\Block\Adminhtml\Color::class);
        $fieldset->addField(
            'template',
            'select',
            [
                'label' => 'Template',
                'name'  => 'template',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => true,
                'values'=> $this->_options->toOptionArray(),
                ]
        );
        $fieldset->addField(
            'bar_background_opacity',
            'text',
            [
             'name' => 'bar_background_opacity',
             'label' => __('Bar Background Opacity'),
             'title' => __('Bar Background Opacity'),
             'required' => false,
             'note' => 'Limit value from 0 -> 1. If value higher than 1, system will use default value is 1'
            ]
        );
        $fieldset->addField(
            'bar_background_color',
            'color',
            [
              'name' => 'bar_background_color',
              'label' => __('Bar Background Color'),
              'title' => __('Bar Background Color'),
              'required' => true,
              'value' => '#0099e5'
                
            ]
        );
        // Remove field that is unnecessory
        /*$field = $fieldset->addField(
            'bar_text_color',
            'color',
            [
              'name' => 'bar_text_color',
              'label' => __('Bar Text Color'),
              'title' => __('Bar Text Color'),
              'required' => true,
              'value' => '#FFFFFF'
            ]
        );*/
        $field = $fieldset->addField(
            'bar_link_color',
            'color',
            [
              'name' => 'bar_link_color',
              'label' => __('Bar Link Color'),
              'title' => __('Bar Link Color'),
              'required' => true,
              'value' => '#F5FF0F'
            ]
        );
        $field = $fieldset->addField(
            'goal_text_color',
            'color',
            [
              'name' => 'goal_text_color',
              'label' => __('Goal Text Color'),
              'title' => __('Goal Text Color'),
              'required' => true,
              'value' => '#F5FF0F'
            ]
        );
        $fieldset->addField(
            'image',
            'image',
            [
              'name' => 'image',
              'label' => __('Image'),
              'title' => __('Image'),
              'required' => false,
              'note' => 'Recommended size: 150x50 px. Supported format: jpg, jpeg, png.'
            ]
        );
        $fieldset->addField(
            'fonts',
            'select',
            [
                'label' => 'Fonts',
                'name'  => 'fonts',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => true,
                'values'=> $this->fonts->toOptionArray(),
                ]
        );
        $fieldset->addField(
            'font_size',
            'text',
            [
              'name' => 'font_size',
              'label' => __('Font Size'),
              'title' => __('Font Size'),
              'required' => true
            ]
        );
        $fieldset = $form->addFieldset('preview_fieldset', ['legend' => __('Preview Template')]);
        if ($model->getData()) {
            $form->setValues($model->getData());
        }
        $this->setForm($form);
        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('What To Display');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('What To Display');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction()
    {
        return $this->_authorization->isAllowed("freeshippping");
    }

    /**
     * prepare form html
     *
     * @return $string
     */
    public function getFormHtml()
    {
        $html=parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(\Magento\Framework\View\Element\Template::class)->setTemplate(
            'Mageants_FreeShippingBar::template.phtml'
        )->toHtml();
        return $html;
    }
}
