<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2016 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Controller\Adminhtml\Video;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class Save extends \Magento\Backend\App\Action
{
    protected $_fileUploaderFactory;
    protected $_videoModel;
    protected $directory_list;
    protected $_imageHelper;
    protected $request;
 
    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        Filesystem $fileSystem,
        Request $request,
        \Mageants\ImageGallery\Model\Video $videoModel,
        \Mageants\ImageGallery\Helper\Data $imageHelper,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem = $fileSystem;
        $this->_videoModel = $videoModel;
        $this->request = $request;
        $this->directory_list = $directory_list;
        $this->_imageHelper = $imageHelper;
        parent::__construct($context);
    }
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $files = $this->request->getFiles()->toArray(); // same as $_FIELS
        if ($data) {
            $model = $this->_videoModel;
            if (isset($data['video']['delete']) == 1) {
                if (!isset($files['video']['tmp_name']) || empty($files['video']['tmp_name'])) {
                    $this->messageManager->addError('Please select another video to delete current one');
                } else {
                    unset($data['video']['value']);
                    $data['video']['value'] = '';
                }
            }
            if (isset($files['video']['name']) && $files['video']['name'] != '') {
                $destinationPath=$this->directory_list->getPath('media')."/imagegallery/gallery/video/";
                try {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'video'])
                            ->setAllowCreateFolders(true);
                    $uploader->setAllowedExtensions(['mp4', 'avi','webm','flv','wmv','3gp','ogg']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $result=$uploader->save($destinationPath);
                            
                    if (!empty($result['file'])) {
                        $data['video'] ='imagegallery/gallery/video'.$result['file'];
                    } else {
                        $this->messageManager->addError(__('Can not save the Category icon: '.$e->getMessage()));
                        $this->_redirect('*/*/');
                    }
                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __('Please select only Video'.$e->getMessage())
                    );
                    $id = $this->getRequest()->getParam('video_id');
                    if ($id) {
                        return $this->_redirect('*/*/edit', ['id' => $id]);
                    }
                    return $this->_redirect('*/*/new');
                }
            } else {
                if (isset($data['video'])) {
                    $data['video'] = $data['video']['value'];
                }
            }

            if ($this->getRequest()->getParam('video_id')) {
                $id = $this->getRequest()->getParam('video_id');
            } else {
                $id = $this->_objectManager->get('Magento\Backend\Model\Session')->getVideoId();
            }

            if ($id) {
                $model->load($id);
            }

            $model->addData($data);
            
            try {
                $model->save();
                $this->messageManager->addSuccess(__('The video has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setVideoId($model->getVideoId());
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getVideoId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the video.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('video_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }
}
