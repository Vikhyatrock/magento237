<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Observer;

use Magento\Framework\Event\ObserverInterface;

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

    protected $_storeManager;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Mageants\MultiStoreViewPricing\Model\Pricing $pricing,
        \Magento\Framework\Message\ManagerInterface $message,
        \Mageants\MultiStoreViewPricing\Helper\Data $helper,
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
    // @codingStandardsIgnoreStart
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ((int)$this->_helper->priceScope()==2) {
            $storeId = $this->_storeManager->getStore()->getId();
            $productData = $this->_pricing
                ->getCollection()
                ->addFieldToFilter('store_id', $storeId)
                ->addFieldToFilter('entity_id', $observer->getProduct()->getId());
            if (empty($productData->getData())) {
                $productData = $this->_pricing
                    ->getCollection()
                    ->addFieldToFilter('store_id', 0)
                    ->addFieldToFilter('entity_id', $observer->getProduct()->getId());
            }

            if ($productData->getData()) {
                foreach ($productData as $pData) {
                    $observer->getProduct()->setPrice($pData->getPrice());
                    $observer->getProduct()->setSpecialPrice($pData->getSpecialPrice());
                    $observer->getProduct()->setSpecialFromDate($pData->getSpecialFromDate());
                    $observer->getProduct()->setSpecialToDate($pData->getSpecialToDate());
                    $observer->getProduct()->setCost($pData->getCost());
                    $observer->getProduct()->setMsrpDisplayActualPriceType($pData->getMsrpDisplayActualPriceType());
                    $observer->getProduct()->setMsrp($pData->getMsrp());
                    
                    $tierPrices=null;
                    if ($pData->getTierPrice()) {
                        if ($this->jsonvalidator($pData->getTierPrice())) {
                            $tierPrices = $this->jsonHelper->jsonDecode($pData->getTierPrice());
                        }
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

    public function jsonvalidator($data = null)
    {
        if (!empty($data)) {
            json_decode($data);
            return (json_last_error() === JSON_ERROR_NONE);
        }

        return false;
    }
    // @codingStandardsIgnoreEnd
}
