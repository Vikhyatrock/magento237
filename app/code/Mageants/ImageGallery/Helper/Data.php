<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_category;
    protected $_objectManager;
    protected $_storeManager;
    protected $_categoryCollectionFactory;
    protected $_imageCollectionFactory;
    protected $_videoCollectionFactory;
    protected $scopeConfig;
    protected $_jsHelper;
    
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $_backendUrl;
        
    public function __construct(
        \Mageants\ImageGallery\Model\Category $category,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Helper\Js $jsHelper,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mageants\ImageGallery\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Mageants\ImageGallery\Model\ResourceModel\Gallery\CollectionFactory $imageCollectionFactory,
        \Mageants\ImageGallery\Model\ResourceModel\Video\CollectionFactory $videoCollectionFactory,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ) {
        $this->_category = $category;
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        $this->_jsHelper = $jsHelper;
        $this->scopeConfig = $scopeConfig;
        $this->_imageCollectionFactory = $imageCollectionFactory;
        $this->_videoCollectionFactory = $videoCollectionFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
        $this->_backendUrl = $backendUrl;
    }
    
    public function getCategoriesSelect()
    {
        $collection=$this->_category->getCollection()->addFieldToFilter('status', 1);
        $options=[];
        foreach ($collection as $category) {
                 $options[$category->getCatId()] = $category->getCatName();
        }
        return $options;
    }
    
    public function getCategories()
    {
        $collection = $this->_categoryCollectionFactory->create()->addFieldToFilter('is_active', 1);
        return $collection;
    }
    
    public function getCategoryById($Id)
    {
        $collection = $this->_categoryCollectionFactory->create()->addFieldToFilter('category_id', $Id);
        return $collection;
    }
    
    
    public function getCategoryImageUrl($categoryid)
    {
        $mediaUrl=$this->getMediaBaseUrl();
        $collection = $this->getCategories()
                            ->addFieldToFilter('category_id', $categoryid);
        if (!empty($collection)) {
            $image=$collection->getData();
            $images='';
            foreach ($image as $rows) {
                $images=$rows['image'];
            }
            return $mediaUrl.$images;
        }
    }
    
    public function getCategorieImageIds($categoryId)
    {
        $collection=$this->getCategories()
                        ->addFieldToFilter('category_id', $categoryId);
        $imageIds=$collection->getData();
       
        foreach ($imageIds as $rows) {
            $imageId=$rows['image_id'];
        }
        return explode(',', $imageId);
    }

    public function getCategorieVideoIds($categoryId)
    {
        $collection=$this->getCategories()
                        ->addFieldToFilter('category_id', $categoryId);
        $videosIds=$collection->getData();
        
        foreach ($videosIds as $rows) {
            $videoId = $rows['video_id'];
        }
        return explode(',', $videoId);
    }
    
    public function getGalleryImageUrl($imageid)
    {
        $mediaUrl=$this->getMediaBaseUrl();
        $collection = $this->_imageCollectionFactory->create()
                            ->addFieldToFilter('is_active', 1)
                            ->addFieldToFilter('image_id', $imageid);
        $count=$collection->count();
        if ($count) {
            $image=$collection->getData();
            $result=[];
            $name="";
            foreach ($image as $rows) {
                $result['url']=$mediaUrl.$rows['image'];
                $result['title']=$rows['image_title'];
            }
            
            return $result;
        }
    }
    
    public function getGalleryVideoUrl($videoId)
    {
        $mediaUrl=$this->getMediaBaseUrl();
        $collection = $this->_videoCollectionFactory->create()
                            ->addFieldToFilter('is_active', 1)
                            ->addFieldToFilter('video_id', $videoId);
        $count = $collection->count();
        if ($count) {
            $videos = $collection->getData();
            $result = [];
            $name = "";
            foreach ($videos as $rows) {
                $result['url'] = $mediaUrl.$rows['video'];
                $result['title'] = $rows['video_title'];
            }
            
            return $result;
        }
    }

    public function getVideoFormData($id)
    {
        $result = [];

        if ($id) {
            $mediaUrl=$this->getMediaBaseUrl();

            $collection = $this->_videoCollectionFactory->create()->addFieldToFilter('video_id', $id);

            $videos = $collection->getData();
    
            foreach ($videos as $rows) {

                if (!empty($rows['video'])) {

                    $result['videourl'] = $mediaUrl.$rows['video'];
                    $result['value'] = $rows['video'];
                }
            }
        }

        return $result;
    }

    public function getImageId($categoryImage_ids)
    {
         $collection = $this->_imageCollectionFactory->create()
                            ->addFieldToFilter('is_active', 1)
                            ->addFieldToSelect('image_id');
        $imageid=$collection->getData();
        //echo "<pre>"; print_r($imageid);
        $cat_imageIds=[];
        foreach ($imageid as $key => $value) {
            foreach ($value as $id => $val) {
                $cat_imageIds[]=$val;
            }
        }
        $imageGridSerializedInputData = $this->_jsHelper->decodeGridSerializedInput($categoryImage_ids);
        $imageIds = [];
        foreach ($imageGridSerializedInputData as $key => $value) {
            if (in_array($key, $cat_imageIds)) {
                $imageIds[] = $key;
            }
        }
        return implode(",", $imageIds);
    }

    public function getVideoId($categoryVideos_ids)
    {
         $collection = $this->_videoCollectionFactory->create()
                            ->addFieldToFilter('is_active', 1)
                            ->addFieldToSelect('video_id');
        $videosId=$collection->getData();
        //echo "<pre>"; print_r($videosId);
        $cat_videosIds=[];
        foreach ($videosId as $key => $value) {
            foreach ($value as $id => $val) {
                $cat_videosIds[]=$val;
            }
        }
        $videosGridSerializedInputData = $this->_jsHelper->decodeGridSerializedInput($categoryVideos_ids);
        $videosIds = [];

        foreach ($videosGridSerializedInputData as $key => $value) {
            if (in_array($key, $cat_videosIds)) {
                $videosIds[] = $key;
            }
        }
        return implode(",", $videosIds);
    }
    
    public function getMediaBaseUrl()
    {
        $currentStore = $this->_storeManager->getStore();
        return $currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    
    public function getImagesGridUrl()
    {
        return $this->_backendUrl->getUrl('imagegallery/category/images', ['_current' => true]);
    }

    public function getVideosGridUrl()
    {
        return $this->_backendUrl->getUrl('imagegallery/category/videos', ['_current' => true]);
    }
    
    public function IsEnabled()
    {
        return $this->scopeConfig->getValue(
            'mageants_imagegallery/imagegallery/enabled',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getHeaderTitle()
    {
        return $this->scopeConfig->getValue(
            'mageants_imagegallery/imagegallery/header_title',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    public function getHeaderBgColor()
    {
        return $this->scopeConfig->getValue(
            'mageants_imagegallery/imagegallery/header_bgcolor',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getHeaderForeColor()
    {
        return $this->scopeConfig->getValue(
            'mageants_imagegallery/imagegallery/header_forecolor',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getImageTitleBgColor()
    {
        return $this->scopeConfig->getValue(
            'mageants_imagegallery/imagegallery/image_bottom_bgcolor',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getImageTitleForeColor()
    {
        return $this->scopeConfig->getValue(
            'mageants_imagegallery/imagegallery/image_bottom_forecolor',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
