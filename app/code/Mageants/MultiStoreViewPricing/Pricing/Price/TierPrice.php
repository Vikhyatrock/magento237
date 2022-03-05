<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\MultiStoreViewPricing\Pricing\Price;

use Magento\Catalog\Model\Product;
use Magento\Customer\Api\GroupManagementInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\Pricing\Adjustment\CalculatorInterface;
use Magento\Framework\Pricing\Amount\AmountInterface;
use Magento\Framework\Pricing\Price\AbstractPrice;
use Magento\Framework\Pricing\Price\BasePriceProviderInterface;
use Magento\Framework\Pricing\PriceInfoInterface;

/**
 * Tire prices model
 */
class TierPrice extends \Magento\Catalog\Pricing\Price\TierPrice
{
    /**
     * Price type tier
     */
    const PRICE_CODE = 'tier_price';

    /**
     * @var Session
     */
    public $customerSession;

    /**
     * @var int
     */
    public $customerGroup;

    /**
     * Raw price list stored in DB
     *
     * @var array
     */
    public $rawPriceList;

    /**
     * Applicable price list
     *
     * @var array
     */
    public $priceList;

    /**
     * @var GroupManagementInterface
     */
    public $groupManagement;

    /**
     * @param Product $saleableItem
     * @param float $quantity
     * @param CalculatorInterface $calculator
     * @param \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency
     * @param Session $customerSession
     * @param GroupManagementInterface $groupManagement
     */
    public function __construct(
        Product $saleableItem,
        $quantity,
        CalculatorInterface $calculator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        Session $customerSession,
        GroupManagementInterface $groupManagement,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mageants\MultiStoreViewPricing\Model\Pricing $pricing,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        $quantity = $quantity ?: 1;
        parent::__construct($saleableItem, $quantity, $calculator, $priceCurrency, $customerSession, $groupManagement);
        $this->customerSession = $customerSession;
        $this->groupManagement = $groupManagement;
        if ($saleableItem->hasCustomerGroupId()) {
            $this->customerGroup = (int) $saleableItem->getCustomerGroupId();
        } else {
            $this->customerGroup = (int) $this->customerSession->getCustomerGroupId();
        }

        $this->storeManager = $storeManager;
        $this->MultiStoreViewPricing = $pricing;
        $this->jsonHelper = $jsonHelper;
    }
    /**
     * @return array
     */
    public function getTierPriceList()
    {
        $tierPriceCollection = $this->MultiStoreViewPricing
            ->getCollection()
            ->addFieldToFilter('entity_id', $this->getProduct()->getId())
            ->addFieldToFilter('store_id', $this->storeManager->getStore()->getId());
        $_tierprices = [];
        $storePrice=0;
        foreach ($tierPriceCollection as $tierprices) {
            $storeViewPrice=[];
            if ($tierprices->getId()) {
                $storePrice=1;
            }

            if ($tierprices->getTierPrice()) {
                if ($this->jsonvalidator($tierprices->getTierPrice())) {
                    $storeViewPrice = $this->jsonHelper->jsonDecode($tierprices->getTierPrice());
                }
            }

            if (!empty($storeViewPrice)) {
                foreach ($storeViewPrice as $prices) {
                    if (array_key_exists('delete', $prices)) {
                        continue;
                    }
                    $_tierprices[] =$prices;
                }
            }
        }

        if (null === $this->priceList) {
            $priceList = $this->getStoredTierPrices();
            if ($storePrice) {
                $priceList=$_tierprices;
            }

            $this->priceList = $this->filterTierPrices($priceList);

            array_walk(
                $this->priceList,
                function (&$priceData) {
                    /* convert string value to float */
                    $priceData['price_qty'] = $priceData['price_qty'] * 1;
                    $priceData['price'] = $this->applyAdjustment($priceData['price']);
                }
            );
        }
        return $this->priceList;
    }

    // @codingStandardsIgnoreStart
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
