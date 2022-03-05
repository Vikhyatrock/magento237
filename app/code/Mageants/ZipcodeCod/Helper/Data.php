<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Helper;

use Mageants\ZipcodeCod\Model\ResourceModel\ZipcodeCod\Collection;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public $storeManager;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public $scopeConfig;

    /**
     * @param \Magento\Framework\App\Helper\Context   $context
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        Collection $zipcodecollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->zipcodecollection = $zipcodecollection;
        $this->scopeConfig = $context->getScopeConfig();
    }
    
    /**
     * Get Store Config Value
     * @return string
     */
    public function getZipcodeCodConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getzipcode($zipcode = '')
    {
        $result = 0;
        try {
            if ($zipcode) {
                $data = $this->zipcodecollection->addFieldToSelect('*')
                                                ->addFieldToFilter('zipcode', $zipcode);
                if ($data->count() > 0) {
                    $result = 1;
                }
            }
        } catch (\Exception $e) {
            // log error while throw from try
        }
        return $result;
    }

    public function getzipcodeCsv($zipcode = '')
    {
        $result = 0;
        try {
            if ($zipcode) {
                $data = $this->zipcodecollection->addFieldToSelect('*')
                                                ->addFieldToFilter('zipcode', $zipcode);
                if ($data->count() > 0) {
                    $result = 1;
                }
                $this->zipcodecollection->clear()->getSelect()->reset('where');
            }
        } catch (\Exception $e) {
            // log error while throw from try
        }

        return $result;
    }

    public function getzipcodeID($zipcodeid = '')
    {
        $result = 0;
        try {
            if ($zipcodeid) {
                $data = $this->zipcodecollection->addFieldToSelect('*')
                                                ->addFieldToFilter('id', $zipcodeid)
                                                ->getData();
                // if ($data->count() > 0) {
                //     $result = 1;
                // }
                return $data[0]['zipcode'];
            }
        } catch (\Exception $e) {
            // log error while throw from try
        }

        return $result;
    }
    public function isZipcodeEnable()
    {
        return $this->getZipcodeCodConfig('mageants_zipcodecod/general/enable');
    }
}
