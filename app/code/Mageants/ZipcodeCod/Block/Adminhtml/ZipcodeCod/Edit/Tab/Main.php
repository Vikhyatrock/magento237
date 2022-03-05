<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Block\Adminhtml\ZipcodeCod\Edit\Tab;

class Main extends \Magento\Backend\Block\Widget\Form\Generic implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    public $store;

    /**
     * @var \Mageants\ZipcodeCod\Helper\Data $helper
     */
    public $helper;

    /**
     * @var \Magento\Store\Model\System\Store
     */
    public $systemStore;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Mageants\ZipcodeCod\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Mageants\ZipcodeCod\Helper\Data $helper,
        \Magento\Store\Model\System\Store $systemStore,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->_systemStore = $systemStore;
        $this->_backendSession = $context->getBackendSession();
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    public function _prepareForm()
    {
        /** @var $model \Mageants\ZipcodeCod\Model\ZipcodeCod */
        $model = $this->_coreRegistry->registry('mageants_zipcodecod');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix('zipcodecod_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Item Information')]);

        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        } else {
            $data = $this->_backendSession->getPageData(true);
            $this->_backendSession->setPage($data);
            if (!empty($data)) {
                $model->addData($data);
            }
        }

        $fieldset->addField(
            'zipcode',
            'text',
            [
                'name' => 'zipcode',
                'label' => __('Zipcode'),
                'class' => 'required-entry',
                'title' => __('Zipcode'),
                'required' => true,
            ]
        );
        
        $fieldset->addField(
            'city',
            'text',
            [
                'name' => 'city',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
            ]
        );
        
        $fieldset->addField(
            'estimated_delivery_time',
            'text',
            [
                'name' => 'estimated_delivery_time',
                'label' => __('Estimated Delivery Time'),
                'title' => __('Estimated Delivery Time'),
                'required' => true,
            ]
        );
        
        $fieldset->addField(
            'is_cod_available',
            'select',
            [
                'name' => 'is_cod_available',
                'label' => __('Is COD Available'),
                'title' => __('Is COD Available'),
                'required' => true,
                'options' => ['1' => __('Yes'), '0' => __('No')]
            ]
        );

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Main');
    }

    /**
     * Prepare title for tab
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Main');
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
    public function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
