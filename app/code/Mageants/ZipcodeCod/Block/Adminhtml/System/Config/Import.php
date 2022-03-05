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

class Import extends Field
{
    /**
     * @var string
     */
    protected $_template = 'Mageants_ZipcodeCod::system/config/import.phtml';
    
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
        $Element = $element;
        return $this->_toHtml();
    }
 
    /**
     * Return ajax url for collect button
     *
     * @return string
     */
    public function getAjaxUrl()
    {
        return $this->getUrl('zipcodecod/system_config/import');
    }
    
    /**
     * Returns html for block import
     */
    public function getFormHtml()
    {
        $html=parent::getFormHtml();
        $html.=$this->setTemplate('Mageants_ZipcodeCod::system/config/import.phtml')->toHtml();
        return $html;
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
                'id' => 'collect_import_button',
                'label' => __('Start Import'),
            ]
        );
        return $button->toHtml();
    }
}
