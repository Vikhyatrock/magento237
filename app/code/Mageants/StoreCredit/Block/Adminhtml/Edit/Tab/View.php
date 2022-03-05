<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Block\Adminhtml\Edit\Tab;
 
use Magento\Customer\Controller\RegistryConstants;
use Magento\Ui\Component\Layout\Tabs\TabInterface;
use Magento\Backend\Block\Widget\Form;
use Magento\Backend\Block\Widget\Form\Generic;
use Mageants\StoreCredit\Model\StoreCredit;

/**
 * Customer store credit tab form block
 */
class View extends Generic implements TabInterface
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Store\Model\System\Store $systemStore,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        $this->_systemStore = $systemStore;
        $this->_storecreditfactory = $storecreditfactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    public function getCustomerId()
    {
        return $this->_coreRegistry->registry(\Magento\Customer\Controller\RegistryConstants::CURRENT_CUSTOMER_ID);
    }

    public function getTabLabel()
    {
        return __('Store Credit');
    }
 
    public function getTabTitle()
    {
        return __('Store Credit');
    }
 
    public function canShowTab()
    {
        if ($this->getCustomerId()) {
            return true;
        }
        return false;
    }
 
    public function isHidden()
    {
        if ($this->getCustomerId()) {
            return false;
        }
        return true;
    }
 
    public function getTabClass()
    {
        return '';
    }
 
    public function getTabUrl()
    {
        return '';
    }
 
    public function isAjaxLoaded()
    {
        return false;
    }

    public function _prepareForm()
    {
        if (!$this->canShowTab()) {
            return $this;
        }
        $id = $this->getCustomerId();
        $credit_amount = '0';
        $collection = $this->_storecreditfactory->create()->getCollection()
                   ->addFieldToSelect('new_balance')
                   ->addFieldToFilter('customer_id', $id)
                   ->setOrder(
                       'id',
                       'DESC'
                   )
                    ->setPageSize(1)
                    ->getLastItem();
        $amount_data = $collection->getData();
        if (!empty($amount_data['new_balance'])) {
            $credit_amount = $amount_data['new_balance'];
        }
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        /**@var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('myform_');

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Store Credit')]);
        $profile = $this->getCollectiveProfile();

        $fieldset->addField(
            'balance',
            'label',
            [
                'name' => 'balance',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('Current Balance'),
                'title' => __('Current Balance'),
                'value' => $priceHelper->currency($credit_amount, true, false),
            ]
        );
        $fieldset->addField(
            'old_balance',
            'hidden',
            [
                'name' => 'old_balance',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('Old Balance'),
                'title' => __('Old Balance'),
                'value' => $credit_amount,
            ]
        );
        $fieldset->addField(
            'balance_change',
            'text',
            [
                'name' => 'balance_change',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('Add or substract a credit value $'),
                'title' => __('Add or substract a credit value $'),
                'value' => '',
                'note' => 'You can add or substract an amount from customers balance by entering a number. For example, enter "99.5" to add $99.5 and "-99.5" to subtract $99.5',
            ]
        );
        $fieldset->addField(
            'comment',
            'textarea',
            [
                'name' => 'comment',
                'data-form-part' => $this->getData('target_form'),
                'label' => __('Comment'),
                'title' => __('Comment'),
                'value' => '',
            ]
        );

        $this->setForm($form);
        return parent::_prepareForm();
    }

    protected function _toHtml()
    {
        if ($this->canShowTab()) {
            $this->_prepareForm();
            return parent::_toHtml();
        } else {
            return '';
        }
    }
    
    public function getFormHtml()
    {
        $html = parent::getFormHtml();
        $html .= $this->getLayout()->createBlock(
            'Mageants\StoreCredit\Block\Adminhtml\Grid\Grid'
        )->toHtml();
        return $html;
    }
}
