<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * set Additional option to product
 */
class SetAdditionalOptions implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $_request;

     /**
      * Json Serializer
      *
      * @var JsonSerializer
      */
    protected $jsonSerializer;
     /**
      * @var PublicCookieMetadataFactory
      */
    protected $cookieMetadataFactory;
    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;
    /**
     * @param RequestInterface $request
     * @param PublicCookieMetadataFactory $cookieMetadataFactory
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        RequestInterface $request,
        JsonSerializer $jsonSerializer,
        PublicCookieMetadataFactory $cookieMetadataFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        SessionManagerInterface $sessionManager
    ) {
        $this->_request = $request;
        $this->jsonSerializer = $jsonSerializer;
        $this->cookieMetadataFactory = $cookieMetadataFactory;
        $this->_productloader = $_productloader;
        $this->sessionManager = $sessionManager;
    }
 
    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $model = $objectManager->create('Mageants\ExtraFee\Model\ExtraFee');
        $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
        // Check and set information according to your need
        if ($this->_request->getFullActionName() == 'checkout_cart_add' || $this->_request->getFullActionName() == "checkout_cart_updateItemOptions") { //checking when product is adding to cart

            // GET CUSTOM OPTION
            $custom_option = [];
            if ($this->_request->getFullActionName() == 'checkout_cart_add') {
                $custom_option = $this->_request->getPost('options');
            }
            if ($this->_request->getFullActionName() == 'checkout_cart_updateItemOptions') {
                $custom_option = $this->_request->getPost('options');
            }

            $price=0;
            $product = $observer->getProduct();
            if ($product->getTypeId()=="configurable") {
                 $simpleproductid = $this->_request->getPost('productid');
                $simpleproduct = $this->_productloader->create()->load($simpleproductid);
                $simpleproprice = $simpleproduct->getPrice();
                $confiproprice = $product->getFinalPrice();
                if ($confiproprice==$simpleproprice) {

                    $price=$product->getFinalPrice();

                } else {

                    $price=$simpleproduct->getPrice();
                
                }
            } else {
                $price=$product->getPrice();
            }

            // FOR CUSTOM OPTION PRODUCT CODE START

            $cust_price = 0;
            $_product = $product;
            if ($_product->getOptions() && $custom_option) {
                foreach ($_product->getOptions() as $op) {
                    foreach ($op->getValues() as $value) {
                        foreach ($custom_option as $custom_key => $custom_value) {
                            if ($value->getOptionId() == $custom_key && $value->getOptionTypeId() == $custom_value) {
                                $cust_price = $cust_price + $value->getPrice();
                            }
                        }
                    }
                }
            }
            $price = $price + $cust_price;

            //  FOR CUSTOM OPTION PRODUCT CODE END

            $ExtraFee=[];
            if (!empty($this->_request->getPost('feesname'))) {
                $ExtraFee=$this->_request->getPost('feesname');
            } else {
                $overrideCat=$product->getOverrideCat();
                if ($overrideCat=="Yes") {
                    $ExtraFeeList=$product->getExtrafeelist();
                    $feeIds=explode(",", $ExtraFeeList);
                    $ExtraFeeCollection=$model->getCollection()
                        ->addFieldToSelect('id')
                        ->addFieldToFilter('estatus', 'Enabled')
                        ->addFieldToFilter('is_mandatory', 'Yes')
                        ->addFieldToFilter('apply_to', [
                                ['finset'=> 'Product']
                            ])
                        ->addFieldToFilter('id', ['in' => $feeIds]);
                     $ExtraFee=$ExtraFeeCollection->getData();
                } elseif ($overrideCat=="No") {
                    $ExtraFeeCollection=$model->getCollection()
                        ->addFieldToSelect('id')
                        ->addFieldToFilter('estatus', 'Enabled')
                        ->addFieldToFilter('is_mandatory', 'Yes')
                        ->addFieldToFilter('apply_to', [
                                ['finset'=> 'Category']
                            ]);
                     $ExtraFee=$ExtraFeeCollection->getData();
                } else {
                    $ExtraFeeList=$product->getExtrafeelist();
                    $feeIds=explode(",", $ExtraFeeList);
                    $categoryId=$product->getCategoryIds();
                    $ExtraFeeCatIdCollection=$model->getCollection()
                        ->addFieldToSelect('id')
                        ->addFieldToSelect('category_ids')
                        ->addFieldToFilter('estatus', 'Enabled')
                        ->addFieldToFilter('is_mandatory', 'Yes')
                        ->addFieldToFilter('apply_to', 'Category');
                    $ExtraFeeCatCollection=$model->getCollection()
                        ->addFieldToSelect('id')
                        ->addFieldToFilter('estatus', 'Enabled')
                        ->addFieldToFilter('is_mandatory', 'Yes')
                        ->addFieldToFilter('apply_to', 'Category');
                    $ExtraFeePrdCollection=$model->getCollection()
                        ->addFieldToSelect('id')
                        ->addFieldToFilter('estatus', 'Enabled')
                        ->addFieldToFilter('is_mandatory', 'Yes')
                        ->addFieldToFilter('apply_to', 'Product')
                        ->addFieldToFilter('id', ['in' => $feeIds]);
                     /*$ExtraCatFee=$ExtraFeeCatCollection->getData();
                     $ExtraPrdFee=$ExtraFeePrdCollection->getData();
                     $ExtraFee=array_merge($ExtraCatFee,$ExtraPrdFee);*/
                     $ExtraCatFee=[];
                     $ExtraPrdFee=[];
                    if (sizeof($ExtraFeeCatCollection) > 0) {
                        $data = $ExtraFeeCatIdCollection->getData();
                        $result = [];
                        foreach ($data as $datas) {
                            $feeCatId = explode(",", $datas['category_ids']);
                            $result=array_intersect($feeCatId, $categoryId);
                            
                            if (sizeof($result) > 0) {
                                $ExtraCatFee=$ExtraFeeCatCollection->getData();
                            }
                        }
                    }
                    if (sizeof($ExtraFeePrdCollection) > 0) {
                        $ExtraPrdFee=$ExtraFeePrdCollection->getData();
                    }
                     $ExtraFee=array_merge($ExtraCatFee, $ExtraPrdFee);
                }
            }
            $additionalOptions = [];
            $i=0;
            if (sizeof($ExtraFee) > 0) {
                foreach ($ExtraFee as $fee) {
                    $data=$model->load($fee);
                    $info=$data->getData();
                    if ($info['type']=='Fixed') {
                        $additionalOptions[$i] = [
                            'label' => __($info['feesname']),
                            'value' => $priceHelper->currency($info['amount'], true, false),
                        ];
                    } else {
                        $amount=($price*$info['amount'])/100;
                        $additionalOptions[$i] = [
                            'label' => __($info['feesname']) ,
                            'value' => $priceHelper->currency($amount, true, false),
                        ];
                    }
                    $i++;
                }
            }
            if ($observer->getProduct()->getTypeId() != "bundle") {
                $observer->getProduct()->addCustomOption('additional_options', $this->jsonSerializer->serialize($additionalOptions));
            } else {
                $addselectionIds = [
                        'label' => __("ExtraFee ") ,
                        'value' => 0,
                    ];
                
                $observer->getProduct()->addCustomOption('additional_options', $this->jsonSerializer->serialize($additionalOptions));
                $observer->getProduct()->addCustomOption('bundle_selection_ids', $this->jsonSerializer->serialize($addselectionIds));
            }
        }
    }
}
