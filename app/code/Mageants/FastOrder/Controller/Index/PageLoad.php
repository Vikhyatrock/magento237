<?php
/**
 * @category Mageants FastOrder
 * @package Mageants_FastOrder
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FastOrder\Controller\Index;

use Magento\Framework\App\Action\Context;

class PageLoad extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var \Magento\Catalog\Model\Product\Option
     */
    protected $_productOption;
    
    /**
     * @var  \Magento\Catalog\Model\Product\Attribute\Source\Status
     */
    protected $_productStatus;
    
    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $_productVisibility;
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    protected $_priceHelper;
    
    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_stockItem;
    
    /**
     * @var \Magento\CatalogInventory\Api\StockStateInterface
     */
    protected $_fastOrderHelper;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;
    protected $_customerSession;
    protected $_tierPriceManagement;
    protected $_pricingHelper;
    
    /**
     * @param Context $context,
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory,
     * @param \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
     * @param \Magento\Catalog\Model\ProductFactory $_productloader,
     * @param \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
     * @param \Magento\Catalog\Model\Product\Visibility $productVisibility,
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager,
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper,
     * @param \Mageants\FastOrder\Helper\Data $fastOrderHelper,
     * @param \Magento\CatalogInventory\Api\StockStateInterface $stockItem
     */
    public function __construct(
        Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $_productloader,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Pricing\Helper\Data $priceHelper,
        \Mageants\FastOrder\Helper\Data $fastOrderHelper,
        \Magento\Catalog\Model\Product\Option $productOption,
        \Magento\CatalogInventory\Api\StockStateInterface $stockItem,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Catalog\Model\Product\TierPriceManagement $tierPriceManagement,
        \Magento\Framework\Pricing\Helper\Data $pricingHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        $this->_productOption = $productOption;
        $this->_productloader = $_productloader;
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productStatus = $productStatus;
        $this->_productVisibility = $productVisibility;
        $this->_storeManager = $storeManager;
        $this->_priceHelper = $priceHelper;
        $this->_stockItem = $stockItem;
        $this->_fastOrderHelper=$fastOrderHelper;
        $this->_customerSession = $customerSession;
        $this->_tierPriceManagement = $tierPriceManagement;
        $this->_pricingHelper = $pricingHelper;
        $this->productRepository = $productRepository;
        parent::__construct($context);
    }
    
    /**
     * return json string
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $result = [];
        $items = [];
        if (isset($params['prodData'])) {
            $prodData = $params['prodData'];
            if (!empty($prodData)) {
                foreach ($prodData as $Mainprod) {
                    foreach ($Mainprod as $prod) {
                        if (isset($prod['productSku']) && isset($prod['qty'])) {
                            $keyword = $prod['productSku'];
                            $paramQty = $prod['qty'];
                            $productId = $prod['productId'];

                            $product_type = $prod['productType'];
                            $productOptions = "";
                            if (isset($prod['productOptions'])) {
                                $productOptions = $prod['productOptions'];
                            }

                            if (isset($prod['childProductId'])) {
                                $childProductId = $prod['childProductId'];
                                $productId = $childProductId;
                            }

                            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                            $configurableProductType = $objectManager->create('\Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable');
                            $childOfConfProduct = $configurableProductType->getParentIdsByChild($productId);
                            if ($childOfConfProduct) {
                                $productLoader = $this->_productloader->create()->load($productId);
                                $keyword = $productLoader->getSku();
                            }
                            $product = $this->productRepository->get($keyword);
                            $keyword = $product->getSku();
                            
                            $thumb_width = 70;
                            $img = "";

                            $confMainProductLoader = $this->_productloader->create()->load($prod['productId']);
                            $imageHelper = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Catalog\Helper\Image::class);
                            if ($confMainProductLoader->getData('thumbnail')) {
                                $img = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) .'catalog/product'. $confMainProductLoader->getData('thumbnail');
                            } else {
                                $img = $imageHelper->getDefaultPlaceholderUrl('small_image');
                            }

                            $customOptions = $this->_productOption->getProductOptionCollection($product);

                            // if (count($customOptions) > 0 || $product->hasCustomOptions() || $product->getTypeId() == 'configurable' || $product->getTypeId()=='grouped') {
                            //     $popup = 1;
                            // } else {
                            //     $popup = 0;
                            // }
                            // $productCustomOptionsPrice = "";
                            // if ($product->hasCustomOptions() && count($customOptions) > 0) {
                            //     $productCustomOptionsPrice = $prod['productCustomOptionsPrice'];
                            // }

                            if ($product->getSpecialPrice()) {
                                $formattedPrice = $this->_pricingHelper->currency($product->getSpecialPrice(), true, false);
                            } else {
                                $formattedPrice = $this->_priceHelper->currency($product->getPrice(), true, false);
                            }
                            // $formattedPrice = $this->_priceHelper->currency($product->getPrice(), true, false);

                            $qty = $this->_stockItem->getStockQty($product->getId(), $product->getStore()->getWebsiteId());
                                
                            /*$productName = str_ireplace($keyword, '<span class="mgantshighlight">'.$keyword.'</span>', $product->getName());

                            $productSku = str_ireplace($keyword, '<span class="mgantshighlight">'.$keyword.'</span>', $product->getSku());*/

                            $productName = $product->getName();

                            $productSku = $product->getSku();
                            if (isset($prod['groupedPrice'])) {
                                $productPrice = $prod['groupedPrice'];
                            }elseif ($product->getSpecialPrice()) {
                                $productPrice = $product->getSpecialPrice();
                            } 
                            else {
                                $productPrice = $product->getPrice();
                            }

                            if ($childOfConfProduct) {
                                $productName = $prod['productName'];
                                $productSku = $prod['productSku'];
                                $product_url = $prod['productUrl'];
                                $product_id = $prod['productId'];
                            } else {
                                $product_url = $product->getProductUrl();
                            }

                            //----------Tier Price Start-----------
                            $tierPriceQty=0;
                            $tier_price=0;
                            $tierPriceUpdate=[];
                            $tierPriceProduct = $this->productRepository->get($product->getSku());
                            $customerGroupId = $this->_customerSession->create()->getCustomerGroupId();
                            $allTiers = $tierPriceProduct->getData('tier_price');
                            $tier_price_array = [];
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
                                        $savePercentageFormat = ceil(100 - ((100 / $tierPriceProduct->getPrice())* $value['price'])) ."%";
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

                            $items[] = ["qty"=>$paramQty,"product_price"=>$formattedPrice,"popup"=>0,"product_name"=>$productName,"product_sku"=> $productSku,'product_sku_highlight'=>$productSku,"product_id"=>$prod['productId'],"product_thumbnail"=>$img,"product_price_amount"=>$productPrice,"product_url"=>$product_url,"product_type"=>$product_type, "product_tier_price"=>$tier_price_string, "tierPriceUpdate"=>$tierPriceUpdate,"productOptions"=>$productOptions];
                        }
                        $result = $items;
                    }
                }
            }
        }
        $this->getResponse()->setBody(json_encode($result));
    }
}
