<?php
namespace Mageants\Customoptionimage\Helper;

class ImageSaving
{
    public $uploader;

    public $filesystem;

    public $storeManager;

    public $fileDriver;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploader,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $fileDriver
    ) {
        $this->storeManager = $storeManager;
        $this->uploader = $uploader;
        $this->filesystem = $filesystem;
        $this->fileDriver = $fileDriver;
    }

    public function moveImage($value)
    {
        $baseUrl = $this->storeManager
                    ->getStore()
                    ->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    );
        $mediaRootDir = $this->filesystem->getDirectoryRead(
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        )->getAbsolutePath();
        if ($value->getData('mageants_image_button')) {
            $file = substr($value->getData('mageants_image_button'), strlen($baseUrl));
        } elseif ($value->getData('image_url')) {
            $file = substr($value->getData('image_url'), strlen($baseUrl));
        } else {
            return '';
        }
        $fileNamePieces = explode('/', $file);
        $fileName = end($fileNamePieces);
        $mediaDirectory = $this->filesystem->getDirectoryRead(
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        );
        $newPath = 'mageants/custimg/' . $value->getOptionId() . '/';
        
        $this->fileDriver->createDirectory($mediaDirectory->getAbsolutePath($newPath));
        $checkDuplicateName = $fileName;
        if ($file !== $newPath . $fileName) {
            $checkTime = 0;
            while ($this->fileDriver->isFile($mediaRootDir . $newPath . $checkDuplicateName)) {
                $checkDuplicateName = '(' . $checkTime . ')' . $fileName;
                $checkTime++;
            }
            $this->fileDriver->rename($mediaRootDir . $file, $mediaRootDir . $newPath . $checkDuplicateName);
        }
        return $baseUrl . $newPath . $checkDuplicateName;
    }
    public function cleanTempFile()
    {
        $mediaRootDir = $this->filesystem->getDirectoryRead(
            \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
        )->getAbsolutePath();
        if ($this->fileDriver->isDirectory($mediaRootDir . 'mageants/temp/')) {
            $this->fileDriver->deleteDirectory($mediaRootDir . 'mageants/temp/');
        }
    }
    public function saveTemporaryImage($opOrder, $valueOrder)
    {
        try {
            $fieldName = 'temporary_image';
            $baseMediaPath = 'mageants/temp/' . $opOrder . '_' . $valueOrder . '/';
            $uploader = $this->uploader->create(['fileId' => $fieldName ]);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png', 'bmp']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $mediaDirectory = $this->filesystem->getDirectoryRead(
                \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
            );
            $result = $uploader->save($mediaDirectory->getAbsolutePath($baseMediaPath));

            $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
            $result['path'] = str_replace('\\', '/', $result['path']);
            $result['url'] = $this->storeManager
                    ->getStore()
                    ->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ) . $this->getFilePath($baseMediaPath, $result['file']);
            $result['name'] = $result['file'];
            $data['mageants_image'] = $baseMediaPath.$result['file'];
            return  $result['url'];
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}
