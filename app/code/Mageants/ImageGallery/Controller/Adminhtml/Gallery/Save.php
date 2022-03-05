<?php
/**
 * @category Mageants ImageGallery
 * @package Mageants_ImageGallery
 * @copyright Copyright (c) 2016 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ImageGallery\Controller\Adminhtml\Gallery;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class Save extends \Magento\Backend\App\Action
{
    protected $_fileUploaderFactory;
    protected $_galleryModel;
    protected $directory_list;
    protected $request;
 
    public function __construct(
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        Filesystem $fileSystem,
        Request $request,
        \Mageants\ImageGallery\Model\Gallery $galleyModel,
        \Magento\Backend\App\Action\Context $context
    ) {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->fileSystem = $fileSystem;
        $this->request = $request;
        $this->_galleryModel = $galleyModel;
        $this->directory_list = $directory_list;
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
            $model = $this->_galleryModel;
            if (isset($data['image']['delete']) == 1) {
                if (!isset($files['image']['tmp_name']) || empty($files['image']['tmp_name'])) {
                    $this->messageManager->addError('Please select another image to delete current one');
                } else {
                    unset($data['image']['value']);
                    $data['image']['value'] = '';
                }
            }

            if (isset($files['image']['name']) && $files['image']['name'] != '') {
                $destinationPath=$this->directory_list->getPath('media')."/imagegallery/gallery/images/";
                try {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image'])
                            ->setAllowCreateFolders(true);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $result=$uploader->save($destinationPath);
                            
                    if (!empty($result['file'])) {
                        $data['image'] ='imagegallery/gallery/images'.$result['file'];
                    } else {
                        $this->messageManager->addError(__('Can not save the image: '.$e->getMessage()));
                        $this->_redirect('*/*/');
                    }
                } catch (\Exception $e) {
                    $this->messageManager->addError(
                        __('Please select only Image'.$e->getMessage())
                    );
                    $id = $this->getRequest()->getParam('image_id');
                    if ($id) {
                        return $this->_redirect('*/*/edit', ['id' => $id]);
                    }
                    return $this->_redirect('*/*/new');
                }
            } else {
                if (isset($data['image'])) {
                    $data['image'] = $data['image']['value'];
                }
            }
            if ($this->getRequest()->getParam('image_id')) {
                $id = $this->getRequest()->getParam('image_id');
            } else {
                $id = $this->_objectManager->get('Magento\Backend\Model\Session')->getImageId();
            }
            if ($id) {
                $model->load($id);
            }

            $model->addData($data);
            
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('The image has been saved.'));
                $this->_objectManager->get('Magento\Backend\Model\Session')->setFormData(false);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setImageId($model->getId());
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', ['id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Model\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\RuntimeException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the image.'));
            }

            $this->_getSession()->setFormData($data);
            $this->_redirect('*/*/edit', ['id' => $this->getRequest()->getParam('image_id')]);
            return;
        }
        $this->_redirect('*/*/');
    }
}
