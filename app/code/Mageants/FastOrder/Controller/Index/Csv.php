<?php
/**
 * @category Mageants FastOrder
 * @package Mageants_FastOrder
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FastOrder\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class Csv extends \Magento\Framework\App\Action\Action
{
    protected $request;
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    protected $_customerSession;
    protected $_tierPriceManagement;
    protected $_pricingHelper;
    
    /**
     * @param Context $context,
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory,
     * @param JsonFactory $resultJsonFactory,
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper,
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Catalog\Model\Product\TierPriceManagement $tierPriceManagement,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        Request $request
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->productRepository = $productRepository;
        $this->_priceHelper = $priceHelper;
        $this->_storeManager = $storeManager;
        $this->_customerSession = $customerSession;
        $this->_tierPriceManagement = $tierPriceManagement;
        $this->_pricingHelper = $pricingHelper;
        $this->request = $request;
        parent::__construct($context);
    }
    
    /**
     * return json string
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $files = $this->request->getFiles()->toArray();

        $fh = fopen($files['file']['tmp_name'], 'r+');
        $ext = explode(".", $files['file']["name"]);
        
        $i = 0;
        $result = [];
        $errorResult = [];
        
        while (($row = fgetcsv($fh)) !== false) {
            $i++;
            if ($i == 1) {
                continue;
            }
                
            if ($ext[1] != "csv") {
                $responce=["error" => "true","message" => "invalid file type"];
                return $this->resultJsonFactory->create()->setData($responce);
            }
            $sku = $row[0];
            $qty = $row[1];
            $product = $this->productRepository->get($sku);
            $tierPriceQty=0;
            $tier_price=0;
            $customerGroupId = $this->_customerSession->create()->getCustomerGroupId();

            if (($product->getTypeId() == 'simple' || $product->getTypeId() == 'virtual' || $product->getTypeId() == 'downloadable') && $qty > 0) {
                $img = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .'catalog/product'. $product->getData('thumbnail');

                if ($product->getSpecialPrice()) {
                    $formattedPrice = $this->_pricingHelper->currency($product->getSpecialPrice(), true, false);
                    $product_price_amount = $product->getSpecialPrice();
                } else {
                    $formattedPrice = $this->_priceHelper->currency($product->getPrice(), true, false);
                    $product_price_amount = $product->getPrice();
                }

                $product_url = $product->getProductUrl();
                $product_type = $product->getTypeId();

                //----------Tier Price Start-----------

                $allTiers = $product->getData('tier_price');
                $tier_price_array = [];
                $tierPriceUpdate=[];
                if ($allTiers) {
                    foreach ($allTiers as $key => $value) {
                        $tierPriceQty = (int)$value['price_qty'];
                        if ($value['cust_group'] == $customerGroupId || $value['cust_group'] == 32000) {
                            if ($value['website_id'] == 0) {
                                $tier_price= $value['price'];
                            } else {
                                $tier_price =$value['website_price'];
                            }
                            $tierPriceUpdate[$tierPriceQty]=$tier_price;
                            $formattedTierPrice = $this->_pricingHelper->currency(number_format($tier_price, 2), true, false);
                            $savePercentageFormat = ceil(100 - ((100 / $product->getPrice())* $value['price'])) ."%";
                            $tier_price_array[] = "Buy $tierPriceQty for ".$formattedTierPrice." each and save ".$savePercentageFormat;
                        }
                    }
                }
                if (sizeof($tierPriceUpdate) !=0) {
                    $tierPriceUpdate = implode(', ', array_map(
                        function ($v, $k) {
                            if (is_array($v)) {
                                return $k.'[]='.implode('&'.$k.'[]=', $v);
                            } else {
                                return $k.'='.$v;
                            }
                        },
                        $tierPriceUpdate,
                        array_keys($tierPriceUpdate)
                    ));
                }
                if (isset($tier_price_array)) {
                    $tier_price_string=implode('<br>', $tier_price_array);
                } else {
                    $tier_price_string = '';
                }
            
                //----------Tier Price End-----------

                $result[] = ["qty"=>$qty,"product_price"=>$formattedPrice,"popup"=>0,"product_name"=>$product->getName(),"product_sku"=>$product->getSku(),"product_id"=>$product->getId(),"product_thumbnail"=>$img,"product_price_amount"=>$product_price_amount,"product_url"=>$product_url,"product_type"=>$product_type, "product_tier_price"=>$tier_price_string, "tierPriceUpdate"=>$tierPriceUpdate];
            } else {
                $errorResult[] = $product->getSku();
            }
        }
        if (sizeof($errorResult)!=0) {
            $errorMessage = 'Product(s) with SKU '.implode(', ', $errorResult).' can not be uploaded.';
            $this->messageManager->addError($errorMessage);
        }
        $this->getResponse()->setBody(json_encode($result));
    }
}
