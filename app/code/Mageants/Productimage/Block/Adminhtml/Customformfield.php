<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Productimage\Block\Adminhtml;

class Customformfield extends \Magento\Backend\Block\Widget\Grid
{
    /**
     * @var \Mageants\Testimonials\Model\GridFactory
     */
    protected $productFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->productFactory = $productFactory;
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }
    
    protected function _prepareColumns()
    {
        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'index' => 'image',
                'renderer'  => 'Mageants\Productimage\Block\Adminhtml\Customformfield\Edit\Renderer\CustomRenderer',
            ]
        );
        return parent::_prepareColumns();
    }
}
