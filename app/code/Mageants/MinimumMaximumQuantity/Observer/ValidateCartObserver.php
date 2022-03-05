<?php
namespace Mageants\MinimumMaximumQuantity\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\App\Http\Context as customerSession;
use Magento\Framework\Serialize\SerializerInterface;

class ValidateCartObserver implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    protected $messageManager;
    /**
     * @var RedirectInterface
     */
    protected $redirect;
    /**
     * @var Cart
     */
    protected $cart;
    protected $request;
    protected $serializer;
    /**
     * @param ManagerInterface $messageManager
     * @param RedirectInterface $redirect
     * @param CustomerCart $cart
     */
    public function __construct(
        ManagerInterface $messageManager,
        RedirectInterface $redirect,
        CustomerCart $cart,
        SerializerInterface $serializer,
        \Magento\Framework\App\Action\Context $context
    ) {
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->cart = $cart;
        $this->serializer = $serializer;
        $this->request = $context->getRequest();
    }
    /**
     * Validate Cart Before going to checkout
     * - event: controller_action_predispatch_checkout_index_index
     *
     * @param Observer $observer
     * @return void
     */

    public function execute(Observer $observer)
    {
       
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $items = $this->cart->getQuote()->getAllItems();
         $itemcount=$this->cart->getItemsCount();
         $totalitem;
         $totalitemqty;
         $totalitemquantitysum=0;
         $totalitemquantitysumnew=0;
         $product_id='';
         $producttype='';
         $arrId=array();
         $arrQuantity=array();
         foreach ($items as $key => $value) {
           
            if($value->getProductType()=='grouped')
            {
                $totalitem=$value->getItemId();
                $totalitemqty=$value->getQty();
                $values=$value->getOptionByCode('info_buyRequest')->getValue();
                $values=$this->serializer->unserialize($values);
            
                $product_id=$values['super_product_config']['product_id'];
                $producttype=$values['super_product_config']['product_type'];
            }
        }

        if($producttype=='grouped'){

            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
            $minquantity=$product->getminimum_quantity();
            $maxquantity=$product->getmaximum_quantity();

            $quote = $this->cart->getQuote();
            $controller = $observer->getControllerAction();
            $cartItemsQty = $quote->getItemsQty();
            $cartItemsQuantity=(int)$cartItemsQty;
         
            if($minquantity && $maxquantity){
                if($cartItemsQuantity > $maxquantity){

                        $helper=$objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMinimumQuantity();
                        $helper1 = str_replace("{max_quantity}", $maxquantity, $helper);
                        $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                        $this->messageManager->addNoticeMessage(
                            __($helper2)
                        );
                        $this->redirect->redirect($controller->getResponse(), 'checkout/cart');

                }
            }
            
            if($minquantity){
                if($cartItemsQuantity < $minquantity){
                    $helper=$objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMinimumQuantity();
                    $helper1 = str_replace("{min_quantity}", $minquantity, $helper);
                    $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                    $this->messageManager->addNoticeMessage(
                        __($helper2)
                    );
                    $this->redirect->redirect($controller->getResponse(), 'checkout/cart');
                } 
            }

            if($maxquantity){
                if($cartItemsQuantity > $maxquantity){
                    $helper=$objectManager->create('Mageants\MinimumMaximumQuantity\Helper\Data')->isMaximumQuantity();
                    $helper1 = str_replace("{max_quantity}", $maxquantity, $helper);
                    $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                    $this->messageManager->addNoticeMessage(
                        __($helper2)
                    );
                    $this->redirect->redirect($controller->getResponse(), 'checkout/cart');
                } 
            }

        }

    }
}