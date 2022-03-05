<?php
namespace Mageants\MinimumMaximumQuantity\Plugin;

use Magento\Checkout\Controller\Sidebar\UpdateItemQty as coreUpdateItemQty;

use Magento\Framework\Json\Helper\Data as coreData;
use Magento\Checkout\Model\Sidebar;
use Magento\Catalog\Model\ProductFactory;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\Session;
use Magento\Framework\Serialize\SerializerInterface;

use Magento\Framework\Exception\LocalizedException;
class UpdateItemQty
{
    protected $dataHelper;
    protected $jsonHelper;
    protected $sidebar;
    protected $quoteItemFactory;
    protected $productFactory;
    protected $cart;
    protected $serializer;


    public function __construct(
        
        
        Sidebar $sidebar,
        coreData $jsonHelper,
        Cart $cart,
        SerializerInterface $serializer,
        ProductFactory $productFactory,
        \Mageants\MinimumMaximumQuantity\Helper\Data $dataHelper,
        \Magento\Framework\App\Request\Http $request,
        Session $checkoutSession
    )
    {
        $this->dataHelper = $dataHelper;
        $this->jsonHelper = $jsonHelper;
        $this->sidebar = $sidebar;
        $this->productFactory = $productFactory;
        $this->serializer = $serializer;
        $this->cart = $cart;
        $this->request = $request;
        $this->_checkoutSession = $checkoutSession;
    }

    public function aroundExecute(coreUpdateItemQty $subject, \Closure $proceed)
    {
    
        $helperenable = $this->dataHelper->isExtensionEnable();
        if($helperenable==1){
             $items = $this->cart->getQuote()->getAllItems();
             $itemcount=$this->cart->getItemsCount();
             $totalitem;
             $totalitemqty;
             $totalitemquantitysum=0;
             $totalitemquantitysumnew=0;
             $product_id;
             $producttype;
             $arrId=array();
             $arrQuantity=array();
             foreach ($items as $key => $value) {
               
                $totalitem=$value->getItemId();
                $totalitemqty=$value->getQty();
                $values=$value->getOptionByCode('info_buyRequest')->getValue();
                $values=$this->serializer->unserialize($values);
            
                $product_id=$values['super_product_config']['product_id'];
                $producttype=$values['super_product_config']['product_type'];
            
                array_push($arrId, $totalitem);
                array_push($arrQuantity, $totalitemqty);
            
            }

            $arrIdlength=count($arrId);
            
            $arrQuantitylength=count($arrQuantity);
   
            if($producttype=='grouped'){
           
                for($j=0;$j<$arrQuantitylength;$j++){
                     $totalitemquantitysum= $totalitemquantitysum+$arrQuantity[$j];
                }

                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $product = $objectManager->get('Magento\Catalog\Model\Product')->load($product_id);
                $minquantity=$product->getminimum_quantity();
                $maxquantity=$product->getmaximum_quantity();

                $updatequantityid=$this->request->getParam('item_id');
                $updatequantity=$this->request->getParam('item_qty');
              
                for($i=0;$i<$arrIdlength;$i++){
                      if($arrId[$i]==$updatequantityid){

                        if($arrQuantity[$i]!=$updatequantity){
                            $totalitemquantitysum=$totalitemquantitysum-$arrQuantity[$i];
                            $totalitemquantitysum=$totalitemquantitysum+$updatequantity; 
                        }
                      } 
                }
            }

            try{

                if($maxquantity){
                    if($totalitemquantitysum > $maxquantity){

                        $helper = $this->dataHelper->isMaximumQuantity();
                        $helper1 = str_replace("{max_quantity}", $maxquantity, $helper);
                        $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                        return $this->jsonResponse($subject,$helper2);
                               
                    }

                }
                
                if($minquantity){
                    if($totalitemquantitysum < $minquantity){
                    
                        $helper = $this->dataHelper->isMinimumQuantity();
                        $helper1 = str_replace("{min_quantity}", $minquantity, $helper);
                        $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                        return $this->jsonResponse($subject,$helper2);
                                            
                    } 
                }

                if($maxquantity && $minquantity){
                    if($totalitemquantitysum > $maxquantity){

                        $helper = $this->dataHelper->isMaximumQuantity();
                        $helper1 = str_replace("{max_quantity}", $maxquantity, $helper);
                        $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                        return $this->jsonResponse($subject,$helper2);
                               
                    }

                    if($totalitemquantitysum < $minquantity){
                    
                        $helper = $this->dataHelper->isMinimumQuantity();
                        $helper1 = str_replace("{min_quantity}", $minquantity, $helper);
                        $helper2 = str_replace("{grouped_ product_name}", $product->getName(), $helper1);
                        return $this->jsonResponse($subject,$helper2);
                                            
                    } 

                }

               
            }
            catch (\Exception $e) {
                return $this->jsonResponse($subject,$e->getMessage());
               
            }
                
       return $proceed();     
    }
  }

    protected function jsonResponse($subject, $error = '')
    {
        return $subject->getResponse()->representJson(
            $this->jsonHelper->jsonEncode($this->sidebar->getResponseData($error))
        );
    }

 
}
