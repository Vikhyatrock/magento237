<?php
/**
 * @category  Mageants BannerSlider
 * @package   Mageants_BannerSlider
 * @copyright Copyright (c) 2017 Mageants
 * @author    Mageants Team <support@Mageants.com>
 */
namespace Mageants\BannerSlider\Controller\Adminhtml\Slides;

use \Mageants\BannerSlider\Helper\Data;
use \Mageants\BannerSlider\Model\ResourceModel\Image;
use \Mageants\BannerSlider\Model\SlidesFactory;
use \Mageants\BannerSlider\Model\Upload;
use \Magento\Backend\App\Action\Context;
use \Magento\Backend\Model\Session;
use \Magento\Backend\Model\View\Result\RedirectFactory;
use \Magento\Framework\Registry;

class Save extends \Mageants\BannerSlider\Controller\Adminhtml\Slides
{
    /**
     * Access Resource ID
     *
     */
    const RESOURCE_ID = 'Mageants_BannerSlider::slide_save';
    /**
     * Upload model
     *
     * @var \Mageants\BannerSlider\Model\Upload
     */
    protected $_uploadModel;

    /**
     * Image model
     *
     * @var \Mageants\BannerSlider\Model\Slide\Image
     */
    protected $_imageModel;

    /**
     * Backend session
     *
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Backend session
     *
     * @var \Mageants\BannerSlider\Helper\Data
     */
    protected $_bannerHelper;

    /**
     * constructor
     *
     * @param Upload $uploadModel
     * @param File $fileModel
     * @param Image $imageModel
     * @param Session $backendSession
     * @param SlidersFactory $slidersFactory
     * @param Registry $registry
     * @param RedirectFactory $resultRedirectFactory
     * @param Context $context
     */
    public function __construct(
        SlidesFactory $slidersFactory,
        Registry $registry,
        Context $context,
        Upload $uploadModel,
        Image $imageModel,
        Data $bannerHelper
    ) {
        $this->_backendSession = $context->getSession();

        $this->_uploadModel = $uploadModel;

        $this->_imageModel = $imageModel;

        $this->_bannerHelper = $bannerHelper;

        $this->slidersFactory = $slidersFactory;

        parent::__construct($slidersFactory, $registry, $context);
    }
    /*
             * Check permission via ACL resource
    */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::RESOURCE_ID);
    }

    /**
     * Retrieve current Store Id
     *
     * @return store_id
     */
    public function getCurrentStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    public function getSlider($sliderid)
    {
        $slider = false;

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $sliderFactory = $objectManager->get('\Mageants\BannerSlider\Model\SlidersFactory')->create();
        $storeId = $objectManager->create("\Magento\Store\Model\StoreManagerInterface")->getStore()->getId();
        if ($sliderid) {
            $slider = $sliderFactory->getCollection()
                ->addFieldToFilter('id', array('eq' => $sliderid))
                ->addFieldToFilter('store_id', array('in' => array($storeId, 0)))
                ->addFieldToFilter('status', array('eq' => 1))
                ->getFirstItem();
        }
        return $slider;
    }

    /**
     * run the action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $data = $this->getRequest()->getPost('slide');
        if (isset($data['id'])) {
            $sliderIdArray = $this->slidersFactory->create()->getCollection()->addFieldToFilter('id', $data['id'])->addFieldToSelect('slider_id')->load()->getData();
            foreach ($sliderIdArray as $sliderIdArrayvalue) {
                foreach ($sliderIdArrayvalue as $sliderKey => $sliderValue) {
                    $data[$sliderKey] = $sliderValue;
                }
            }
        }

        $slidesetting = $this->getRequest()->getPost('slidesetting');
        $data['product_ids'] = str_replace('&', ', ', $data['product_ids']);
        $data['slidesetting'] = $this->_bannerHelper->serializeSetting($slidesetting);

        $resultRedirect = $this->resultRedirectFactory->create();
        $fileData = $this->getRequest()->getFiles('image');

        $slider_id = $data['slider_id'];
        $slider = $this->getSlider($slider_id);
        $unser_setting = $this->_bannerHelper->unserializeSetting($slider->getSetting());
        $setting = $unser_setting['setting'];

        if ($data) {
            $slide = $this->_initSlides();
            $slide->setData($data);
            // Start Image height Validation
            if ($setting['adaptive_height'] == 1) {
                $sliderHeight = $setting['height'];
                if (isset($fileData) && $fileData['name'] != '') {
                    $file_tmp = getimagesize($fileData['tmp_name']);
                    if ($file_tmp[1] != $sliderHeight) {
                        $this->messageManager->addError(__('Your Image height is %1. Please upload image same as the Slider Height which is %2', $file_tmp[1], $sliderHeight));
                        $this->_redirect('mageants_bannerslider/*/edit', ['id' => $slide->getId()]);
                        return;
                    }
                }
            }
            // End Image height Validation
            $image = $this->_uploadModel->uploadFileAndGetName('image', $this->_imageModel->getBaseDir(), $data);

            $slide->setImage($image);

            $this->_eventManager->dispatch(
                'mageants_bannerslider_slides_prepare_save',
                [
                    'slider' => $slide,
                    'request' => $this->getRequest(),
                ]
            );

            try {
                $slide->save();

                $sliderid = $slide->getSliderId();

                $this->messageManager->addSuccess(__('The Slide has been saved.'));

                $this->_backendSession->setMageantsBannerSliderSlideData(false);

                $sliderid = $this->getRequest()->getParam('sliderid');

                if ($sliderid) {
                    if ($this->getRequest()->getParam('back')) {
                        $resultRedirect->setPath(
                            'mageants_bannerslider/sliders/edit/',
                            [
                                'id' => $sliderid,
                                '_current' => true,
                            ]
                        );

                        return $resultRedirect;
                    }

                    $resultRedirect->setPath('mageants_bannerslider/sliders/edit/id/' . $sliderid . '/back/edit/active_tab/associated_slides/*/');
                } else {
                    if ($this->getRequest()->getParam('back')) {
                        $resultRedirect->setPath(
                            'mageants_bannerslider/*/edit',
                            [
                                'id' => $slide->getId(),
                                '_current' => true,
                            ]
                        );

                        return $resultRedirect;
                    }

                    $resultRedirect->setPath('mageants_bannerslider/*/');
                }

                return $resultRedirect;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the Slide.'));
            }

            $this->_getSession()->setMageantsBannerSliderPostData($data);

            $resultRedirect->setPath(
                'mageants_bannerslider/*/edit',
                [
                    'id' => $slide->getId(),
                    '_current' => true,
                ]
            );

            return $resultRedirect;
        }

        $resultRedirect->setPath('mageants_bannerslider/*/');

        return $resultRedirect;
    }
}
