<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Block\Adminhtml\Cspm\Edit;
use \Magento\Cms\Ui\Component\Listing\Column\Cms\Options;

/**
 * CSPM Form
 */
class Form extends \Magento\Backend\Block\Widget\Form\Generic
{
     /**
     * helper
     *
     * @var \Mageants\CSPM\Helper\Data
     */
    protected $_helper;

     /**
     * Store View options
     */
    protected $_cmsOpt;

	/**
	 * @param \Magento\Backend\Block\Template\Context 
	 * @param \Magento\Framework\Registry 
	 * @param \Magento\Framework\Data\FormFactory
	 * @param \Magento\Store\Model\System\Store
	 * @param array $data
	 */
	public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Mageants\CSPM\Helper\Data $helper,
        Options $cmsOpt,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_cmsOpt               = $cmsOpt;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * constructur for the CSPM form
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('cspm_form');
        $this->setTitle(__('CSPM Information'));
    }
	
	/**
	 * Prepare form
	 *
	 * @return $this
	 */ 
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('CSPM_data');
		$form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post', 'enctype' => 'multipart/form-data']]
        );

        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('CSPM Information'), 'class' => 'fieldset-wide']
        );

        if ($model->getId()) {
            $fieldset->addField('entity_id', 'hidden', ['name' => 'entity_id']);
        }

        $fieldset->addField(
            'cstatus',
            'select',
            ['name' => 'cstatus', 'label' => __('Status'), 'title' => __('Status'), 'required' => false,
            'style'=>'min-width:210px;',
            'values'=>array("Enable"=>"Enable","Disable"=>"Disable") ]
        );
        
        /*$websitearray=$this->_helper->getWebsite();
        $fieldset->addField(
            'website',
            'select',
            ['name' => 'website', 'label' => __('Select Website'), 'title' => __('Select Website'), 'required' => false,
            'values'=>$websitearray ]
        );*/

        $fieldset->addField(
            'website',
            'select',
            [
                'name'  => 'website',
                'label' => __('Store View'),
                'title' => __('Store View'),
                'required' => true,
                'style'=>'min-width:210px;',
                'values' => $this->_cmsOpt->toOptionArray()
            ]
        );
        
        $cgidarray=$this->_helper->getCgid();
        $fieldset->addField(
            'cgid',
            'select',
            ['name' => 'cgid', 'label' => __('Customer Gruop'), 'title' => __('Customer Name'), 'required' => false,
            'style'=>'min-width:210px;',
            'values'=>$cgidarray ]
        );

        $smarray=$this->_helper->getShippingMethod();
        $fieldset->addField(
            'smethod',
            'multiselect',
            ['name' => 'smethod', 'label' => __('Shipping Method'), 'title' => __('Shipping Method'), 'required' => false,
            'style'=>'min-width:210px;',
            'values'=>$smarray ]
        );

        $pmarray=$this->_helper->getPaymentMethod();
        $fieldset->addField(
            'pmethod',
            'multiselect',
            ['name' => 'pmethod', 'label' => __('Payment Method'), 'title' => __('Payment Method'), 'required' => false,
            'style'=>'min-width:210px;',
            'values'=>$pmarray ]
        ); 

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
		return parent::_prepareForm();
    }
}