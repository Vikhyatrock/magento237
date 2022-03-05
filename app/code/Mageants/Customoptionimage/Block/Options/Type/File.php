<?php
namespace Mageants\Customoptionimage\Block\Options\Type;

use Magento\Framework\View\Element\Template\Context as TemplateContext;
use Magento\Framework\Pricing\Helper\Data as PricingHelper;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Mageants\Customoptionimage\Helper\ModuleConfig;

class File extends \Magento\Catalog\Block\Product\View\Options\Type\File
{
    private $dataObjectFactory;

    public function __construct(
        TemplateContext $context,
        PricingHelper $pricingHelper,
        CatalogHelper $catalogData,
        \Magento\Framework\DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct($context, $pricingHelper, $catalogData, $data);
    }

    public function _construct()
    {
        parent::_construct();
        $this->setTemplate('Mageants_Customoptionimage::file.phtml');
    }

    public function getMageantsCustomOptionBlock($place)
    {
        $childObject = $this->dataObjectFactory->create();

        $this->_eventManager->dispatch(
            'mg_custom_options_render_file_' . $place,
            ['child' => $childObject]
        );
        $blocks = $childObject->getData() ?: [];
        $output = '';

        foreach ($blocks as $childBlock) {
            $block = $this->getLayout()->createBlock($childBlock);
            $block->setProduct($this->getProduct())->setOption($this->getOption());
            $output .= $block->toHtml();
        }
        return $output;
    }
}
