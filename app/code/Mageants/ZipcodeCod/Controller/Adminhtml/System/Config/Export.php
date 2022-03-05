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
use Magento\Framework\App\Filesystem\DirectoryList;

class Export extends Action
{
    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;
 
    /**
     * @var \Magento\Config\Model\ResourceModel\Config
     */
    public $scopeConfig;
  
    /**
     * @var \Mageants\ZipcodeCod\Model\ZipcodeCod
     */
    public $zipcodeCod;
    
    public $fileFactory;

    public $directory;

    /**
     * @param Context $context
     * @param JsonFactory $resultJsonFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mageants\ZipcodeCod\Model\ZipcodeCod $zipcodeCod,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem
    ) {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->scopeConfig = $scopeConfig;
        $this->_zipcodeCod = $zipcodeCod;
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        parent::__construct($context);
    }
 
    /**
     * Collect relations data
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $name = date('m_d_Y_H_i_s');
        $filepath = 'export/custom' . $name . '.csv';
        $this->directory->create('export');
        /* Open file */
        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();
        try {
            $columns = $this->getColumnHeader();
            foreach ($columns as $column) {
                $header[] = $column;
            }
            /* Write Header */
            $stream->writeCsv($header);

            $zipcodeCollections = $this->_zipcodeCod->getCollection();

            $unSerializePostCodeDetails = [];
            $i = 0;
            foreach ($zipcodeCollections as $zipcodeCollection) {
                $itemData = [];
                $itemData[] = $zipcodeCollection->getZipcode();
                $itemData[] = $zipcodeCollection->getEstimatedDeliveryTime();
                $itemData[] = $zipcodeCollection->getIsCodAvailable();
                $itemData[] = $zipcodeCollection->getCity();
                $stream->writeCsv($itemData);
            }

            $content = [];
            $content['type'] = 'filename'; // must keep filename
            $content['value'] = $filepath;
            $content['rm'] = '1'; //remove csv from var folder

            $csvfilename = 'codbasedonzipcode.csv';
            return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR);
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        $this->_redirect('*/*/*/');
    }
  
    /* Header Columns */
    public function getColumnHeader()
    {
        $headers = ['Postcode','Deliverydays','CodAvailable','City'];
        return $headers;
    }

    public function _isAllowed()
    {
        return true;
    }
}
