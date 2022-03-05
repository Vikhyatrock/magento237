<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Observer;
 
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Controller\ResultFactory;

class SetPreOrderOptions implements ObserverInterface
{
    
    /**
     * @param RequestInterface $request
     */

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
        \Mageants\PreOrder\Block\Preorder $preOrder
    ) {
        $this->stockItem = $stockItem;
        $this->_request = $request;
        $this->preOrder = $preOrder;
        $this->serializer = $serializer;
        $this->_messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
        $this->_redirect = $redirect;
        $this->_checkoutSession = $checkoutSession;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->preOrder->getACTIVE() == 1) {

            $product = $observer->getProduct();
            $item_id = $this->_request->getParam('product');
            $simpal_product_id = $this->_request->getParam('simpleproductid');
            $quote = $this->_checkoutSession->getQuote();
            $allow_mix_order = $this->preOrder->getRestrictOrder();
        
            $allow_order = 0;
            $simple_in_group =0;
            $preorder_in_group = 0;

            if ($product->getTypeId() == 'bundle') {
                $bundle_option = $this->_request->getParam('bundle_option');
                $selectionCollection = $product->getTypeInstance()
                ->getSelectionsCollection(
                    $product->getTypeInstance()->getOptionsIds($product),
                    $product
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
                if ($preorde_product_name != "") {
                    $preordernote[] = [
                    'label' => "Pre-Order",
                    'value' => "Pre Order Item : ".$preorde_product_name,
                    ];
                    $observer->getProduct()->addCustomOption('additional_options', $this->serializer->serialize($preordernote));
                }
            } elseif ($this->_request->getParam('super_group')) {

                $product = $observer->getProduct();
                $preorderstock = $this->preOrder->getProductStockStatusById($product->getId());
          
                if ($preorderstock->getPreorderNote() !="") {
                    $preorder_note = $preorderstock->getPreorderNote();
                } else {
                    $preorder_note = $this->preOrder->getDefaultMessageForPreorder();
                }
                if ($preorderstock->getBackorders() == 4) {
                    $preordernote[] = [
                    'label' => "Pre-Order",
                    'value' => $preorder_note,
                    ];
                    $observer->getProduct()->addCustomOption('additional_options', $this->serializer->serialize($preordernote));
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
          
                if ($preorderstock->getBackorders() == 4) {
                    $preordernote[] = [
                    'label' => "Pre-Order",
                    'value' => $preorder_note,
                    ];
                    $product = $observer->getProduct();
                    $observer->getProduct()->addCustomOption('additional_options', $this->serializer->serialize($preordernote));
                }
            
            }
        }
    }
}
