<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\ZipcodeCod\Block\Adminhtml\System\Config;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Export extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Mageants_ZipcodeCod::system/config/export.phtml';
    
    // phpcs:disable Generic.CodeAnalysis.UselessOverridingMethod
    /**
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }
    /**
     * Remove scope label
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }
 
    /**
     * Return element html
     *
     * @param  AbstractElement $element
     * @return string
     */
    public function _getElementHtml(AbstractElement $element)
    {
        $elementdata = $element;
        return $this->_toHtml();
    }
 
    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('zipcodecod/system_config/export');
    }

    /**
     * Generate collect button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock(
            \Magento\Backend\Block\Widget\Button::class
        )->setData(
            [
                'id' => 'collect_export_button',
                'label' => __('Export Data'),
            ]
        );
 
        return $button->toHtml();
    }
}
