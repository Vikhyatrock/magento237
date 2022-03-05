<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Block;

/**
 * Check Shipping Method status for front user
 */
class Shipping extends \Magento\Shipping\Model\Shipping
{
    /**
     * current customer session
     *
     * @var \Magento\Customer\Model\Session
     */
    protected $customerSession;

    /**
     * CSPM Model
     *
     * @var \Mageants\CSPM\Model\Cspm
     */
    protected $_cspmModel;
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Shipping\Model\Config $shippingConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Shipping\Model\CarrierFactory $carrierFactory
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Shipping\Model\Shipment\RequestFactory $shipmentRequestFactory
     * @param \Magento\Directory\Model\RegionFactory $regionFactory
     * @param \Magento\Framework\Math\Division $mathDivision
     * @param \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Mageants\CSPM\Model\Cspm $cspmModel
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Shipping\Model\CarrierFactory $carrierFactory,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Shipping\Model\Shipment\RequestFactory $shipmentRequestFactory,
        \Magento\Directory\Model\RegionFactory $regionFactory,
        \Magento\Framework\Math\Division $mathDivision,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry,
        \Magento\Customer\Model\Session $customerSession,
        \Mageants\CSPM\Model\Cspm $cspmModel
    ) {
    	$this->customerSession = $customerSession;
        $this->_cspmModel=$cspmModel;
        $this->_storeManager=$storeManager;
        parent::__construct($scopeConfig,$shippingConfig,$storeManager,$carrierFactory,$rateResultFactory,$shipmentRequestFactory,$regionFactory,$mathDivision,$stockRegistry);
    }

    /**
     * Check Carrier Method For customer Group
     */
    public function collectCarrierRates($carrierCode, $request) {
        if (!$this->_checkCarrierAvailability($carrierCode, $request)) {
            return $this;
        }
        return parent::collectCarrierRates($carrierCode, $request);
    }

    /**
     * Check Method is available or not
     */
    protected function _checkCarrierAvailability($carrierCode, $request = null) {
        $groupId = $this->customerSession->getCustomerGroupId();
        if ($this->customerSession->isLoggedIn()) {
            $groupId = $this->customerSession->getCustomer()->getGroupId();
        }
        $webId=$this->_storeManager->getStore()->getId();
        $storeView=array();
        $storeView[0]="0";
        $storeView[1]=$webId;
        $collection=$this->_cspmModel->getCollection()
                    ->addFieldToFilter("cgid",$groupId)
                    ->addFieldToFilter("cstatus","Enable")
                    ->addFieldToFilter("website",array('in' => $storeView));
        if(sizeof($collection) > 0)
        {
            foreach ($collection->getData() as $item) {
                if($item['smethod']!=="0")
                {
                    $shippingArray = explode(',', $item['smethod']);
               
                    foreach ($shippingArray as $method) {
                        if($method == $carrierCode)
                        {
                            return true;
                        }
                    }
                    return false;
                }
                
                    # code...
            }    
        }
        return true;
    }

}