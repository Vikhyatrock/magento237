<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Model\Quote\Address\Total;

use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Quote\Model\Quote\Address\FreeShippingInterface;

class Shipping extends \Magento\Quote\Model\Quote\Address\Total\Shipping
{
    /**
     * Collect totals information about shipping
     *
     * @param \Magento\Quote\Model\Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param \Magento\Quote\Model\Quote\Address\Total $total
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
            $_objectManager=\Magento\Framework\App\ObjectManager::getInstance();
            $_cookieManager=$_objectManager->create("Magento\Framework\Stdlib\CookieManagerInterface");
            $shippingFee=0;
            $shippingLabel='';
            $shippingFee=$_cookieManager->getCookie("shippingExtrafeeIds");
            $shippingLabel=$_cookieManager->getCookie("shippingExtraFeeLabel");
            $address = $shippingAssignment->getShipping()->getAddress();
            $method = $shippingAssignment->getShipping()->getMethod();
            $address->setWeight(0);
            $address->setFreeMethodWeight(0);

            $addressWeight = $address->getWeight();
            $freeMethodWeight = $address->getFreeMethodWeight();

            $isAllFree = $this->freeShipping->isFreeShipping($quote, $shippingAssignment->getItems());
        if ($isAllFree && !$address->getFreeShipping()) {
            $address->setFreeShipping(true);
        }
            $total->setTotalAmount($this->getCode(), 0);
            $total->setBaseTotalAmount($this->getCode(), 0);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

            $addressQty = 0;
        foreach ($shippingAssignment->getItems() as $item) {
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

            if ($item->getHasChildren() && $item->isShipSeparately()) {
                foreach ($item->getChildren() as $child) {
                    if ($child->getProduct()->isVirtual()) {
                        continue;
                    }
                    $addressQty += $child->getTotalQty();

                    if (!$item->getProduct()->getWeightType()) {
                        $itemWeight = $child->getWeight();
                        $itemQty = $child->getTotalQty();
                        $rowWeight = $itemWeight * $itemQty;
                        $addressWeight += $rowWeight;
                        if ($address->getFreeShipping() || $child->getFreeShipping() === true) {
                            $rowWeight = 0;
                        } elseif (is_numeric($child->getFreeShipping())) {
                            $freeQty = $child->getFreeShipping();
                            if ($itemQty > $freeQty) {
                                $rowWeight = $itemWeight * ($itemQty - $freeQty);
                            } else {
                                $rowWeight = 0;
                            }
                        }
                        $freeMethodWeight += $rowWeight;
                        $item->setRowWeight($rowWeight);
                    }
                }
                if ($item->getProduct()->getWeightType()) {
                    $itemWeight = $item->getWeight();
                    $rowWeight = $itemWeight * $item->getQty();
                    $addressWeight += $rowWeight;
                    if ($address->getFreeShipping() || $item->getFreeShipping() === true) {
                        $rowWeight = 0;
                    } elseif (is_numeric($item->getFreeShipping())) {
                        $freeQty = $item->getFreeShipping();
                        if ($item->getQty() > $freeQty) {
                            $rowWeight = $itemWeight * ($item->getQty() - $freeQty);
                        } else {
                            $rowWeight = 0;
                        }
                    }
                    $freeMethodWeight += $rowWeight;
                    $item->setRowWeight($rowWeight);
                }
            } else {
                if (!$item->getProduct()->isVirtual()) {
                    $addressQty += $item->getQty();
                }
                $itemWeight = $item->getWeight();
                $rowWeight = $itemWeight * $item->getQty();
                $addressWeight += $rowWeight;
                if ($address->getFreeShipping() || $item->getFreeShipping() === true) {
                    $rowWeight = 0;
                } elseif (is_numeric($item->getFreeShipping())) {
                    $freeQty = $item->getFreeShipping();
                    if ($item->getQty() > $freeQty) {
                        $rowWeight = $itemWeight * ($item->getQty() - $freeQty);
                    } else {
                        $rowWeight = 0;
                    }
                }
                $freeMethodWeight += $rowWeight;
                $item->setRowWeight($rowWeight);
            }
        }

        if (isset($addressQty)) {
            $address->setItemQty($addressQty);
        }

            $address->setWeight($addressWeight);
            $address->setFreeMethodWeight($freeMethodWeight);
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
                    if ($shippingLabel!='') {
                        $shippingDescription = $rate->getCarrierTitle() . ' - ' . $rate->getMethodTitle()." + ".$shippingLabel;
                    } else {
                        $shippingDescription = $rate->getCarrierTitle() . ' - ' . $rate->getMethodTitle();
                    }
                    $mandatoryShippingExtraFee = $_cookieManager->getCookie("mandatoryShippingExtraFee");
                    if ($mandatoryShippingExtraFee!=null && $mandatoryShippingExtraFee!='') {
                        $shippingDescription = $shippingDescription.' + '.$mandatoryShippingExtraFee;
                    }
                    $mandatoryShippingAmount=$_cookieManager->getCookie("mandatoryShippingAmount");
                    if ($mandatoryShippingAmount != 0) {
                        $shippingFee = $shippingFee+$mandatoryShippingAmount;
                    }
                    $address->setShippingDescription(trim($shippingDescription, ' -'));

                    $total->setBaseShippingAmount($rate->getPrice() + $shippingFee);
                    $total->setShippingAmount($amountPrice + $shippingFee);
                    $total->setShippingDescription($address->getShippingDescription());
                    $total->setGrandTotal($total->getGrandTotal());
                    $total->setBaseGrandTotal($total->getBaseGrandTotal());
                        
                    break;
                }
            }
        }
            return $this;
    }
}
