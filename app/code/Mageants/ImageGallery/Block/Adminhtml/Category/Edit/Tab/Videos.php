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

class Videos extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Slide factory
     *
     * @var \Webspeaks\BannerSlider\Model\ResourceModel\Slide\CollectionFactory
     */
    protected $_videoCollectionFactory;
    protected $_categoryFactory;
    protected $_videoModel;
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
        \Mageants\ImageGallery\Model\ResourceModel\Video\CollectionFactory $videoCollectionFactory,
        \Mageants\ImageGallery\Model\Video $videoModel,
        \Mageants\ImageGallery\Model\CategoryFactory $categoryeFactory,
        array $data = []
    ) {
        $this->_videoCollectionFactory = $videoCollectionFactory;
        $this->_videoModel = $videoModel;
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
        $this->setId('videoGrid');
        $this->setDefaultSort('video_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
        if ($this->getRequest()->getParam('video_id')) {
            $this->setDefaultFilter(['in_video' => 1]);
        }
    }

    /**
     * add Column Filter To Collection
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_video') {
            $videoIds = $this->_getSelectedVideos();

            if (empty($videoIds)) {
                $videoIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('video_id', ['in' => $videoIds]);
            } else {
                if ($videoIds) {
                    $this->getCollection()->addFieldToFilter('video_id', ['nin' => $videoIds]);
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
        $collection = $this->_videoCollectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        /* @var $model \Webspeaks\BannerSlider\Model\Slide */
        $model = $this->_videoModel;

        $this->addColumn(
            'in_video',
            [
                'type' => 'checkbox',
                'name' => 'in_video',
                'align' => 'center',
                'index' => 'video_id',
                'values' => $this->_getSelectedVideos(),
                'filter_index' => 'main_table.video_id',
                'header_css_class' => 'col-select',
                'column_css_class' => 'col-select',
            ]
        );
        
        $this->addColumn(
            'video_title',
            [
                'header' => __('Title'),
                'index' => 'video_title',
                'width' => '50px',
            ]
        );
        
        $this->addColumn(
            'video',
            [
                'header' => __('Video'),
                'filter' => false,
                'width' => '100px',
                'renderer' => 'Mageants\ImageGallery\Block\Adminhtml\Category\Helper\Renderer\Video',
            ]
        );
        
        $this->addColumn(
            'video_is_active',
            [
                'header' => __('Status'),
                'index' => 'is_active',
                'type' => 'options',
                'filter_index' => 'main_table.is_active',
                'options' => $model->getAvailableStatuses(),
            ]
        );

        $this->addColumn(
            'video_order',
            [
                'header' => __('Order'),
                'name' => 'video_order',
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
        return $this->getUrl('*/*/videosgrid', ['_current' => true]);
    }

    /**
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return '';
    }

    public function getSelectedCategoryVideos()
    {
         
        $tm_id = $this->getRequest()->getParam('id');
        if (!isset($tm_id)) {
            $tm_id = 0;
        }

        // if you save product id in your custom table

        $collection = $this->_categoryFactory->create()->load($tm_id);
        $data =  $collection->getVideoId();
        $images = explode(',', $data);

        $imgIds = [];

        foreach ($images as $image) {
            $imgIds[$image] = ['id'=>$image];
        }
        return $imgIds;
    }

    protected function _getSelectedVideos()
    {
        $videos = $this->getRequest()->getParam('video');
        if (!is_array($videos)) {
            $videos = array_keys($this->getSelectedCategoryVideos());
        }

        return $videos;
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
