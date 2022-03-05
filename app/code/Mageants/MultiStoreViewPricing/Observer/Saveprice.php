<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Observer;

use Magento\Framework\Event\ObserverInterface;

class Saveprice implements ObserverInterface
{
    /**
     * request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    public $request;

    /**
     * pricing
     *
     * @var \Mageants\MultiStoreViewPricing\Model\Pricing
     */
    protected $_pricing;

    /**
     * message
     *
     * @var Magento\Framework\Message\ManagerInterface
     */
    protected $_message;

    /**
     * helper
     *
     * @var Mageants\MultiStoreViewPricing\Helper\Data
     */
    protected $_helper;

    /**
     * jsonHelper
     *
     * @var \Magento\Framework\Json\Helper\Data
     */
    protected $_jsonHelper;

    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Mageants\MultiStoreViewPricing\Model\Pricing $pricing,
        \Magento\Framework\Message\ManagerInterface $message,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Mageants\MultiStoreViewPricing\Helper\Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->_pricing = $pricing;
        $this->_message=$message;
        $this->_helper =$helper;
        $this->_jsonHelper = $jsonHelper;
        $this->_storeManager = $storeManager;
    }
    /**
     * Execute and perform price for store view
     */
    // @codingStandardsIgnoreStart
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ((int)$this->_helper->priceScope()==2) {
            try {
                $entityID = $observer->getProduct()->getEntityId();
                $posts = $this->request->getPost();
                $storeId = $this->_storeManager->getStore()->getId();
                foreach ($posts as $key => $postData) {
                    if ($key=='product') {
                        if (is_array($postData)) {
                              $cost='';
                            if (array_key_exists('cost', $postData)) {
                                $cost = $postData['cost'];
                            }

                            $tierPrice='';
                            $msrp='';
                            $msrpDisplayActualPriceType='';
                            $price='';
                            $specialPrice='';
                            $specialFromDate='';
                            $specialToDate='';
                        
                            if (array_key_exists('sku', $postData)) {
                                $sku = $postData['sku'];
                            }

                            if (array_key_exists('tier_price', $postData)) {
                                $tierPrice = $this->_jsonHelper->jsonEncode($postData['tier_price']);
                            }

                            if (array_key_exists('msrp', $postData)) {
                                $msrp = $postData['msrp'];
                            }

                            if (array_key_exists('msrp_display_actual_price_type', $postData)) {
                                $msrpDisplayActualPriceType=$postData['msrp_display_actual_price_type'];
                            }

                            if (array_key_exists('price', $postData)) {
                                $price = $postData['price'];
                            }

                            if (array_key_exists('special_price', $postData)) {
                                $specialPrice = $postData['special_price'];
                            }

                            if (array_key_exists('special_from_date', $postData)) {
                                $specialFromDate = $postData['special_from_date'];
                            }

                            if (array_key_exists('special_to_date', $postData)) {
                                $specialToDate = $postData['special_to_date'];
                            }

                            $pricingData = ['entity_id'=>$entityID,
                            'store_id'=>$storeId,
                            'sku'=>$sku,
                            'price' => $price,
                            'special_price'=>$specialPrice,
                            'special_from_date'=>$specialFromDate,
                            'special_to_date'=>$specialToDate,
                            'cost'=>$cost,
                            'msrp'=>$msrp,
                            'msrp_display_actual_price_type'=>$msrpDisplayActualPriceType,
                            'tier_price'=>$tierPrice];
                        }
                    }
                }
                
                $availablePricing =$this->_pricing
                    ->getCollection()
                    ->addFieldToFilter('store_id', $storeId)
                    ->addFieldToFilter('entity_id', $entityID);
                $id = null;

                if ($availablePricing->getData()) {
                    foreach ($availablePricing as $currentPricing) {
                        $id=$currentPricing->getId();
                    }
                }
                
                if ($pricingData) {
                    $this->_pricing->setData($pricingData);

                    if ($id) {
                        $this->_pricing->setId($id);
                    }

                    $this->_pricing->save();
                }
            } catch (\Exception $ex) {
                $this->_message->addError(__("Something is wrong".$ex->getMessage()));
            }
        }
    }
    // @codingStandardsIgnoreEnd
}
