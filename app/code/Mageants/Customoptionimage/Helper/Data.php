<?php
namespace Mageants\Customoptionimage\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $adapterFactory;

    public $uploader;

    public $filesystem;

    public $scopeConfig;

    public $storeManager;

    public $storeId;

    public $request;

    public $resource;

    public $imageUrl;

    public $optionData;

    public $customOptionData;

    public $imgModel;

    public $productData;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mageants\Customoptionimage\Model\ImageUrlFactory $imageUrl,
        \Mageants\Customoptionimage\Model\ResourceModel\ImageUrl $imgModel,
        \Mageants\Customoptionimage\Model\OptionDataFactory $optionData,
        \Mageants\Customoptionimage\Model\CustomOptionDataFactory $customOptionData,
        \Mageants\Customoptionimage\Model\ProductDataFactory $productData
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->resource = $resource;
        $this->storeManager = $storeManager;
        $this->imageUrl = $imageUrl;
        $this->customOptionData = $customOptionData;
        $this->optionData = $optionData;
        $this->imgModel = $imgModel;
        $this->productData = $productData;
    }
    public function getImgDataById()
    {
        $imgUrl = $this->imageUrl->create();
        $imageUrls = $imgUrl->getCollection();
        $imageData = [];
        foreach ($imageUrls as $im) {
            $imageData[$im['option_type_image_id']][$im['option_type_id']] = $im['image_url'];
        }
        return $imageData;
    }
    public function getImgUrlList()
    {
        $imgUrl = $this->imageUrl->create();
        $imageUrls = $imgUrl->getCollection();
        $imgList = [];
        foreach ($imageUrls as $id => $im) {
            $imgList[$id] = $im['image_url'];
        }
        return $imgList;
    }
    public function getImgStringData()
    {
        $result = "";
        $imgUrl = $this->imageUrl->create();
        $values = $imgUrl->getCollection();
        foreach ($values as $key => $value) {
            $result .= $value['option_type_id'] . ',' . $value['image_url'] .';';
        }
        return $result;
    }
    public function getUrlData($productId)
    {
        $result = [];
        $options = $this->getOptionData($productId);
        $imgCollection = $this->imageUrl->create()->getCollection();
        foreach ($options as $okey => $option) {
            $result[$option->getOptionId()] = [];
            $valueWithImages = $imgCollection->getItemsByColumnValue('option_type_image_id', $option->getOptionId());
            foreach ($valueWithImages as $vkey => $value) {
                if ($value->getImageUrl() != '') {
                    $result[$option->getOptionId()][$value->getOptionTypeId()] = $value->getImageUrl();
                }
            }
        }
        return $result;
    }
    public function insertImageUrl($optionId, $optionTypeId, $imgUrl)
    {
        $this->imgModel->insertImgUrl($optionId, $optionTypeId, $imgUrl);
    }
    public function clearImageByOptionValue($vlId)
    {
        $this->imgModel->clearImageByOptionValue($vlId);
    }
    public function clearImageByOption($opId)
    {
        $this->imgModel->clearImageByOption($opId);
    }
    public function getOptionData($productId)
    {
        return $this->optionData->create()->getCollection()->setOrder('sort_order', 'ASC')
        ->getItemsByColumnValue('product_id', $productId);
    }
    public function getCustomOptionData($optionId)
    {
        return $this->customOptionData->create()->getCollection()->addFieldToFilter('option_id', $optionId);
    }
    public function getProductIdBySKU($SKU)
    {
        return $this->productData->create()->load($SKU);
    }
}
