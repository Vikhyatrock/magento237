<?php
/**
 * @category Mageants Productimage
 * @package Mageants_Productimage
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\Productimage\Api\Data;
 
interface GridInterface
{
    const ID = 'id';
    const STATUS = 'status';
    const IMAGE = 'image';
    const PRODUCT_SKU = 'product_sku';
    const CUSTOMER_EMAIL = 'customer_email';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    const STORE_ID = 'store_id';
 
    public function getId();
    public function setId($Id);
 
    public function getStatus();
    public function setStatus($status);
 
    public function getImage();
    public function setImage($image);
 
    public function getProductSKU();
    public function setProductSKU($productsku);
 
    public function getCustomerEmail();
    public function setCustomerEmail($customerEmail);
 
    public function getCreatedAt();
    public function setCreatedAt($createdAt);

    public function getUpdatedAt();
    public function setUpdatedAt($updatedAt);

    public function getStoreId();
    public function setStoreId($store_id);
}
