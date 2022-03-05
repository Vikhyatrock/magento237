<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Model\Carrier;

use Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Shipping\Model\Carrier\AbstractCarrier;
use Magento\Shipping\Model\Carrier\CarrierInterface;
use Magento\Shipping\Model\Rate\Result;

/**
 * Flat rate shipping model
 *
 * @api
 * @since 100.0.2
 */
class Flatrate extends AbstractCarrier implements CarrierInterface
{
    /**
     * @var string
     */
    protected $_code = 'flatrate';

    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;

    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;

    /**
     * @var ItemPriceCalculator
     */
    private $itemPriceCalculator;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param ItemPriceCalculator $itemPriceCalculator
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        \Magento\OfflineShipping\Model\Carrier\Flatrate\ItemPriceCalculator $itemPriceCalculator,
        \Magento\Checkout\Model\Cart $cart,
        \Mageants\FreeShippingBar\Block\Frontend\FreeShippingBar $freeshippingbar,
        \Mageants\FreeShippingBar\Helper\Data $helper,
        array $data = []
    ) {
        $this->_cart = $cart;
        $this->_freeshippingbar = $freeshippingbar;
        $this->_helper = $helper;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->itemPriceCalculator = $itemPriceCalculator;
        parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return Result|bool
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        $freeBoxes = $this->getFreeBoxesCount($request);
        $this->setFreeBoxes($freeBoxes);

        /** @var Result $result */
        $result = $this->_rateResultFactory->create();

        $shippingPrice = $this->getShippingPrice($request, $freeBoxes);

        if ($shippingPrice !== false) {
            $method = $this->createResultMethod($shippingPrice);
            $result->append($method);
        }

        return $result;
    }

    /**
     * @param RateRequest $request
     * @return int
     */
    private function getFreeBoxesCount(RateRequest $request)
    {
        $freeBoxes = 0;
        if ($request->getAllItems()) {
            foreach ($request->getAllItems() as $item) {
                if ($item->getProduct()->isVirtual() || $item->getParentItem()) {
                    continue;
                }

                if ($item->getHasChildren() && $item->isShipSeparately()) {
                    $freeBoxes += $this->getFreeBoxesCountFromChildren($item);
                } elseif ($item->getFreeShipping()) {
                    $freeBoxes += $item->getQty();
                }
            }
        }
        return $freeBoxes;
    }

    /**
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['flatrate' => $this->getConfigData('name')];
    }

    /**
     * @param RateRequest $request
     * @param int $freeBoxes
     * @return bool|float
     */
    private function getShippingPrice(RateRequest $request, $freeBoxes)
    {
        $shippingPrice = false;

        $configPrice = $this->getConfigData('price');
        $quote = $this->_cart->getQuote();
        // $total =  $quote->getData('subtotal');
        $total = 0;
        
        $target = "{{goal}}";
        $goal = $this->_freeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('goal')->getData();
        $products = $this->_freeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('products')->getData();
        if (!empty($products)) {
            if ($products[0]['products'] != "") {
                $selectedProducts = explode(',', trim($products[0]['products'], ","));
                foreach ($quote->getAllVisibleItems() as $item) {
                    if (in_array($item->getProductId(), $selectedProducts)) {
                        $total += $item->getRowTotal();
                    }
                }
            } else {
                $total = $quote->getData('subtotal');
            }
        } else {
            $total = $quote->getData('subtotal');
        }
        
        $fromDate = $this->_freeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('fromdate')->getData();
        $toDate = $this->_freeshippingbar->getCollections()->addFieldToFilter('first_message', [
                ['like' => '% '.$target.' %'],
                ['like' => '% '.$target],
                ['like' => $target.' %']])->addFieldtoSelect('todate')->getData();
        if ($this->_helper->isFreeShippingBarEnable()) {
            if (!empty($goal[0]['goal'])) {
                if ($this->_freeshippingbar->getCurrentDateTime() >= $fromDate[0]['fromdate'] && $this->_freeshippingbar->getCurrentDateTime() <= $toDate[0]['todate']) {
                    if ($total >= $goal[0]['goal']) {
                        $shippingPrice = '0.00';
                    } else {
                        if ($this->getConfigData('type') === 'O') {
                            // per order
                            $shippingPrice = $this->itemPriceCalculator->getShippingPricePerOrder(
                                $request,
                                $configPrice,
                                $freeBoxes
                            );
                        } elseif ($this->getConfigData('type') === 'I') {
                            // per item
                            $shippingPrice = $this->itemPriceCalculator->getShippingPricePerItem(
                                $request,
                                $configPrice,
                                $freeBoxes
                            );
                        }
                    }
                } else {
                    if ($this->getConfigData('type') === 'O') {
                        // per order
                        $shippingPrice = $this->itemPriceCalculator->getShippingPricePerOrder(
                            $request,
                            $configPrice,
                            $freeBoxes
                        );
                    } elseif ($this->getConfigData('type') === 'I') {
                        // per item
                        $shippingPrice = $this->itemPriceCalculator->getShippingPricePerItem(
                            $request,
                            $configPrice,
                            $freeBoxes
                        );
                    }
                }
            } else {
                if ($this->getConfigData('type') === 'O') {
                    // per order
                    $shippingPrice = $this->itemPriceCalculator->getShippingPricePerOrder(
                        $request,
                        $configPrice,
                        $freeBoxes
                    );
                } elseif ($this->getConfigData('type') === 'I') {
                    // per item
                    $shippingPrice = $this->itemPriceCalculator->getShippingPricePerItem(
                        $request,
                        $configPrice,
                        $freeBoxes
                    );
                }
            }
        } else {
            if ($this->getConfigData('type') === 'O') {
                // per order
                $shippingPrice = $this->itemPriceCalculator->getShippingPricePerOrder(
                    $request,
                    $configPrice,
                    $freeBoxes
                );
            } elseif ($this->getConfigData('type') === 'I') {
                // per item
                $shippingPrice = $this->itemPriceCalculator->getShippingPricePerItem(
                    $request,
                    $configPrice,
                    $freeBoxes
                );
            }
        }
        $shippingPrice = $this->getFinalPriceWithHandlingFee($shippingPrice);

        if ($shippingPrice !== false && $request->getPackageQty() == $freeBoxes) {
            $shippingPrice = '0.00';
        }
        return $shippingPrice;
    }

    /**
     * @param int|float $shippingPrice
     * @return \Magento\Quote\Model\Quote\Address\RateResult\Method
     */
    private function createResultMethod($shippingPrice)
    {
        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();

        $method->setCarrier('flatrate');
        $method->setCarrierTitle($this->getConfigData('title'));

        $method->setMethod('flatrate');
        $method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($shippingPrice);
        $method->setCost($shippingPrice);
        return $method;
    }

    /**
     * @param mixed $item
     * @return mixed
     */
    private function getFreeBoxesCountFromChildren($item)
    {
        $freeBoxes = 0;
        foreach ($item->getChildren() as $child) {
            if ($child->getFreeShipping() && !$child->getProduct()->isVirtual()) {
                $freeBoxes += $item->getQty() * $child->getQty();
            }
        }
        return $freeBoxes;
    }
}
