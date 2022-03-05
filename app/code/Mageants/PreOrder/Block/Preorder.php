<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Block;

class Preorder extends \Magento\Framework\View\Element\Template
{
    protected $_product = null;

    protected $_registry;
    protected $_productFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockregistryinterface,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Mageants\PreOrder\Helper\Data $preorderhelper,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        array $data = []
    ) {
        $this->_registry = $registry;
        $this->_productFactory = $productFactory;
        $this->_stockregistryinterface = $stockregistryinterface;
        $this->_preorderhelper = $preorderhelper;
        $this->_productloader = $_productloader;
        $this->_orderFactory = $orderFactory;
        $this->stockItem = $stockItem;
        parent::__construct($context, $data);
    }


    public function getProductStockStatus()
    {
        $_product = $this->_registry->registry('current_product');
        $stockitemdata = $this->_stockregistryinterface->getStockItem($_product->getId(), $_product->getStore()->getWebsiteId());
        return $stockitemdata;
    }

    

    public function getProductStockStatusById($id)
    {
        $_product = $this->_productloader->create()->load($id);
        $stockitemdata = $this->_stockregistryinterface->getStockItem($_product->getId(), $_product->getStore()->getWebsiteId());
        return $stockitemdata;
    }

    public function getOrderDetailsById($id)
    {
        $order_contains_pre_order_item = 0;
        $order = $this->_orderFactory->create()->load($id);
        $items = $order->getAllItems();
        foreach ($items as $item) {
            if ($item->getProductOptionByCode('additional_options')) {
                $order_contains_pre_order_item = 1;
            }
        }
        return $order_contains_pre_order_item;
    }



    public function getACTIVE()
    {
        return $this->_preorderhelper->getACTIVE();
    }

    /**
     * @return int
     */
    public function getChangeStatus()
    {
        return $this->_preorderhelper->getChangeStatus();
    }

    /**
     * @return int
     */
    public function getAlloweOutofproduct()
    {
        return $this->_preorderhelper->getAlloweOutofproduct();
    }

    /**
     * @return int
     */
    public function getRestrictOrder()
    {
        return $this->_preorderhelper->getRestrictOrder();
    }

     /**
      * @return String
      */
    public function getCartButtonText()
    {
        return $this->_preorderhelper->getCartButtonText();
    }

    /**
     * @return String
     */
    public function getDefaultMessageForPreorder()
    {
        return $this->_preorderhelper->getDefaultMessageForPreorder();
    }

    /**
     * @return String
     */
    public function getNoteForPreorderIncart()
    {
        return $this->_preorderhelper->getNoteForPreorderIncart();
    }
}
