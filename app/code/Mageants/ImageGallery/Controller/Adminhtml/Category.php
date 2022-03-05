<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Filesystem;

abstract class Category extends \Magento\Backend\App\Action
{
    
    protected $resultLayoutFactory;
    protected $_jsHelper;
    protected $_categoryMOdel;
    protected $_fileUploaderFactory;
    protected $_CategoryHelper;
    protected $directory_list;
    
    /**
     * @var GalleryInterfaceFactory
     */

    /**
     * @var Registry
     */
    protected $coreRegistry = null;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param GalleryRepositoryInterface $galleryRepository
     * @param GalleryInterfaceFactory $galleryFactory
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        \Mageants\ImageGallery\Helper\Data $Data,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        Filesystem $fileSystem,
        \Magento\Backend\Helper\Js $jsHelper,
        \Mageants\ImageGallery\Model\Category $categoryModel,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        $this->_CategoryHelper = $Data;
        $this->coreRegistry = $coreRegistry;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem = $fileSystem;
        $this->_jsHelper = $jsHelper;
        $this->_categoryMOdel=$categoryModel;
        $this->directory_list = $directory_list;
        $this->resultLayoutFactory = $resultLayoutFactory;
        parent::__construct($context);
    }

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    protected function initPage(Page $resultPage)
    {
        $resultPage->setActiveMenu('Mageants_All::mageants')
            ->addBreadcrumb(__('Category'), __('Category'))
            ->addBreadcrumb(__('Categories'), __('Categories'));
        return $resultPage;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('DR_Gallery::gallery_gallery');
    }
}
