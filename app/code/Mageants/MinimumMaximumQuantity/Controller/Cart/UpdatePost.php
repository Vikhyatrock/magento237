<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Mageants\MinimumMaximumQuantity\Controller\Cart;

use Magento\Checkout\Model\Cart\RequestQuantityProcessor;
use Magento\Framework\Serialize\Serializer\Json;

class UpdatePost extends \Magento\Checkout\Controller\Cart
{
    /**
     * @var RequestQuantityProcessor
     */
    private $quantityProcessor;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator
     * @param \Magento\Checkout\Model\Cart $cart
     * @param RequestQuantityProcessor $quantityProcessor
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Checkout\Model\Cart $cart,
       
        RequestQuantityProcessor $quantityProcessor = null
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
          
        );
       //$this->session  =  $session;
        $this->quantityProcessor = $quantityProcessor ?: $this->_objectManager->get(RequestQuantityProcessor::class);
        $this->checkoutSession = $checkoutSession;
        $this->cart = $cart;
        
       
    }

    /**
     * Empty customer's shopping cart
     *
     * @return void
     */
    protected function _emptyShoppingCart()
    {
        try {
            $this->cart->truncate()->save();
        } catch (\Magento\Framework\Exception\LocalizedException $exception) {
            $this->messageManager->addError($exception->getMessage());
        } catch (\Exception $exception) {
            $this->messageManager->addException($exception, __('We can\'t update the shopping cart.'));
        }
    }

    /**
     * Update customer's shopping cart
     *
     * @return void
     */
    protected function _updateShoppingCart()
    {  
        $quantitysum=0;
        $helperenable=$this->_objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isExtensionEnable();
       
        if($helperenable==1){
       
        try {
            
                $product_id;
                $producttype;
                
                $cartData = $this->getRequest()->getParam('cart');
                $items = $this->cart->getQuote()->getAllItems();

                if ($items) {
                    $minquantity;
                    $maxquantity;
                    $serializerClass = $this->_objectManager->get(Json::class);
                    foreach ($items as $key => $value) {
                       
                            $values=$value->getOptionByCode('info_buyRequest')->getValue();

                            $values=$serializerClass->unserialize($values);
                        
                            $product_id=$values['super_product_config']['product_id'];
                            $producttype=$values['super_product_config']['product_type'];
                        }
                    }
                            
                    if($producttype=='grouped'){

                        if (is_array($cartData)) {
                            foreach ($cartData as $cartkey=>$getQty) {

                                $quantity=$cartData[$cartkey]['qty'];
                                $quantitysum=$quantitysum+$quantity;

                            }
                        }

                        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                        $product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
                        $minquantity=$product->getminimum_quantity();
                        $maxquantity=$product->getmaximum_quantity();
                        
                        if($maxquantity){
                            if($quantitysum > $maxquantity){
                                $helper=$this->_objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMaximumQuantity();
                                $helper1 = str_replace("{max_quantity}", $maxquantity, $helper);
                                $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                                throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                                return false; 
            
                            }
                        }
                      
                        if($minquantity){
                            if($quantitysum < $minquantity){
                                $helper=$this->_objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMinimumQuantity();
                                $helper1 = str_replace("{min_quantity}", $minquantity, $helper);
                                $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                                throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                                return false; 
                            } 
                        }

                        if($maxquantity && $minquantity){
                            if($quantitysum > $maxquantity){
                                $helper=$this->_objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMaximumQuantity();
                                $helper1 = str_replace("{max_quantity}", $maxquantity, $helper);
                                $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                                throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                                return false; 
            
                            }

                            if($quantitysum < $minquantity){
                                $helper=$this->_objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMinimumQuantity();
                                $helper1 = str_replace("{min_quantity}", $minquantity, $helper);
                                $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                                throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                                return false; 
                            }  
                        }
                       
            
                    }
                    
                              
                if (!$this->cart->getCustomerSession()->getCustomerId() && $this->cart->getQuote()->getCustomerId()) {
                    $this->cart->getQuote()->setCustomerId(null);
                }
                $cartData = $this->quantityProcessor->process($cartData);
                $cartData = $this->cart->suggestItemsQty($cartData);
                $this->cart->updateItems($cartData)->save();
            
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError(
                $this->_objectManager->get(\Magento\Framework\Escaper::class)->escapeHtml($e->getMessage())
            );
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t update the shopping cart.'));
            $this->_objectManager->get(\Psr\Log\LoggerInterface::class)->critical($e);
        }
      }
    }

    /**
     * Update shopping cart data action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }

        $updateAction = (string)$this->getRequest()->getParam('update_cart_action');

        switch ($updateAction) {
            case 'empty_cart':
                $this->_emptyShoppingCart();
                break;
            case 'update_qty':
                $this->_updateShoppingCart();
                break;
            default:
                $this->_updateShoppingCart();
        }

        return $this->_goBack();
    }
}
