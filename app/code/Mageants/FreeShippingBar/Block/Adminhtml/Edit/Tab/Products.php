<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\FreeShippingBar\Block\Adminhtml\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;

class Products extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * @var \\Mageants\FreeShippingBar\Model\FreeShippingBarFactory $_freeShippingFactory
     */
    public $_freeShippingFactory;
    /**
     * core registry
     *
     * @var \Magento\Framework\Registry
     */
    public $coreRegistry = null;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     */
    public $productFactory;
 
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Registry $coreRegistry,
        \Mageants\FreeShippingBar\Model\FreeShippingBarFactory $freeShippingFactory,
        array $data = []
    ) {
        $this->productFactory = $productCollectionFactory;
        $this->_freeShippingFactory = $freeShippingFactory;
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $backendHelper, $data);
    }
 
    /**
     * Block constructor
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('product_grid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('id')) {
            $this->setDefaultFilter(['in_products' => 1]);
            $productIds = $this->_getSelectedProducts();
            if (!empty($productIds)) {
                $i = 1;
                foreach ($productIds as $value) {
                    if (!$value) {
                        $i = 0;
                    }
                }
                if ($i == 0) {
                    $this->setDefaultFilter(['in_products' => ""]);
                } else {
                    $this->setDefaultFilter(['in_products' => 1]);
                }
            }
        } else {
            // echo "Please Save First";
            return "Please Save First";

        }
    }
 
    /**
     * add Column Filter To Collection
     */
    public function _addColumnFilterToCollection($column)
    {
        // Set custom filter for in product flag
        if ($column->getId() == 'in_products') {
            $productIds = $this->_getSelectedProducts();
            if (empty($productIds)) {
                $productIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', ['in' => $productIds]);
            } else {
                if ($productIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', ['nin' => $productIds]);
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
    
    /**
     * prepare collection
     */
    public function _prepareCollection()
    {
        $collection = $this->productFactory->create()
            ->addAttributeToSelect('*')->joinField(
                'qty',
                'cataloginventory_stock_item',
                'qty',
                'product_id=entity_id',
                '{{table}}.stock_id=1',
                'left'
            );

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
 
    /**
     * @return $this
     */
    public function _prepareColumns()
    {
        $this->addColumn(
            'in_products',
            [
                'type' => 'checkbox',
                'name' => 'in_products',
                'align' => 'center',
                'index' => 'entity_id',
                'filter_index' => 'entity_id',
                'values' => $this->_getSelectedProducts(),
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select'
            ]
        );
        
        $this->addColumn(
            'entity_id',
            [
                'header' => __('Product ID'),
                'type' => 'number',
                'index' => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );
        
        $this->addColumn(
            'products_name',
            [
                'header' => __('Name'),
                'html_name' => 'product_name',
                'index' => 'name',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'sku',
            [
                'header' => __('Sku'),
                'index' => 'sku',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'price',
            [
                'header' => __('Price'),
                'type' => 'currency',
                'index' => 'price',
                'width' => '50px',
            ]
        );
        $this->addColumn(
            'qty',
            [
                'header' => __('Qty'),
                'index' => 'qty',
                'class' => 'xxx',
                'width' => '50px',
            ]
        );
 
        return parent::_prepareColumns();
    }
    
    /**
     * @return array
     */
    public function getVisibilityOptionArray()
    {
        return ['1' => 'Not Visible Individually', '2' => 'Catalog', '3' => 'Search', '4' => 'Catalog, Search'];
    }
 
    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('freeshippingbar/backend/productsgrid', ['_current' => true]);
    }
    
    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    public function _getSelectedProducts()
    {
        $allstore = $this->getAllShippingBar();
        return $allstore->getProducts($allstore);
    }

    /**
     * Retrieve selected products
     *
     * @return array
     */
    public function getSelectedProducts()
    {
        $allstore = $this->getAllShippingBar();
        $selected = $allstore->getProducts($allstore);
        if (!is_array($selected)) {
            $selected = [];
        }
        return $selected;
    }

    public function getAllShippingBar()
    {
        $allstoreId = $this->getRequest()->getParam('id');

        $allstore   = $this->_freeShippingFactory->create();
        if ($allstoreId) {
            $allstore->load($allstoreId);
        }
        return $allstore;
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return true;
    }
}
