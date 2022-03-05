<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model\Quote\Address\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Api\Data\AddressInterface;
use Magento\Quote\Model\Quote\Address\FreeShippingInterface;

class Shipping extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * @var FreeShippingInterface
     */
    protected $freeShipping;

    /**
     * @param PriceCurrencyInterface $priceCurrency
     * @param FreeShippingInterface $freeShipping
     */
    public function __construct(
        PriceCurrencyInterface $priceCurrency,
        FreeShippingInterface $freeShipping,
        \Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar $mageantsfreeshippingbar,
        \Mageants\FreeShippingBar\Helper\Data $helper,
        \Magento\Checkout\Model\Cart $cart
    ) {
        $this->_cart = $cart;
        $this->priceCurrency = $priceCurrency;
        $this->freeShipping = $freeShipping;
        $this->_mageantsfreeshippingbar = $mageantsfreeshippingbar;
        $this->_helper = $helper;
        $this->setCode('shipping');
    }

    /**
     * Collect totals information about shipping
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $address = $shippingAssignment->getShipping()->getAddress();
        $method = $shippingAssignment->getShipping()->getMethod();
        $target = "{{goal}}";
        $goal = $this->_mageantsfreeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']
        ])->addFieldtoSelect('goal')->getData();
        $fromDate = $this->_mageantsfreeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('fromdate')->getData();
        $toDate = $this->_mageantsfreeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('todate')->getData();
        // $quote = $this->_cart->getQuote();
        // $subtotal = $quote->getData('subtotal');
        $subtotal = 0;
        $products = $this->_mageantsfreeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('products')->getData();
        if (!empty($products)) {
            if ($products[0]['products'] != "") {
                $selectedProducts = explode(',', trim($products[0]['products'], ","));
                foreach ($quote->getAllVisibleItems() as $item) {
                    if (in_array($item->getProductId(), $selectedProducts)) {
                        $subtotal += $item->getRowTotal();
                    }
                }
            } else {
                $subtotal = $quote->getData('subtotal');
            }
        } else {
            $subtotal = $quote->getData('subtotal');
        }
        $total->setTotalAmount($this->getCode(), 0);
        $total->setBaseTotalAmount($this->getCode(), 0);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }
        $data = $this->getAssignmentWeightData($address, $shippingAssignment->getItems());
        $address->setItemQty($data['addressQty']);
        $address->setWeight($data['addressWeight']);
        $address->setFreeMethodWeight($data['freeMethodWeight']);
        $addressFreeShipping = (bool)$address->getFreeShipping();
        $isFreeShipping = $this->freeShipping->isFreeShipping($quote, $shippingAssignment->getItems());
        if ($this->_helper->isFreeShippingBarEnable()) {
            if (!empty($goal[0]['goal'])) {
                if ($this->_mageantsfreeshippingbar->getCurrentDateTime() >= $fromDate[0]['fromdate'] &&
                 $this->_mageantsfreeshippingbar->getCurrentDateTime() <= $toDate[0]['todate']) {
                    if ($subtotal >= $goal[0]['goal']) {
                        $address->setFreeShipping(true);
                    } else {
                        $address->setFreeShipping($isFreeShipping);
                    }
                } else {
                    $address->setFreeShipping($isFreeShipping);
                }
            } else {
                $address->setFreeShipping($isFreeShipping);
            }
        } else {
            $address->setFreeShipping($isFreeShipping);
        }
        if (!$addressFreeShipping && $isFreeShipping) {
            $data = $this->getAssignmentWeightData($address, $shippingAssignment->getItems());
            $address->setItemQty($data['addressQty']);
            $address->setWeight($data['addressWeight']);
            $address->setFreeMethodWeight($data['freeMethodWeight']);
        }

        $address->collectShippingRates();

        if ($method) {
            foreach ($address->getAllShippingRates() as $rate) {
                if ($rate->getCode() == $method) {
                    $store = $quote->getStore();
                    $amountPrice = $this->priceCurrency->convert(
                        $rate->getPrice(),
                        $store
                    );
                    $total->setTotalAmount($this->getCode(), $amountPrice);
                    $total->setBaseTotalAmount($this->getCode(), $rate->getPrice());
                    $shippingDescription = $rate->getCarrierTitle() . ' - ' . $rate->getMethodTitle();
                    $address->setShippingDescription(trim($shippingDescription, ' -'));
                    $total->setBaseShippingAmount($rate->getPrice());
                    $total->setShippingAmount($amountPrice);
                    $total->setShippingDescription($address->getShippingDescription());
                    break;
                }
            }
        }
        return $this;
    }

    /**
     * Add shipping totals information to address object
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function fetch(\Magento\Quote\Model\Quote $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $amount = $total->getShippingAmount();
        $shippingDescription = $total->getShippingDescription();
        $title = ($amount != 0 && $shippingDescription)
            ? __('Shipping & Handling (%1)', $shippingDescription)
            : __('Shipping & Handling');

        return [
            'code' => $this->getCode(),
            'title' => $title,
            'value' => $amount
        ];
    }

    /**
     * Get Shipping label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __('Shipping');
    }

    /**
     * Gets shipping assignments data like items weight, address weight, items quantity.
     *
     * @param AddressInterface $address
     * @param array $items
     * @return array
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    private function getAssignmentWeightData(AddressInterface $address, array $items): array
    {
        $address->setWeight(0);
        $address->setFreeMethodWeight(0);
        $addressWeight = $address->getWeight();
        $freeMethodWeight = $address->getFreeMethodWeight();
        $addressFreeShipping = (bool)$address->getFreeShipping();
        $addressQty = 0;
        foreach ($items as $item) {
            /**
             * Skip if this item is virtual
             */
            if ($item->getProduct()->isVirtual()) {
                continue;
            }

            /**
             * Children weight we calculate for parent
             */
            if ($item->getParentItem()) {
                continue;
            }

            $itemQty = (float)$item->getQty();
            $itemWeight = (float)$item->getWeight();

            if ($item->getHasChildren() && $item->isShipSeparately()) {
                foreach ($item->getChildren() as $child) {
                    if ($child->getProduct()->isVirtual()) {
                        continue;
                    }
                    $addressQty += $child->getTotalQty();

                    if (!$item->getProduct()->getWeightType()) {
                        $itemWeight = (float)$child->getWeight();
                        $itemQty = (float)$child->getTotalQty();
                        $addressWeight += ($itemWeight * $itemQty);
                        $rowWeight = $this->getItemRowWeight(
                            $addressFreeShipping,
                            $itemWeight,
                            $itemQty,
                            $child->getFreeShipping()
                        );
                        $freeMethodWeight += $rowWeight;
                        $item->setRowWeight($rowWeight);
                    }
                }
                if ($item->getProduct()->getWeightType()) {
                    $addressWeight += ($itemWeight * $itemQty);
                    $rowWeight = $this->getItemRowWeight(
                        $addressFreeShipping,
                        $itemWeight,
                        $itemQty,
                        $item->getFreeShipping()
                    );
                    $freeMethodWeight += $rowWeight;
                    $item->setRowWeight($rowWeight);
                }
            } else {
                if (!$item->getProduct()->isVirtual()) {
                    $addressQty += $itemQty;
                }
                $addressWeight += ($itemWeight * $itemQty);
                $rowWeight = $this->getItemRowWeight(
                    $addressFreeShipping,
                    $itemWeight,
                    $itemQty,
                    $item->getFreeShipping()
                );
                $freeMethodWeight += $rowWeight;
                $item->setRowWeight($rowWeight);
            }
        }

        return [
            'addressQty' => $addressQty,
            'addressWeight' => $addressWeight,
            'freeMethodWeight' => $freeMethodWeight
        ];
    }

    /**
     * Calculates item row weight.
     *
     * @param bool $addressFreeShipping
     * @param float $itemWeight
     * @param float $itemQty
     * @param $freeShipping
     * @return float
     */
    private function getItemRowWeight(
        bool $addressFreeShipping,
        float $itemWeight,
        float $itemQty,
        $freeShipping
    ): float {
        $rowWeight = $itemWeight * $itemQty;
        if ($addressFreeShipping || $freeShipping === true) {
            $rowWeight = 0;
        } elseif (is_numeric($freeShipping)) {
            $freeQty = $freeShipping;
            if ($itemQty > $freeQty) {
                $rowWeight = $itemWeight * ($itemQty - $freeQty);
            } else {
                $rowWeight = 0;
            }
        }
        return (float)$rowWeight;
    }
}
