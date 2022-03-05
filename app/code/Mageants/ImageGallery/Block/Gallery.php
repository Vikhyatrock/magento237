<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ImageGallery\Block;

class Gallery extends \Magento\Framework\View\Element\Template
{

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Mageants\ImageGallery\Helper\Data $helper
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Mageants\ImageGallery\Helper\Data $helper
    ) {
        $this->_helper = $helper;
        parent::__construct($context);
    }

    public function _prepareLayout()
    {

        
        $this->pageConfig->getTitle()->set(__($this->_helper->getHeaderTitle()));

        return parent::_prepareLayout();
    }
}
