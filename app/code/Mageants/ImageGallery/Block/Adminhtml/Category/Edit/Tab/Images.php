<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Block\Adminhtml\Category\Edit\Tab;

use Magento\Backend\Block\Widget\Grid;
use Magento\Backend\Block\Widget\Grid\Column;
use Magento\Backend\Block\Widget\Grid\Extended;

class Images extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Slide factory
     *
     * @var \Webspeaks\BannerSlider\Model\ResourceModel\Slide\CollectionFactory
     */
    protected $_imageCollectionFactory;
    protected $_categoryFactory;
    protected $_imagemodel;
    protected $_objectManager = null;

    /**
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Webspeaks\BannerSlider\Model\ResourceModel\Slide\CollectionFactory $slideCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Mageants\ImageGallery\Model\ResourceModel\Gallery\CollectionFactory $imageCollectionFactory,
        \Mageants\ImageGallery\Model\Gallery $imagemodel,
        \Mageants\ImageGallery\Model\CategoryFactory $categoryeFactory,
        array $data = []
    ) {
        $this->_imageCollectionFactory = $imageCollectionFactory;
        $this->_imagemodel = $imagemodel;
        $this->_categoryFactory = $categoryeFactory;
        $this->_objectManager = $objectManager;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * _construct
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('imageGrid');
        $this->setDefaultSort('image_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('image_id')) {
            $this->setDefaultFilter(['in_image' => 1]);
        }
    }

    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_image') {
            $imageIds = $this->_getSelectedImages();

            if (empty($imageIds)) {
                $imageIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('image_id', ['in' => $imageIds]);
            } else {
                if ($imageIds) {
                    $this->getCollection()->addFieldToFilter('image_id', ['nin' => $imageIds]);
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
    protected function _prepareCollection()
    {
        /** @var \Webspeaks\BannerSlider\Model\ResourceModel\Slide\Collection $collection */
        $collection = $this->_imageCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        /* @var $model \Webspeaks\BannerSlider\Model\Slide */
        $model = $this->_imagemodel;

        $this->addColumn(
            'in_image',
            [
                'type' => 'checkbox',
                'name' => 'in_image',
                'align' => 'center',
                'index' => 'image_id',
                'values' => $this->_getSelectedImages(),
                'filter_index' => 'main_table.image_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select',
            ]
        );
        
        $this->addColumn(
            'image_title',
            [
                'header' => __('Title'),
                'index' => 'image_title',
                'width' => '50px',
            ]
        );
        
        $this->addColumn(
            'image',
            [
                'header' => __('Image'),
                'filter' => false,
                'width' => '100px',
                'renderer' => 'Mageants\ImageGallery\Block\Adminhtml\Category\Helper\Renderer\Image',
            ]
        );
        
        $this->addColumn(
            'image_is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'filter_index' => 'main_table.is_active',
                'options' => $model->getAvailableStatuses(),
            ]
        );

        $this->addColumn(
            'image_order',
            [
                'header' => __('Order'),
                'name' => 'image_order',
                'index' => 'order',
                'width' => '50px',
                'editable' => true,
                'column_css_class'=>'no-display',
                'header_css_class'=>'no-display',
            ]
        );
        
        

        return parent::_prepareColumns();
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/imagesgrid', ['_current' => true]);
    }

    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    public function getSelectedCategoryImages()
    {
        $tm_id = $this->getRequest()->getParam('id');
        if (!isset($tm_id)) {
            $tm_id = 0;
        }

        // if you save product id in your custom table

        $collection = $this->_categoryFactory->create()->load($tm_id);
        // echo "<pre>";
        // print_r(get_class_methods($collection));
        // exit();
        $data =  $collection->getImageId();
        $images = explode(',', $data);

        $imgIds = [];

        foreach ($images as $image) {
            $imgIds[$image] = ['id'=>$image];
        }
        return $imgIds;
    }

    protected function _getSelectedImages()
    {
        $images = $this->getRequest()->getParam('image');
        if (!is_array($images)) {
            $images = array_keys($this->getSelectedCategoryImages());
        }

        return $images;
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
