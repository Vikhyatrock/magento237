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

class General extends \Magento\Backend\Block\Widget\Form\Generic implements
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
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Framework\Stdlib\DateTime\Timezone $datetime,
        \Magento\Customer\Model\ResourceModel\Group\Collection  $customergroup,
        array $data = []
    ) {
        $this->_wysiwygConfig = $wysiwygConfig;
        $this->_systemStore = $systemStore;
        $this->_localeDate = $localeDate;
        $this->_datetime = $datetime;
        $this->_fieldFactory = $fieldFactory;
        $this->_customergroup = $customergroup;
        $this->freeshippingbarfactory = $freeshippingbarfactory;
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
        $currentDateTime = $this->_datetime->date();
        $isElementDisabled = false;
        $groupOptions = $this->_customergroup->toOptionArray();
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('page_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('General')]);

        $htmlIdPrefix = $form->getHtmlIdPrefix();
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id', 'value' => 'id']);
        }
        $fieldset->addField(
            'name',
            'text',
            ['name' => 'name', 'label' => __('Name'), 'title' => __('Name'), 'required' => true]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'label' => 'Status',
                'name'  => 'status',
                'class' => '',
                'note'  => '',
                'style' => '',
                'required' => true,
                'values'=> [1 => 'Enabled' , 0 =>'Disabled']
            ]
        );
        $fieldset->addField(
            'priority',
            'text',
            ['name' => 'priority', 'label' => __('Priority'), 'title' => __('Priority'), 'required' => true]
        );
        $fieldset->addField(
            'storeview',
            'multiselect',
            [
             'name'     => 'storeview[]',
             'label'    => __('Store Views'),
             'title'    => __('Store Views'),
             'required' => true,
             'values'   => $this->_systemStore->getStoreValuesForForm(false, true),
            ]
        );
        $fieldset->addField(
            'customer_group',
            'multiselect',
            [
                'name' => 'customer_group[]',
                'label' => __('Customer Group'),
                'title' => __('Customer Group'),
                'required' => true,
                'values' => $groupOptions
            ]
        );
        $fieldset->addField(
            'fromdate',
            'text',
            [
                'name' => 'fromdate',
                'label' => __('From Date'),
                'title' => __('From Date'),
                'required' => true,
                'class' => '',
                'singleClick'=> true,
                'date_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'time_format' => 'HH:mm:ss',
                'readonly' => true,
                'format' =>$this->_localeDate->getDateFormat(\IntlDateFormatter::FULL)
            ]
        );
        $fieldset->addField(
            'todate',
            'text',
            [
                'name' => 'todate',
                'label' => __('To Date'),
                'title' => __('To Date'),
                'required' => true,
                'class' => '',
                'singleClick'=> true,
                'date_format' => \Magento\Framework\Stdlib\DateTime::DATE_INTERNAL_FORMAT,
                'time_format' => 'HH:mm:ss',
                'readonly' => true,
                'format' =>$this->_localeDate->getDateFormat(\IntlDateFormatter::LONG)
            ]
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
        return __('General');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('General');
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
