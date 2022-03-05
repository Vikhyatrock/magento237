<?php
namespace Mageants\PreOrder\Plugin;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Controller\ResultFactory;

class PreventAddToCart
{
    protected $_productloader;
    private $serializer;
    protected $resultFactory;
    protected $redirect;

    public function __construct(
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        ResultFactory $resultFactory,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Mageants\PreOrder\Block\Preorder $preOrder
    ) {
        $this->stockItem = $stockItem;
        $this->_request = $request;
        $this->preOrder = $preOrder;
        $this->serializer = $serializer;
        $this->_messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
        $this->_productloader = $_productloader;
        $this->_redirect = $redirect;
        $this->_checkoutSession = $checkoutSession;
    }

    public function beforeAddProduct(
        \Magento\Checkout\Model\Cart $subject,
        $productInfo,
        $requestInfo = null
    ) {
        
        $item_id = $this->_request->getParam('product');
        $simpal_product_id = $this->_request->getParam('simpleproductid');
        $quote = $this->_checkoutSession->getQuote();
        $allow_mix_order = $this->preOrder->getRestrictOrder();
      
        $preorder_item_in_cart = 0;
        $normal_product_item_in_cart = 0;
        $allow_order = 0;
        $simple_in_group = 0;
        $preorder_in_group = 0;

        if ($productInfo->getTypeId() == 'bundle') {
            $bundle_option = $this->_request->getParam('bundle_option');
            $selectionCollection = $productInfo->getTypeInstance()
            ->getSelectionsCollection(
                $productInfo->getTypeInstance()->getOptionsIds($productInfo),
                $productInfo
            );

            $preorde_product_name = "";
            foreach ($selectionCollection as $selection) {
                foreach ($bundle_option as $key => $value) {
                    if (is_array($value)) {
                        foreach ($value as $val) {
                            if ($val == $selection->getSelectionId()) {
                                $preorderstock = $this->preOrder->getProductStockStatusById($selection->getId());
                                if ($preorderstock->getBackorders() == 4) {
                                    $preorde_product_name.=$selection->getName().",";
                                    $preorder_in_group++;
                                } else {
                                    $simple_in_group++;
                                }
                            }
                        }
                    } else {
                        if ($value == $selection->getSelectionId()) {
                            $preorderstock = $this->preOrder->getProductStockStatusById($selection->getId());
                            if ($preorderstock->getBackorders() == 4) {
                                $preorde_product_name.=$selection->getName().",";
                                $preorder_in_group++;
                            } else {
                                $simple_in_group++;
                            }
                        }
                    }
                }
            }
        
            if ($allow_mix_order == 1) {
              // echo "if 1";
                if ($preorder_in_group > 0 && $simple_in_group > 0) {
                    $message = "We could not add both pre-order and regular items to an order.";
                    throw new \Magento\Framework\Exception\LocalizedException(__($message));
                    return;
                }
            }
            if (count($quote->getAllItems())) {
                if ($allow_mix_order == 1) {
                  // echo "if 2";
                    if ($preorde_product_name != "" && $normal_product_item_in_cart > 0) {
                        $message = "We could not add both pre-order and regular items to an order.";
                         throw new \Magento\Framework\Exception\LocalizedException(__($message));
                         return;
                    } elseif ($preorde_product_name == "" && $preorder_item_in_cart > 0) {
                        $message = "We could not add both pre-order and regular items to an order.";
                        throw new \Magento\Framework\Exception\LocalizedException(__($message));
                        return;
                    }
                }
            }
        } elseif ($this->_request->getParam('super_group')) {
            $group_product_id = $this->_request->getParam('super_group');
            foreach ($group_product_id as $key => $value) {
                $preorderstock = $this->preOrder->getProductStockStatusById($key);
                if ($preorderstock->getBackorders() == 4 && $value != 0) {
                    $preorder_in_group++;
                } else {
                    if ($value != 0) {
                        $simple_in_group++;
                    }
                }
            }
            if ($allow_mix_order == 1) {
                // echo "if 3";
                if ($preorder_in_group > 0 && $simple_in_group > 0) {
                    $message = "We could not add both pre-order and regular items to an order.";
                    throw new \Magento\Framework\Exception\LocalizedException(__($message));
                    return;
                }
                if (count($quote->getAllItems())) {
                    if ($preorder_in_group > 0 && $normal_product_item_in_cart > 0) {
                        $message = "We could not add both pre-order and regular items to an order.";
                        throw new \Magento\Framework\Exception\LocalizedException(__($message));
                        return;
                    } elseif ($simple_in_group > 0 && $preorder_item_in_cart > 0) {
                        $message = "We could not add both pre-order and regular items to an order.";
                        throw new \Magento\Framework\Exception\LocalizedException(__($message));
                        return;
                    } else {
                        $allow_order++;
                    }
                } else {
                    $allow_order++;
                }
            } else {
                $allow_order++;
            }
        } else {
            if ($simpal_product_id != "") {
                $preorderstock = $this->preOrder->getProductStockStatusById($simpal_product_id);
            } else {
                $preorderstock = $this->preOrder->getProductStockStatusById($item_id);
            }

            if ($preorderstock->getPreorderNote() !="") {
                $preorder_note = $preorderstock->getPreorderNote();
            } else {
                $preorder_note = $this->preOrder->getDefaultMessageForPreorder();
            }
          
            $obj = \Magento\Framework\App\ObjectManager::getInstance();
            $stockRegistry = $obj->get('Magento\CatalogInventory\Api\StockRegistryInterface');
            $stockitem = $stockRegistry->getStockItem($item_id, 1);
            $backorders_data = $stockitem->getBackorders();
          
            /*$writer = new \Zend\Log\Writer\Stream(BP.'/var/log/custom.log');
            $logger = new \Zend\Log\Logger();
            $logger->addWriter($writer);
            $logger->info("backorders_data -> ".$backorders_data);*/

            $cookieManager = $obj->get('\Magento\Framework\Stdlib\CookieManagerInterface');
            $cookieMetadataFactory = $obj->get('\Magento\Framework\Stdlib\Cookie\CookieMetadataFactory');

            $publicCookieMetadata = $cookieMetadataFactory->createPublicCookieMetadata();
            $publicCookieMetadata->setDurationOneYear();
            $publicCookieMetadata->setPath('/');
            $publicCookieMetadata->setHttpOnly(false);

            $normal_item = 0;
            $preorder_item = 0;
            if ($backorders_data == 4) {
                $cookieManager->setPublicCookie(
                    'preorder_item',
                    '1',
                    $publicCookieMetadata
                );
                $preorder_item = 1;
            } else {
                $normal_item = 1;
            }
            // var_dump(count($quote->getAllItems()));
            if (count($quote->getAllItems())) {
                if ($allow_mix_order == 1) {
                
                   /* $logger->info("cookie -> ".$cookieManager->getCookie('preorder_item'));
                    $logger->info("preorder_item -> ".$preorder_item);
                    $logger->info("normal_item -> ".$normal_item."\n");*/
                
                    if ($preorder_item == 1) {
                        if ($cookieManager->getCookie('preorder_item')) {
                            $allow_order++;
                            // $message = "We could not add both pre-order and regular items to an order";
                            // throw new \Magento\Framework\Exception\LocalizedException(__($message));
                            // return;
                        }
                    } else {
                        if (count($quote->getAllItems()) > 0) {
                            if ($cookieManager->getCookie('preorder_item')) {
                                $items = $subject->getQuote()->getAllVisibleItems();
                                foreach ($items as $item) {
                                    $customOptions = $item->getOptionByCode('additional_options');

                                    if ($customOptions) {
                                          $row = $customOptions->getData();
                                          // $logger->info("additional Options :".$row['value']);
                                        if (!empty($row['value'])) {
                                            $message = "We could not add both pre-order and regular items to an order.";
                                            throw new \Magento\Framework\Exception\LocalizedException(__($message));
                                            return;
                                        } else {
                                            $allow_order++;
                                        }
                                    }
                                }
                            }
                        } else {
                            $allow_order++;
                            $cookieManager->deleteCookie('preorder_item', $metadata);
                        }
                    }
                } else {
                    $allow_order++;
                }
            }
        }
        return [$productInfo, $requestInfo];
    }
}
