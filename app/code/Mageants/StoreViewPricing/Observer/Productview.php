<?php
/**
 * @category Mageants StoreViewPricing
 * @package Mageants_StoreViewPricing
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\StoreViewPricing\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Request\Http;

/**
 * Save Price class
 */
class Productview implements ObserverInterface
{
    /**
     * request
     *
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     * pricing
     *
     * @var \Mageants\StoreViewPricing\Model\Pricing
     */
    private $pricing;

    /**
     * message
     *
     * @var Magento\Framework\Message\ManagerInterface
     */
    private $message;

    /**
     * helper
     *
     * @var Mageants\StoreViewPricing\Helper\Data
     */
    private $helper;

    private $storeManager;

    public function __construct(
        Http $request,
        \Mageants\StoreViewPricing\Model\Pricing $pricing,
        \Magento\Framework\Message\ManagerInterface $message,
        \Mageants\StoreViewPricing\Helper\Data $helper,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->request = $request;
        $this->_pricing = $pricing;
        $this->_message=$message;
        $this->_helper =$helper;
        $this->jsonHelper = $jsonHelper;
        $this->_storeManager = $storeManager;
    }
    /**
     * Execute and perform price for store view
     */
    // @codingStandardsIgnoreLine
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ((int)$this->_helper->priceScope()==2) {
            $storeId = $this->_storeManager->getStore()->getId();
            $productData = $this->_pricing->getCollection()
            ->addFieldToFilter(
                'store_id',
                $storeId
            )->addFieldToFilter(
                'entity_id',
                $observer->getProduct()->getId()
            );
            if (empty($productData->getData())) {
                $productData = $this->_pricing->getCollection()
                ->addFieldToFilter(
                    'store_id',
                    0
                )->addFieldToFilter(
                    'entity_id',
                    $observer->getProduct()->getId()
                );
            }

            if ($productData->getData()) {
                foreach ($productData as $pData) {
                    $fromDate = date_create($pData->getSpecialFromDate());
                    $fromDateFormat = date_format($fromDate,"Y-m-d H:i:s");
                    $toDate = date_create($pData->getSpecialToDate());
                    $toDateFormat = date_format($toDate,"Y-m-d H:i:s");
                    $price = $pData->getPrice();
                    $special_price = $pData->getSpecialPrice();
                    $cost = $pData->getCost();
                    $msrp_display_actual_price_type = $pData->getMsrpDisplayActualPriceType();
                    $msrp = $pData->getMsrp();

                    $observer->getProduct()->setPrice($pData->getPrice());
                    $observer->getProduct()->setSpecialPrice($pData->getSpecialPrice());
                    $observer->getProduct()->setSpecialFromDate(date_format($fromDate,"Y-m-d H:i:s")); 
                    $observer->getProduct()->setSpecialToDate(date_format($toDate,"Y-m-d H:i:s"));

                    $observer->getProduct()->setCost($pData->getCost());
                    $observer->getProduct()->setMsrpDisplayActualPriceType($pData->getMsrpDisplayActualPriceType());
                    $observer->getProduct()->setMsrp($pData->getMsrp());

                    $tierPrices=null;
                    if ($pData->getTierPrice()) {
                        if ($this->jsonValidator($pData->getTierPrice())) {
                            $tierPrices = $this->jsonHelper->jsonDecode($pData->getTierPrice());
                        }
                    }

                 // New
                     if (!empty($pData->getSpecialFromDate())) {
                        # code...
                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        $array_product = [$observer->getProduct()->getId()]; //product Ids
                        $productActionObject = $objectManager->create('Magento\Catalog\Model\Product\Action');
                        $productActionObject->updateAttributes($array_product, 
                            array(
                                'special_to_date'                => $toDateFormat,
                                'special_from_date'              => $fromDateFormat,
                                // 'price'                          => $price,
                                // 'special_price'                  => $special_price,
                                // 'cost'                           => $cost,
                                // 'msrp'                           => $msrp,
                                // 'msrp_display_actual_price_type' => $msrp_display_actual_price_type
                        ), $storeId);
                    }
                    
                    
                    $storeviewprice=[];
                    if ($tierPrices) {
                        foreach ($tierPrices as $tierprice) {
                            if (array_key_exists('delete', $tierprice)) {
                                continue;
                            }
                            $storeviewprice[]=$tierprice;
                        }
                    }
                    $observer->getProduct()->setTierPrice($storeviewprice);
                }
            }
        }
    }

    private function jsonValidator($data = null)
    {
        // @codingStandardsIgnoreStart
        if (!empty($data)) {
            @json_decode($data);

            return (json_last_error() === JSON_ERROR_NONE);
        }
        // @codingStandardsIgnoreEnd
        return false;
    }
}
