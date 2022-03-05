<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\ZipcodeCod\Controller\Adminhtml\System\Config;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Mageants\ZipcodeCod\Helper\Data;

class Import extends Action
{
    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;
 
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    public $resourceConfig;
 
    /**
     * @var \Mageants\ZipcodeCod\Model\ZipcodeCod
     */
    public $zipcodeCod;
    
    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    public $csvProcessor;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param \Magento\Config\Model\ResourceModel\Config $resourceConfig
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Magento\Config\Model\ResourceModel\Config $resourceConfig,
        \Mageants\ZipcodeCod\Model\ZipcodeCod $zipcodeCod,
        \Magento\Framework\File\Csv $csvProcessor,
        Data $helperData
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_resourceConfig = $resourceConfig;
        $this->_zipcodeCod = $zipcodeCod;
        $this->csvProcessor = $csvProcessor;
        $this->helperData = $helperData;
        parent::__construct($context);
    }
 
    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        try {
            $uploader = $this->_objectManager->create(
                \Magento\MediaStorage\Model\File\Uploader::class,
                ['fileId' => 'import_block']
            );
        } catch (\Exception $e) {
            $responce = ["error" => "true","message" => "Please Upload File"];
            return $this->resultJsonFactory->create()->setData($responce);
        }

        if ($uploader->getFileExtension() != "csv") {
            $responce=["error" => "true","message" => "Invalid file type"];
            return $this->resultJsonFactory->create()->setData($responce);
        }

        try {
            $file = $uploader->validateFile();

            if (!isset($file['tmp_name'])) {
                $responce = ["error" => "true","message" => "can't import zipcodes"];
                return $this->resultJsonFactory->create()->setData($responce);
            }
            $zipcodeArray = $this->csvProcessor->getData($file['tmp_name']);
            $count = 0;
            $deletedatacount = 0;

            foreach ($zipcodeArray as $zipcodeData) {
                if ($deletedatacount > 0) {
                    $codecheck = [];
                    $codecheck[] = $zipcodeData[0];
                    $zipcodeCollections = $this->_zipcodeCod->getCollection();
                    $zipcodeCollections->addFieldToFilter('zipcode', $zipcodeData[0]);
                    foreach ($zipcodeCollections as $price) {
                        $price->delete();
                    }
                }
                $deletedatacount++;
            }
            
            foreach ($zipcodeArray as $zipcodeData) {

                if (!$count) {
                    $count++;
                    continue;
                }

                $zipcode = [];
                $existZipCode = $this->helperData->getzipcodeCsv($zipcodeData[0]);
                if (!$existZipCode) {
                    $zipcode['zipcode'] = $zipcodeData[0];
                    $zipcode['estimated_delivery_time'] = $zipcodeData[1];
                    $zipcode['is_cod_available'] = $zipcodeData[2];
                    $zipcode['city'] = $zipcodeData[3];
                    
                    $this->_zipcodeCod->setData($zipcode);
                    
                    $this->_zipcodeCod->save();
                }
            }

            $responce = ["success" => "true","message" => "Successfilly import zipcodes"];
        } catch (\Exception $e) {
            
            $responce = ["error" => "true","message" => "can't import zipcodes"];
        }

        return $this->resultJsonFactory->create()->setData($responce);
    }
 
    public function _isAllowed()
    {
        return true;
    }
}
