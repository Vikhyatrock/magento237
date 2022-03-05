<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Block\Product\View\Type;

use Magento\Catalog\Block\Product\Context;
use Magento\Catalog\Helper\Product as CatalogProduct;
use Magento\Catalog\Model\Product;
use Magento\ConfigurableProduct\Helper\Data;
use Magento\ConfigurableProduct\Model\ConfigurableAttributeData;
use Magento\Customer\Helper\Session\CurrentCustomer;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\Stdlib\ArrayUtils;
use Magento\Store\Model\ScopeInterface;
use Magento\Swatches\Helper\Data as SwatchData;
use Magento\Swatches\Helper\Media;
use Magento\Swatches\Model\Swatch;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Locale\Format;
use Magento\Swatches\Model\SwatchAttributesProvider;

class Configurable extends \Magento\Swatches\Block\Product\Renderer\Configurable
{

    /**
     * Path to template file with Swatch renderer.
     */
    const SWATCH_RENDERER_TEMPLATE = 'Mageants_PreOrder::product/view/renderer.phtml';

    public function __construct(
        Context $context,
        ArrayUtils $arrayUtils,
        EncoderInterface $jsonEncoder,
        Data $helper,
        CatalogProduct $catalogProduct,
        CurrentCustomer $currentCustomer,
        PriceCurrencyInterface $priceCurrency,
        ConfigurableAttributeData $configurableAttributeData,
        SwatchData $swatchHelper,
        Media $swatchMediaHelper,
        array $data = [],
        SwatchAttributesProvider $swatchAttributesProvider = null,
        \Mageants\PreOrder\Block\Preorder $preOrder
    ) {
        $this->_preOrder = $preOrder;
        parent::__construct($context, $arrayUtils, $jsonEncoder, $helper, $catalogProduct, $currentCustomer, $priceCurrency, $configurableAttributeData, $swatchHelper, $swatchMediaHelper, $data, $swatchAttributesProvider);
    }


    public function getAllowProducts()
    {
    
        if (!$this->hasAllowProducts()) {
            $products = [];
            $skipSaleableCheck = $this->catalogProduct->getSkipSaleableCheck();
            $allProducts = $this->getProduct()->getTypeInstance()->getUsedProducts($this->getProduct(), null);
            foreach ($allProducts as $product) {
                $preorderstock = $this->_preOrder->getProductStockStatusById($product->getId());
                if ($this->_preOrder->getACTIVE()) {
                    if ($preorderstock->getBackorders() == 4) {
                        if ($preorderstock->getBackstockPreorders() == 1) {
                            $product->setData('is_salable', 1);
                        } elseif ($preorderstock->getBackstockPreorders() == 0) {
                            if ($this->_preOrder->getAlloweOutofproduct()) {
                                $product->setData('is_salable', 1);
                            }
                        }
                    }
                }
                
                if ($product->isSaleable() || $skipSaleableCheck) {
                    $products[] = $product;
                }
            }
            
            $this->setAllowProducts($products);
        }
        return $this->getData('allow_products');
    }
    /**
     * @codeCoverageIgnore
     * @return string
     */
    protected function getRendererTemplate()
    {
        return $this->isProductHasSwatchAttribute() ?
            self::SWATCH_RENDERER_TEMPLATE : self::CONFIGURABLE_RENDERER_TEMPLATE;
    }
}
