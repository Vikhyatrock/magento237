<?php
namespace Mageants\MinimumMaximumQuantity\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Checkout\Model\Session;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\App\RequestInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Serialize\SerializerInterface;
use \Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Http\Context as customerSession;
 
class RestrictAddToCart implements ObserverInterface
{
    protected $request;
    protected $_url;
    protected $_responseFactory;
    private $serializer;
    protected $cart;
    public function __construct(
        RequestInterface $request,
        Cart $cart,
        Session $checkoutSession,
        ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Magento\Framework\UrlInterface $url,
        SerializerInterface $serializer,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Mageants\MinimumMaximumQuantity\Helper\Data $dataHelper,
        \Magento\Framework\App\Action\Context $contextredirect
    )
    {
        $this->request = $request;
        $this->_checkoutSession = $checkoutSession;
        $this->_scopeConfig = $scopeConfig;
        $this->_productloader = $_productloader;
        $this->_productRepository = $productRepository;
        $this->serializer = $serializer;
        $this->_responseFactory = $responseFactory;
        $this->_url = $url;
        $this->_messageManager = $messageManager;
        $this->_resultFactory = $context->getResultFactory();
        $this->dataHelper = $dataHelper;
        $this->_redirect = $contextredirect->getRedirect();
        $this->cart = $cart;
    }
   
    public function execute(\Magento\Framework\Event\Observer $observer) {  

                     
        $quantityadd=false;
        $sum=0;
        $helperenable = $this->dataHelper->isExtensionEnable();
 
        if($helperenable==1){
        if ($this->request->getFullActionName() == 'checkout_cart_add' || $this->request->getFullActionName() == 'checkout_cart_updateItemOptions') {
             $productId=$this->request->getParam('product');
             $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
             $product = $objectManager->get('Magento\Catalog\Model\Product')->load($productId);
             $productType = $product->getTypeID();
             $maximumquantity;
             $minimumquantity;

            if($productType == "grouped")
            {   
                      
                $qu=$this->request->getParam('super_group');

                foreach ($qu as $value) {
                   $sum=$sum+$value;
                } 
                $quote = $this->cart->getQuote();
                $controller = $observer->getControllerAction();
                $cartItemsQty = $quote->getItemsSummaryQty();
                $cartItemsQuantity=(int)$cartItemsQty;
                $cartquantitysum=$cartItemsQuantity+$sum; 

                $product = $this->_productRepository->getById($this->request->getParam('product'));
              
                $attributes=$product->getAttributes();
               
                foreach ($attributes as  $attribute) {
                    if ($attribute->getAttributeCode() == 'maximum_quantity') {
                            
                        $maximumquantity=$attribute->getFrontend()->getValue($product);
                    }

                    if ($attribute->getAttributeCode() == 'minimum_quantity') {
                            
                        $minimumquantity=$attribute->getFrontend()->getValue($product);
                    }
                }
                if($maximumquantity){
                    if($sum > $maximumquantity){

                            $helper = $this->dataHelper->isMaximumQuantity();
                            $helper1 = str_replace("{max_quantity}", $maximumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                            
                    }

                    if($cartquantitysum > $maximumquantity){
                            $helper = $this->dataHelper->isMaximumQuantity();
                            $helper1 = str_replace("{max_quantity}", $maximumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                    }
                }

                if($minimumquantity){
                    if($sum < $minimumquantity && !$cartItemsQuantity){

                            $helper = $this->dataHelper->isMinimumQuantity();
                            $helper1 = str_replace("{min_quantity}", $minimumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                            
                    }
                } 

                if($maximumquantity && $minimumquantity){
                    if($sum > $maximumquantity && !$cartItemsQuantity){

                            $helper = $this->dataHelper->isMaximumQuantity();
                            $helper1 = str_replace("{max_quantity}", $maximumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                            
                    }

                    if($cartquantitysum > $maximumquantity){
                            $helper = $this->dataHelper->isMaximumQuantity();
                            $helper1 = str_replace("{max_quantity}", $maximumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                    }
                    if($sum < $minimumquantity && !$cartItemsQuantity){

                            $helper = $this->dataHelper->isMinimumQuantity();
                            $helper1 = str_replace("{min_quantity}", $minimumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                            
                    }

                    if($sum < $minimumquantity && $cartquantitysum > $maximumquantity){

                            $helper = $this->dataHelper->isMaximumQuantity();
                            $helper1 = str_replace("{max_quantity}", $maximumquantity, $helper);
                            $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                            throw new \Magento\Framework\Exception\LocalizedException(__($helper2));
                            return false;
                            
                    } 
                } 
                    
            }
                    
  
        }
      }      
    }
   
}


