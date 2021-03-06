<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mageants\OutofStockNotification\Helper\ConfigurableProduct;

use Magento\Catalog\Model\Product\Image\UrlBuilder;
use Magento\Framework\App\ObjectManager;
use Magento\Catalog\Helper\Image as ImageHelper;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Image;

/**
 * Class Data
 *
 * Helper class for getting options
 * @api
 * @since 100.0.2
 */
class Data extends \Magento\ConfigurableProduct\Helper\Data
{
    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * @var UrlBuilder
     */
    private $imageUrlBuilder;

    /**
     * @param ImageHelper $imageHelper
     * @param UrlBuilder $urlBuilder
     */
    public function __construct(ImageHelper $imageHelper, UrlBuilder $urlBuilder = null,
    \Mageants\OutofStockNotification\Block\Product\Notify $notifyBlock)
    {
        $this->imageHelper = $imageHelper;
        $this->imageUrlBuilder = $urlBuilder ?? ObjectManager::getInstance()->get(UrlBuilder::class);
        $this->_notifyBlock = $notifyBlock;
    }

    /**
     * Retrieve collection of gallery images
     *
     * @param ProductInterface $product
     * @return Image[]|null
     */
    public function getGalleryImages(ProductInterface $product)
    {

        $images = $product->getMediaGalleryImages();
        if ($images instanceof \Magento\Framework\Data\Collection) {
            /** @var $image Image */
            foreach ($images as $image) {
                $smallImageUrl = $this->imageUrlBuilder
                    ->getUrl($image->getFile(), 'product_page_image_small');
                $image->setData('small_image_url', $smallImageUrl);

                $mediumImageUrl = $this->imageUrlBuilder
                    ->getUrl($image->getFile(), 'product_page_image_medium');
                $image->setData('medium_image_url', $mediumImageUrl);

                $largeImageUrl = $this->imageUrlBuilder
                    ->getUrl($image->getFile(), 'product_page_image_large');
                $image->setData('large_image_url', $largeImageUrl);
            }
        }

        return $images;
    }

    /**
     * Get Options for Configurable Product Options
     *
     * @param \Magento\Catalog\Model\Product $currentProduct
     * @param array $allowedProducts
     * @return array
     */
   public function getOptions($currentProduct, $allowedProducts)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $stockRegistry = $objectManager->get('Magento\CatalogInventory\Api\StockRegistryInterface');
        $test = 0;
        $options = [];
        $allowAttributes = $this->getAllowAttributes($currentProduct);
        if ($this->_notifyBlock->getAllowSelectSimpleConfig() == 1) {
        foreach ($allowedProducts as $product) {
            $productId = $product->getId();
            $product = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);
            $stockitem = $stockRegistry->getStockItem($product->getId(), $product->getStore()->getWebsiteId());
            foreach ($allowAttributes as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();
                $attributeValue = $product->getData($productAttribute->getAttributeCode());
                
                $options[$productAttributeId][$attributeValue][] = $productId;
                $options['index'][$productId][$productAttributeId] = $attributeValue;
            }
        }
      }else{
         foreach ($allowedProducts as $product) {
            
            $productId = $product->getId();
            foreach ($allowAttributes as $attribute) {
                $productAttribute = $attribute->getProductAttribute();
                $productAttributeId = $productAttribute->getId();
                $attributeValue = $product->getData($productAttribute->getAttributeCode());
                if ($product->isSalable()) {
                    $options[$productAttributeId][$attributeValue][] = $productId;
                }
                $options['index'][$productId][$productAttributeId] = $attributeValue;
            }
        }
      }
        return $options;
    }

    /**
     * Get allowed attributes
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function getAllowAttributes($product)
    {
        return $product->getTypeInstance()->getConfigurableAttributes($product);
    }
}
