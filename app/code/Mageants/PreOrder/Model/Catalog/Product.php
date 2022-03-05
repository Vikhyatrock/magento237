<?php
 /**
  * @category Mageants PreOrder
  * @package Mageants_PreOrder
  * @copyright Copyright (c) 2018  Mageants
  * @author Mageants Team <support@mageants.com>
  */
namespace Mageants\PreOrder\Model\Catalog;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Api\Data\ProductAttributeMediaGalleryEntryInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Backend\Media\EntryConverterPool;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Pricing\SaleableInterface;

class Product extends \Magento\Catalog\Model\Product
{
    
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $metadataService,
        \Magento\Catalog\Model\Product\Url $url,
        \Magento\Catalog\Model\Product\Link $productLink,
        \Magento\Catalog\Model\Product\Configuration\Item\OptionFactory $itemOptionFactory,
        \Magento\CatalogInventory\Api\Data\StockItemInterfaceFactory $stockItemFactory,
        \Magento\Catalog\Model\Product\OptionFactory $catalogProductOptionFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $catalogProductStatus,
        \Magento\Catalog\Model\Product\Media\Config $catalogProductMediaConfig,
        \Magento\Catalog\Model\Product\Type $catalogProductType,
        \Magento\Framework\Module\Manager $moduleManager,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Catalog\Model\ResourceModel\Product $resource,
        \Magento\Catalog\Model\ResourceModel\Product\Collection $resourceCollection,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\Catalog\Model\Indexer\Product\Flat\Processor $productFlatIndexerProcessor,
        \Magento\Catalog\Model\Indexer\Product\Price\Processor $productPriceIndexerProcessor,
        \Magento\Catalog\Model\Indexer\Product\Eav\Processor $productEavIndexerProcessor,
        CategoryRepositoryInterface $categoryRepository,
        \Magento\Catalog\Model\Product\Image\CacheFactory $imageCacheFactory,
        \Magento\Catalog\Model\ProductLink\CollectionProvider $entityCollectionProvider,
        \Magento\Catalog\Model\Product\LinkTypeProvider $linkTypeProvider,
        \Magento\Catalog\Api\Data\ProductLinkInterfaceFactory $productLinkFactory,
        \Magento\Catalog\Api\Data\ProductLinkExtensionFactory $productLinkExtensionFactory,
        EntryConverterPool $mediaGalleryEntryConverterPool,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface $joinProcessor,
        \Mageants\PreOrder\Block\Preorder $preOrder,
        array $data = []
    ) {
        $this->preOrder = $preOrder;
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $storeManager, $metadataService, $url, $productLink, $itemOptionFactory, $stockItemFactory, $catalogProductOptionFactory, $catalogProductVisibility, $catalogProductStatus, $catalogProductMediaConfig, $catalogProductType, $moduleManager, $catalogProduct, $resource, $resourceCollection, $collectionFactory, $filesystem, $indexerRegistry, $productFlatIndexerProcessor, $productPriceIndexerProcessor, $productEavIndexerProcessor, $categoryRepository, $imageCacheFactory, $entityCollectionProvider, $linkTypeProvider, $productLinkFactory, $productLinkExtensionFactory, $mediaGalleryEntryConverterPool, $dataObjectHelper, $joinProcessor, $data);
    }

    public function isSalable()
    {
        $_preorderblock=$this->preOrder;

        $preorderstock = $_preorderblock->getProductStockStatusById($this->getId());
        if ($this->preOrder->getACTIVE()) {
            if ($preorderstock->getBackorders() == 4) {
                if ($preorderstock->getBackstockPreorders() == 1) {
                    return 1;
                } elseif ($preorderstock->getBackstockPreorders() == 0) {
                    if ($_preorderblock->getAlloweOutofproduct()) {
                        return 1;
                    }
                }
            }
        }
        

        if ($this->_catalogProduct->getSkipSaleableCheck()) {
            return true;
        }

        if (($this->getOrigData('status') != $this->getData('status'))
            || $this->isStockStatusChanged()) {
            $this->unsetData('salable');
        }

        if ($this->hasData('salable')) {
            return $this->getData('salable');
        }
        $this->_eventManager->dispatch('catalog_product_is_salable_before', ['product' => $this]);

        $salable = $this->isAvailable();

        $object = new \Magento\Framework\DataObject(['product' => $this, 'is_salable' => $salable]);
        $this->_eventManager->dispatch(
            'catalog_product_is_salable_after',
            ['product' => $this, 'salable' => $object]
        );
        $this->setData('salable', $object->getIsSalable());
       
        return $this->getData('salable');
    }


    private function isStockStatusChanged()
    {
        $stockItem = null;
        $extendedAttributes = $this->getExtensionAttributes();
        if ($extendedAttributes !== null) {
            $stockItem = $extendedAttributes->getStockItem();
        }
        $stockData = $this->getStockData();
        return (
            (is_array($stockData))
            && array_key_exists('is_in_stock', $stockData)
            && (null !== $stockItem)
            && ($stockItem->getIsInStock() != $stockData['is_in_stock'])
        );
    }
}
