<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Model;
 
use Magento\Framework\DataObject\IdentityInterface;
use Mageants\Productimage\Api\Data\GridInterface;

class Grid extends \Magento\Framework\Model\AbstractModel implements IdentityInterface, GridInterface
{
    const CACHE_TAG = 'mageants_productimage_bycustomer';
    protected $_cacheTag = 'mageants_productimage_bycustomer';
    protected $_eventPrefix = 'mageants_productimage_bycustomer';
 
    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('Mageants\Productimage\Model\ResourceModel\Grid');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
    /**
     * Get EntityId.
     *
     * @return int
     */
    public function getId()
    {
        return $this->getData(self::ID);
    }
 
    /**
     * Set EntityId.
     */
    public function setId($Id)
    {
        return $this->setData(self::ID, $Id);
    }
 
    /**
     * Get Title.
     *
     * @return varchar
     */
    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }
 
    /**
     * Set Title.
     */
    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
 
    /**
     * Get getContent.
     *
     * @return varchar
     */
    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }
 
    /**
     * Set Content.
     */
    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }
 
    /**
     * Get PublishDate.
     *
     * @return varchar
     */
    public function getProductSKU()
    {
        return $this->getData(self::PRODUCT_SKU);
    }
 
    /**
     * Set PublishDate.
     */
    public function setProductSKU($productsku)
    {
        return $this->setData(self::PRODUCT_SKU, $productsku);
    }
 
    /**
     * Get IsActive.
     *
     * @return varchar
     */
    public function getCustomerEmail()
    {
        return $this->getData(self::CUSTOMER_EMAIL);
    }
 
    /**
     * Set IsActive.
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }
    
    public function getStoreId()
    {
        return $this->getData(self::STORE_ID);
    }
 
    /**
     * Set IsActive.
     */
    public function setStoreId($store_id)
    {
        return $this->setData(self::STORE_ID, $store_id);
    }
    /**
     * Get UpdateTime.
     *
     * @return varchar
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }
 
    /**
     * Set UpdateTime.
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
 
    /**
     * Get CreatedAt.
     *
     * @return varchar
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }
 
    /**
     * Set CreatedAt.
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }
}
