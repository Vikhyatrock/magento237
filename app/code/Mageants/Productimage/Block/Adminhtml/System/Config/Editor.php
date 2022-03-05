<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Productimage\Block\Adminhtml\System\Config;

use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Cms\Model\Wysiwyg\Config as WysiwygConfig;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Editor extends \Magento\Config\Block\System\Config\Form\Field
{
/**
 * @var  Registry
 */
    protected $_coreRegistry;
/**
 * @param Context    $context
 * @param WysiwygConfig $wysiwygConfig
 * @param array      $data
 */
    public function __construct(
        Context $context,
        WysiwygConfig $wysiwygConfig,
        array $data = []
    ) {
         $this->_wysiwygConfig = $wysiwygConfig;
         parent::__construct($context, $data);
    }
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
         // set wysiwyg for element
        $element->setWysiwyg(true);
        $confgiData = $this->_wysiwygConfig->getConfig($element);
        $confgiData->setplugins([]);
        $confgiData->setadd_variables(0);
        $confgiData->setadd_widgets(0);
        $confgiData->setadd_images(0);
        // set configuration values
        
        $element->setConfig($confgiData);
         return parent::_getElementHtml($element);
    }
}
