<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Block\Adminhtml\Edit\Form;

use Magento\Store\Model\StoreManagerInterface;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Form extends \Magento\Backend\Block\Widget\Form\Generic implements TabInterface
{
    protected $productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\Data\FormFactory $formFactory,
        StoreManagerInterface $storemanager,
        \Mageants\Productimage\Model\GridFactory $gridFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Mageants\Productimage\Model\Status $status,
        \Mageants\Productimage\Ui\Component\Listing\Column\Cms\Options $options,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        $this->options = $options;
        $this->status = $status;
        $this->_systemStore = $systemStore;
        $this->_storeManager = $storemanager;
        $this->gridFactory = $gridFactory;
        parent::__construct($context, $coreRegistry, $formFactory, $data);
    }
    public function getModel()
    {
        $rowId = (int) $this->getRequest()->getParam('id');
        $rowData = $this->gridFactory->create();
        return $rowData->load($rowId);
    }
    protected function _prepareForm()
    {
        $modelData = $this->coreRegistry->registry('mageants_productimage_bycustomer');
        $model = $this->getModel();
        if ($model->getImage()) {
            $flag = true;
        }
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('field_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'class' => 'fieldset-wide'
            ]
        );
        $flag = true;
        if ($flag === true) {
            $categories = 1 ;
            $fieldset->addField(
                'status',
                'select',
                [
                'name' => 'status',
                'label' => __('Approved'),
                'title' => __('Status'),
                'values' => $this->status->getOptionEdit(),
                ]
            );
            $fieldset->addField(
                'customer_email',
                'text',
                [
                'name' => 'email',
                'label' => __('Customer Email'),
                'title' => __('Email'),
                'value' => $model->getCustomerEmail(),
                'disabled' => true,
                'required' =>true,
                ]
            );
            $fieldset->addType(
                'image',
                '\Mageants\Productimage\Block\Adminhtml\Customformfield\Edit\Renderer\CustomRenderer'
            );
            $fieldset->addField(
                'image',
                'image',
                [
                    'name' => 'image',
                    'label' => __('Image'),
                    'title' => __('Image'),
                ]
            );
            $fieldset->addField(
                'product_sku',
                'text',
                [
                'name' => 'productsku',
                'label' => __('Product SKU'),
                'title' => __('SKU'),
                'value' => $model->getProductSKU(),
                'disabled' => true,
                'required' =>true,
                ]
            );
            $fieldset->addField(
                'store_id',
                'multiselect',
                [
                'name' => 'store_id',
                'label' => __('Store View'),
                'title' => __('Store'),
                'values' => $this->options->toOptionArray(),
                'required' =>true,
                ]
            );
        }
        $data = $model->getData();
       
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }

    public function getTabLabel()
    {
        return _('Edit Image');
    }

    public function getTabTitle()
    {
        return __('Edit Image');
    }
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
}
