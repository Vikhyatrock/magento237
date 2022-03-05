<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model\Config\Source;

use \Magento\Store\Model\StoreRepository;
/**
 * return all Website
 */
class Website implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @var Rate
     */
    protected $_storeRepository;
      
    /**
     * @param StoreRepository      $storeRepository
     */
    public function __construct(
        StoreRepository $storeRepository
    ) {
        $this->_storeRepository = $storeRepository;
    }

    /**
     * return payment method array
     */
    public function toOptionArray()
    {
        $stores = $this->_storeRepository->getList();
        $websiteIds = array();
        foreach ($stores as $store) {
            $webName=$store->getWebsite()->getName();
            $webId=$store->getWebsite()->getId();
            if($webId > 0){
                $websiteIds[$webId] = array(
                'label' => $webName,
                'value' => $webId
                );    
            }
        }
        return $websiteIds;
    }
}

