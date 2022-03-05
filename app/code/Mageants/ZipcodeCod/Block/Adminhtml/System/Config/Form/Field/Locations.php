<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\ZipcodeCod\Block\Adminhtml\System\Config\Form\Field;

/**
 * Class Locations Backend system config array field renderer
 */
class Locations extends \Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray
{
    /**
     * @var Customergroup
     */
    public $groupRenderer;

    /**
     * Retrieve group column renderer
     *
     * @return Customergroup
     */
    public function _getGroupRenderer()
    {
        if (!$this->_groupRenderer) {
            $this->_groupRenderer = $this->getLayout()->createBlock(
                \Mageants\ZipcodeCod\Block\Adminhtml\System\Config\Form\Field\Select::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_groupRenderer->setClass('customer_group_select');
        }
        return $this->_groupRenderer;
    }

    public function _prepareToRender()
    {
        $this->addColumn(
            'postcode',
            ['label' => __('Postcode'), 'renderer' => false, 'class' => 'validate-no-empty','style' => 'width:150px']
        );
    
        $this->addColumn(
            'codavailable',
            ['label' => __('COD available'), 'renderer' => $this->_getGroupRenderer()]
        );
        $this->addColumn(
            'deliverydays',
            [
                'label' => __('Delivery Days'),
                'renderer' => false,
                'class' => 'validate-no-empty',
                'style' => 'width:150px'
            ]
        );

        $this->_addAfter = true;
        $this->_addButtonLabel = __('Add');
    }

    /**
     * Prepare existing row data object
     *
     * @param \Magento\Framework\DataObject $row
     * @return void
     */
    public function _prepareArrayRow(\Magento\Framework\DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getGroupRenderer()->calcOptionHash($row->getData('codavailable'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
