<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Block;

use Magento\Catalog\Block\Product\AbstractProduct;

/**
 * ProductFee Class
 */
class ProductFee extends AbstractProduct
{
    /**
     * Return product title
     */
    public function getProductTitle()
    {
        return $this->_scopeConfig->getValue('mageants_extrafee/setting/prd_title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Return category title
     */
    public function getCategoryTitle()
    {
        return $this->_scopeConfig->getValue('mageants_extrafee/setting/cat_title', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * Return store Id
     */
    public function getStoreId()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $_storeManager=$objectManager->create("\Magento\Store\Model\StoreManagerInterface");
        return $this->_storeManager->getStore()->getStoreId();
    }

    /**
     * Return Collection
     */
    public function getExtraFeeProductCollection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeId=$this->getStoreId();
        $storeIds=['0' =>'0','1'=>$storeId];
        $colModel = $objectManager->create('Mageants\ExtraFee\Model\ExtraFee');
        $collection = $colModel->getCollection()->addFieldToFilter('apply_to', 'Product')
        ->addFieldToFilter('estatus', 'Enabled')
        ->addFieldToFilter('store_id', ['in'=>$storeIds]);
        return $collection;
    }

    /**
     * Return Collection
     */
    public function getExtraFeeCategoryCollection()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeId=$this->getStoreId();
        $storeIds=['0' =>'0','1'=>$storeId];
        $colModel = $objectManager->create('Mageants\ExtraFee\Model\ExtraFee');
        $collection = $colModel->getCollection()->addFieldToFilter('apply_to', 'Category')
        ->addFieldToFilter('estatus', 'Enabled')
        ->addFieldToFilter('store_id', ['in'=>$storeIds]);
        return $collection;
    }
}
