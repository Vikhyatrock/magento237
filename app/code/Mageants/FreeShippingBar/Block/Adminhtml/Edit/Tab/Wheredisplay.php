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

class Wheredisplay extends \Magento\Backend\Block\Widget\Form\Generic implements
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
        \Mageants\FreeShippingBar\Model\Config\Source\Position $position,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_fieldFactory = $fieldFactory;
        $this->freeshippingbarfactory = $freeshippingbarfactory;
        $this->position = $position;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        /* @var $model \Magento\Cms\Model\Page */
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

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Where To Display')]);
        
        $htmlIdPrefix = $form->getHtmlIdPrefix();
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id', 'value' => 'id']);
        }
        $fieldset->addField(
            'position',
            'select',
            [
                'label' => 'Position',
                'name'  => 'position',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => true,
                'values'=> $this->position->toOptionArray()
                ]
        );
        $allowpage = $fieldset->addField(
            'allow_page',
            'select',
            [
                'label' => 'Allow Page',
                'name'  => 'allow_page',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => true,
                'values'=> [1 => 'Specific Page' , 0 =>'All Pages'],
                'note' => 'Select page(s) to display the free shipping bar'
                ]
        );
        $specificpagetoshow = $fieldset->addField(
            'specific_page_to_show',
            'textarea',
            [
             'name' => 'specific_page_to_show',
             'label' => __('Which Page(s) To Show'),
             'title' => __('Which Page To Show'),
             'required' => false,
             'note' => 'Enter handle name(s) of page(s) to display free shipping bars'
            ]
        );
        $specificpageurl = $fieldset->addField(
            'specific_page_url',
            'textarea',
            [
                'label' => 'Include page(s) with URL(s) containing',
                'name'  => 'specific_page_url',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => false,
                'note' => 'Page(s) with URL(s) containing the above path(s) will be selected to display free shipping
                 bars'
                ]
        );
        $excludeAction = $fieldset->addField(
            'exclude_page',
            'textarea',
            [
             'name' => 'exclude_page',
             'label' => __('Exclude pages'),
             'title' => __('Exclude pages'),
             'required' => false,
             'note' => 'Enter handle name(s) of page(s) NOT to display free shipping bars'
            ]
        );
        $excludePageUrl = $fieldset->addField(
            'exclude_page_url',
            'textarea',
            [
                'label' => 'Exclude Page with URL contains',
                'name'  => 'exclude_page_url',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => false,
                'note' => 'Page(s) with URL(s) containing the above path(s) will not selected to display free shipping
                 bars'
                ]
        );
        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock(\Magento\Backend\Block\Widget\Form\Element\Dependence::class)
            ->addFieldMap($allowpage->getHtmlId(), $allowpage->getName())
            ->addFieldMap($excludeAction->getHtmlId(), $excludeAction->getName())
            ->addFieldMap($excludePageUrl->getHtmlId(), $excludePageUrl->getName())
            ->addFieldMap($specificpagetoshow->getHtmlId(), $specificpagetoshow->getName())
            ->addFieldMap($specificpageurl->getHtmlId(), $specificpageurl->getName())
            ->addFieldDependence($excludeAction->getName(), $allowpage->getName(), 0)
            ->addFieldDependence($excludePageUrl->getName(), $allowpage->getName(), 0)
            ->addFieldDependence($specificpagetoshow->getName(), $allowpage->getName(), 1)
            ->addFieldDependence($specificpageurl->getName(), $allowpage->getName(), 1)
        );
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
        return __('Where To Display');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Where To Display');
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
        return $html;
    }
}
