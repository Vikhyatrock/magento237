<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Model\CatalogInventory;

use Magento\Catalog\Model\ProductFactory;
use Magento\CatalogInventory\Api\Data\StockItemInterface;
use Magento\CatalogInventory\Model\Spi\StockStateProviderInterface;
use Magento\Framework\Locale\FormatInterface;
use Magento\Framework\Math\Division as MathDivision;
use Magento\Framework\DataObject\Factory as ObjectFactory;

class StockStateProvider extends \Magento\CatalogInventory\Model\StockStateProvider
{
    protected $qtyCheckApplicable;

    public function __construct(
        MathDivision $mathDivision,
        FormatInterface $localeFormat,
        ObjectFactory $objectFactory,
        ProductFactory $productFactory,
        $qtyCheckApplicable = true
    ) {
        parent::__construct($mathDivision, $localeFormat, $objectFactory, $productFactory, $qtyCheckApplicable);
    }

    public function checkQuoteItemQty(StockItemInterface $stockItem, $qty, $summaryQty, $origQty = 0)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $preorder = $objectManager->create('\Mageants\PreOrder\Block\Preorder');
        

        $result = $this->objectFactory->create();
        $result->setHasError(false);

        $qty = $this->getNumber($qty);

        /**
         * Check quantity type
         */
        $result->setItemIsQtyDecimal($stockItem->getIsQtyDecimal());
        if (!$stockItem->getIsQtyDecimal()) {
            $result->setHasQtyOptionUpdate(true);
            $qty = intval($qty);
            /**
             * Adding stock data to quote item
             */
            $result->setItemQty($qty);
            $qty = $this->getNumber($qty);
            $origQty = intval($origQty);
            $result->setOrigQty($origQty);
        }

        if ($stockItem->getMinSaleQty() && $qty < $stockItem->getMinSaleQty()) {
            $result->setHasError(true)
                ->setMessage(__('The fewest you may purchase is %1.', $stockItem->getMinSaleQty() * 1))
                ->setErrorCode('qty_min')
                ->setQuoteMessage(__('Please correct the quantity for some products.'))
                ->setQuoteMessageIndex('qty');
            return $result;
        }

        if ($stockItem->getMaxSaleQty() && $qty > $stockItem->getMaxSaleQty()) {
            $result->setHasError(true)
                ->setMessage(__('The most you may purchase is %1.', $stockItem->getMaxSaleQty() * 1))
                ->setErrorCode('qty_max')
                ->setQuoteMessage(__('Please correct the quantity for some products.'))
                ->setQuoteMessageIndex('qty');
            return $result;
        }

        $result->addData($this->checkQtyIncrements($stockItem, $qty)->getData());
        if ($result->getHasError()) {
            return $result;
        }

        if (!$stockItem->getManageStock()) {
            return $result;
        }
        if ($preorder->getACTIVE()) {
            if ($stockItem->getBackorders() == 4) {
                if ($stockItem->getBackstockPreorders() == 1) {
                    $stockItem->setIsInStock(1);
                } elseif ($stockItem->getBackstockPreorders() == 0) {
                    if ($preorder->getAlloweOutofproduct()) {
                        $stockItem->setIsInStock(1);
                    }
                }
            }
        }
        if (!$stockItem->getIsInStock()) {
            $result->setHasError(true)
                ->setMessage(__('This product is out of stock.'))
                ->setQuoteMessage(__('Some of the products are out of stock.'))
                ->setQuoteMessageIndex('stock');
            $result->setItemUseOldQty(true);
            return $result;
        }

        if (!$this->checkQty($stockItem, $summaryQty) || !$this->checkQty($stockItem, $qty)) {
            $message = __('We don\'t have as many "%1" as you requested.', $stockItem->getProductName());
            $result->setHasError(true)->setMessage($message)->setQuoteMessage($message)->setQuoteMessageIndex('qty');
            return $result;
        } else {
            if ($stockItem->getQty() - $summaryQty < 0) {
                if ($stockItem->getProductName()) {
                    if ($stockItem->getIsChildItem()) {
                        $backOrderQty = $stockItem->getQty() > 0 ? ($summaryQty - $stockItem->getQty()) * 1 : $qty * 1;
                        if ($backOrderQty > $qty) {
                            $backOrderQty = $qty;
                        }

                        $result->setItemBackorders($backOrderQty);
                    } else {
                        $orderedItems = (int)$stockItem->getOrderedItems();

                        // Available item qty in stock excluding item qty in other quotes
                        $qtyAvailable = ($stockItem->getQty() - ($summaryQty - $qty)) * 1;
                        if ($qtyAvailable > 0) {
                            $backOrderQty = $qty * 1 - $qtyAvailable;
                        } else {
                            $backOrderQty = $qty * 1;
                        }

                        if ($backOrderQty > 0) {
                            $result->setItemBackorders($backOrderQty);
                        }
                        $stockItem->setOrderedItems($orderedItems + $qty);
                    }

                    if ($stockItem->getBackorders() == \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NOTIFY) {
                        if (!$stockItem->getIsChildItem()) {
                            $result->setMessage(
                                __(
                                    'We don\'t have as many "%1" as you requested, but we\'ll back order the remaining %2.',
                                    $stockItem->getProductName(),
                                    $backOrderQty * 1
                                )
                            );
                        } else {
                            $result->setMessage(
                                __(
                                    'We don\'t have "%1" in the requested quantity, so we\'ll back order the remaining %2.',
                                    $stockItem->getProductName(),
                                    $backOrderQty * 1
                                )
                            );
                        }
                    } elseif ($stockItem->getShowDefaultNotificationMessage()) {
                        $result->setMessage(
                            __('We don\'t have as many "%1" as you requested.', $stockItem->getProductName())
                        );
                    }
                }
            } else {
                if (!$stockItem->getIsChildItem()) {
                    $stockItem->setOrderedItems($qty + (int)$stockItem->getOrderedItems());
                }
            }
        }
        return $result;
    }

    public function checkQty(StockItemInterface $stockItem, $qty)
    {
        if (!$this->qtyCheckApplicable) {
            return true;
        }
        if (!$stockItem->getManageStock()) {
            return true;
        }
        if ($stockItem->getQty() - $stockItem->getMinQty() - $qty < 0) {
            switch ($stockItem->getBackorders()) {
                case \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NONOTIFY:
                case \Magento\CatalogInventory\Model\Stock::BACKORDERS_YES_NOTIFY:
                case \Mageants\PreOrder\Model\Source\Backorders::PRE_ORDER:
                    break;
                default:
                    return false;
            }
        }
        return true;
    }
}
