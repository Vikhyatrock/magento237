<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ExtraFee\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    protected $storeManager;
    
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager
     */
    protected $_cookieManager;
    /**
     * @var \Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory $cookieMetadataFactory
     */
    protected $_cookieMetadataFactory;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface $sessionManager
     */
    protected $sessionManager;
    /**
     * @var CurrencyFactory
     */
    private $currencyCode;

    /**
     * @param \Magento\Framework\App\Helper\Context
     * @param \Magento\Store\Model\StoreManagerInterface
     * @param \Mageants\ExtraFee\Model\ExtraFee\Source\StoreList
     * @param \Mageants\ExtraFee\Model\ExtraFee\Source\CategoryList
     * @param \Mageants\ExtraFee\Model\ExtraFee
     * @param \Magento\Catalog\Model\ProductRepository
     * @param \Magento\Framework\Stdlib\CookieManagerInterface
     * @param \Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory
     * @param \Magento\Framework\Session\SessionManagerInterface
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mageants\ExtraFee\Model\ExtraFee\Source\StoreList $storeList,
        \Mageants\ExtraFee\Model\ExtraFee\Source\CategoryList $categoryList,
        \Mageants\ExtraFee\Model\ExtraFee $feeCollection,
        \Magento\Catalog\Model\ProductRepository $productRepository,
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\PublicCookieMetadataFactory $cookieMetadataFactory,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager
    ) {
        parent::__construct($context);
        $this->_collection = $feeCollection;
        $this->storeManager = $storeManager;
        $this->_allStoreList=$storeList;
        $this->_allCategoryList=$categoryList;
        $this->scopeConfig = $context->getScopeConfig();
        $this->_productRepository = $productRepository;
        $this->_cookieManager = $cookieManager;
        $this->_cookieMetadataFactory = $cookieMetadataFactory;
        $this->sessionManager = $sessionManager;
    }
    
    /**
     * Get Store Config Value
     * @return string
     */
    public function getExtraFeeConfig($configPath)
    {
        return $this->scopeConfig->getValue(
            $configPath,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getIsEnabled()
    {
        return $this->scopeConfig->getValue(
            'mageants_extrafee/setting/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get Store List
     * @return Array
     */
    public function getStoreList()
    {
        return $this->_allStoreList->toOptionArray();
    }

    /**
     * Get Category List
     * @return Array
     */
    public function getCategoryList()
    {
        return $this->_allCategoryList->toOptionArray();
    }

    /**
     * Get Store Id
     * @return Int
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getStoreId();
    }

    /**
     * Get Extra fee product collection
     * @return collection
     */
    public function getExtraFeeProductCollection()
    {
        $collection = $this->_collection->getCollection()->addFieldToFilter('apply_to', 'Product')
        ->addFieldToFilter('estatus', 'Enabled');
        return $collection;
    }

    /**
     * Get Store Config Value
     * @return string
     */
    public function isModuleEnabled()
    {
        return $this->scopeConfig->getValue(
            "mageants_extrafee/setting/enable",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Get Store Config Value
     * @return string
     */
    public function getCodFee()
    {
        $CodFee=$this->scopeConfig->getValue(
            "mageants_extrafee/setting/cod_fee",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        return number_format((float)$CodFee, 2, '.', '');
    }
    /**
     * Get Store Config Value
     * @return string
     */
    public function getCheckoutFeeLabel()
    {
        $ShippingLabel=$this->scopeConfig->getValue(
            "mageants_extrafee/setting/shipping_title",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($ShippingLabel=='') {
            $ShippingLabel="Shipping Extra Fee";
        }
        return $ShippingLabel;
    }
    
    /**
     * Get Store Config Value
     * @return string
     */
    public function getProductFeeEachQty()
    {
        return $this->scopeConfig->getValue(
            "mageants_extrafee/setting/prd_fee_qty",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function getAllMandatoryShipingfeeLable()
    {
        if ($this->isModuleEnabled()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
            $storeId=$this->getStoreId();
            $cart = $objectManager->get(\Magento\Checkout\Model\Cart::class);
            $subTotal = $cart->getQuote()->getSubtotal();
            $storeIds=['0' =>'0','1'=>$storeId];
            $collection = $this->_collection->getCollection()
                ->addFieldToFilter('apply_to', 'Shipping')
                ->addFieldToFilter('estatus', 'Enabled')
                ->addFieldToFilter('is_mandatory', 'Yes')
                ->addFieldToFilter('store_id', ['in'=>$storeIds]);
            $sizecol=sizeof($collection);
            $shipfeesname="";
            if ($sizecol > 0) {
                $mandatoryShippingAmount=0;
                $feesname="";
                foreach ($collection->getData() as $col) {
                    $amount="";
                    if ($col['type']=="Fixed") {
                        $amount=$col['amount'];
                    } else {
                        $amount=($subTotal*$col['amount'])/100;

                    }
                    $mandatoryShippingAmount = $mandatoryShippingAmount+$amount;
                    $feesname=$priceHelper->currency($amount, true, false).' - '.__($col['feesname']);
                    if ($shipfeesname == "" && empty($shipfeesname)) {
                        $shipfeesname .= $feesname;
                    } else {
                        $shipfeesname .= "+".$feesname;
                    }
                }
                if ($mandatoryShippingAmount) {
                    $shipfeesname .= "=".$priceHelper->currency($mandatoryShippingAmount, true, false);
                }
            }
            return $shipfeesname;
        }
        return;
    }

    public function getAllMandatoryOrderfeeLable()
    {
        if ($this->isModuleEnabled()) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
            $storeId=$this->getStoreId();
            $cart = $objectManager->get(\Magento\Checkout\Model\Cart::class);
            $subTotal = $cart->getQuote()->getSubtotal();
            $storeIds=['0' =>'0','1'=>$storeId];
            $collection = $this->_collection->getCollection()
                ->addFieldToFilter('apply_to', 'Order')
                ->addFieldToFilter('estatus', 'Enabled')
                ->addFieldToFilter('is_mandatory', 'Yes')
                ->addFieldToFilter('store_id', ['in'=>$storeIds]);
            $sizecol=sizeof($collection);
            $orderfeesname="";
            if ($sizecol > 0) {
                $mandatoryOrderfeeAmount=0;
                $feesname="";
                foreach ($collection->getData() as $col) {
                    $amount="";
                    if ($col['type']=="Fixed") {
                        $amount=$col['amount'];
                    } else {
                        $amount=($subTotal*$col['amount'])/100;
                    }
                    $mandatoryOrderfeeAmount = $mandatoryOrderfeeAmount+$amount;
                    $feesname=$priceHelper->currency($amount, true, false).' - '.__($col['feesname']);
                    if ($orderfeesname == "" && empty($orderfeesname)) {
                        $orderfeesname .= $feesname;
                    } else {
                        $orderfeesname .= "+".$feesname;
                    }
                }
                if ($mandatoryOrderfeeAmount) {
                    $orderfeesname .= "=".$priceHelper->currency($mandatoryOrderfeeAmount, true, false);
                }
            }
            return $orderfeesname;
        }
        return;
    }

    /**
     * Get checkout fee amount
     * @return Array
     */
    public function getCheckoutFeeAmount()
    {
        if ($this->isModuleEnabled()) {
            $checkoutFee=[];
            $storeId=$this->getStoreId();
            $storeIds=['0' =>'0','1'=>$storeId];
            $collection = $this->_collection->getCollection()
            ->addFieldToFilter('apply_to', 'Shipping')
            ->addFieldToFilter('estatus', 'Enabled')
            ->addFieldToFilter('store_id', ['in'=>$storeIds]);
            $sizecol=sizeof($collection);
            if ($sizecol>0) {
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
                $subTotal = $cart->getQuote()->getSubtotal();
                $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
                $feesname=[];
                $mandatoryShippingAmount = 0;
                foreach ($collection->getData() as $col) {
                    if ($col['is_mandatory'] != 'Yes') {
                        $data=[];
                        $amount="";
                        //$selectedBox='';
                        // if($col['is_mandatory']=="Yes")
                        // { $selectedBox="true"; }

                        if ($col['type']=="Fixed") {
                            $amount=$col['amount'];

                        } else {
                            $amount=($subTotal*$col['amount'])/100;
                        }
                        $data['amount']=$priceHelper->currency($amount, true, false);
                        $data['price']=$amount;
                        $data['is_mandatory']=$col['is_mandatory'];
                        $data['id']=$col['id'];
                        $data['feesname']=$priceHelper->currency($amount, true, false).' - '.__($col['feesname']);
                        $checkoutFee[]=$data;
                    } else {
                        $amount="";
                        if ($col['type']=="Fixed") {
                            $amount=$col['amount'];

                        } else {
                            $amount=($subTotal*$col['amount'])/100;
                        }
                        $mandatoryShippingAmount = $mandatoryShippingAmount+$amount;
                        $feesname[]=$priceHelper->currency($amount, true, false).' - '.__($col['feesname']);
       
                           $metadata = $this->_cookieMetadataFactory->create()
                              ->setPath($this->sessionManager->getCookiePath())
                              ->setDomain($this->sessionManager->getCookieDomain());
                       
                    }
                }
                if (sizeof($feesname)>0) {
                    $ExtraShip = implode(' + ', $feesname);
                    $this->_cookieManager->setPublicCookie('mandatoryShippingExtraFee', $ExtraShip, $metadata);
                    $this->_cookieManager->setPublicCookie('mandatoryShippingAmount', $mandatoryShippingAmount, $metadata);
                }
            }

            return $checkoutFee;
        }
        return;
    }
    /**
     * Get Order fee Amount
     * @return Array
     */
    public function getOrderFeeAmount()
    {
        $orderFee=[];
        $storeId=$this->getStoreId();
        $storeIds=['0' =>'0','1'=>$storeId];

        $collection = $this->_collection->getCollection()
            ->addFieldToFilter('apply_to', 'Order')
            ->addFieldToFilter('estatus', 'Enabled')
            ->addFieldToFilter('store_id', ['in'=>$storeIds]);
        $sizecol=sizeof($collection);
        if ($sizecol>0) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
            $subTotal = $cart->getQuote()->getSubtotal();
            $priceHelper = $objectManager->create('Magento\Framework\Pricing\Helper\Data');
            $data=[];
            $feesname=[];
            $mandatoryOrderExtraFeeIds=[];
            $mandExtraFeeAmount = 0;
            foreach ($collection->getData() as $col) {
                if ($col['is_mandatory'] != 'Yes') {
                    $data=[];
                    $amount="";
                
                    if ($col['type']=="Fixed") {
                        $amount=$col['amount'];

                    } else {
                        $amount=($subTotal*$col['amount'])/100;
                    }
                    $data['amount']=$priceHelper->currency($amount, true, false);
                    $data['price']=$amount;
                    $data['is_mandatory']=$col['is_mandatory'];
                    $data['id']=$col['id'];
                    $data['feesname']=$priceHelper->currency($amount, true, false).' - '.__($col['feesname']);
                    $orderFee[]=$data;
                } else {
                    $amount="";
                    if ($col['type']=="Fixed") {
                        $amount=$col['amount'];

                    } else {
                        $amount=($subTotal*$col['amount'])/100;
                    }
                    $feesname[]=$priceHelper->currency($amount, true, false).' - '.__($col['feesname']);
                    $mandExtraFeeAmount = $mandExtraFeeAmount +$amount;
                    $mandatoryOrderExtraFeeIds[]=$col['id'];
                    $mandatoryOrderExtraFeeIdsStr=implode(',', $mandatoryOrderExtraFeeIds);
                    $metadata = $this->_cookieMetadataFactory->create()
                              ->setPath($this->sessionManager->getCookiePath())
                              ->setDomain($this->sessionManager->getCookieDomain());
                }
            }
            if (sizeof($feesname)>0) {
                $ExtraOrd = implode(' + ', $feesname);
                $this->_cookieManager->setPublicCookie('mandatoryOrderExtraFee', $ExtraOrd, $metadata);
                $this->_cookieManager->setPublicCookie('mandatoryOrderExtraFeeAmount', $mandExtraFeeAmount, $metadata);
                $this->_cookieManager->setPublicCookie('mandatoryOrderExtraFeeIdsStr', $mandatoryOrderExtraFeeIdsStr, $metadata);
            }
                       
        }
        return $orderFee;
    }

    /**
     * Get config value
     * @return string
     */
    public function getOrderFeeLabel()
    {
        $orderLabel=$this->scopeConfig->getValue(
            "mageants_extrafee/setting/order_title",
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if ($orderLabel=='') {
            $orderLabel="Order Extra Fee";
        }
        return $orderLabel;
    }

    /**
     * Get Extra fee
     * @return int
     */
    public function getExtrafee()
    {
        $fee=0;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $cart = $objectManager->get(\Magento\Checkout\Model\Session::class);
        $quoteId = $cart->getquoteId();
        $quote = $objectManager->get(\Magento\Quote\Model\QuoteFactory::class)->create()->load($quoteId);
        $items = $quote->getAllItems();
        $model = $objectManager->create(\Mageants\ExtraFee\Model\ExtraFee::class);
        foreach ($items as $item) {
            if ($item->getParentItemId() == null) {
                $options = $item->getProduct()->getTypeInstance(true)->getOrderOptions($item->getProduct());
                $price=0;
                $price=$item->getPrice();
                $qty = $item->getQty();
                if ($item->getProduct()->getVisibility()==4) {
                    if (!empty($options['info_buyRequest']['feesname'])) {
                        $feesId=$options['info_buyRequest']['feesname'];
                    } else {
                        $productId=$item->getProduct()->getId();
                        $product=$this->_productRepository->getById($productId);
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
                             $feesId=$ExtraFeeCollection->getData();
                        } elseif ($overrideCat=="No") {
                            $ExtraFeeCollection=$model->getCollection()
                                ->addFieldToSelect('id')
                                ->addFieldToFilter('estatus', 'Enabled')
                                ->addFieldToFilter('is_mandatory', 'Yes')
                                ->addFieldToFilter('apply_to', [
                                        ['finset'=> 'Category']
                                    ]);

                             $feesId=$ExtraFeeCollection->getData();
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
                             $feesId=array_merge($ExtraCatFee,$ExtraPrdFee);*/
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
                             $feesId=array_merge($ExtraCatFee, $ExtraPrdFee);
                        }
                    }
                    if (sizeof($feesId) > 0) {
                        foreach ($feesId as $fid) {
                            $data=$model->load($fid);
                            $info=$data->getData();
                            if ($info['type']=='Fixed') {
                                if ($info['apply_to'] == 'Product') {
                                    if ($this->getProductFeeEachQty()) {
                                        $fee=$fee+($qty*$info['amount']);
                                    } else {
                                        $fee=$fee+$info['amount'];
                                    }
                                } else {
                                    $fee=$fee+$info['amount'];
                                }
                            } else {
                                if ($info['apply_to'] == 'Product') {
                                    if ($this->getProductFeeEachQty()) {
                                        $fee=$fee+($qty*($price*$info['amount'])/100);
                                    } else {
                                        $fee=$fee+(($price*$info['amount'])/100);
                                        
                                    }
                                } else {
                                    $fee=$fee+(($price*$info['amount'])/100);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $fee;
    }

    /**
     * Get Extra fee label
     * @return string
     */
    public function getFeeLabel()
    {
        $feeslabel=__("Total Extra Fee");
        return $feeslabel;
    }
    public function getAllFeeLabels()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create(\Magento\Framework\Pricing\Helper\Data::class);
        $cart = $objectManager->get(\Magento\Checkout\Model\Cart::class);
        $subTotal = $cart->getQuote()->getSubtotal();
        $model = $objectManager->create(\Mageants\ExtraFee\Model\ExtraFee::class);
        $feesLabel = '';
        if ($cart->getItemsCount() != 0) {
            $storeId=$this->getStoreId();
            $storeIds=['0' =>'0','1'=>$storeId];
            $collection = $this->_collection->getCollection()
                ->addFieldToFilter('estatus', 'Enabled')
                ->addFieldToFilter('store_id', ['in'=>$storeIds]);
            if (count($collection) > 0) {
                $allItems = $cart->getQuote()->getAllVisibleItems();
                $feesLabel = '';
                foreach ($allItems as $items) {
                    if ($items->getProductType() != 'grouped') {
                        $options = $items->getProduct()->getTypeInstance(true)->getOrderOptions($items->getProduct());
                        $customOptions = $options['info_buyRequest'];
                        $extraprice=0;
                        $extraprice=$items->getPrice();
                        if (isset($customOptions['feesname'])) {
                            $feesname = $customOptions['feesname'];
                            $feesId = [];
                            foreach ($feesname as $fname) {
                                $feesId[] = $fname;
                            }
                            $feeCollection = $this->_collection->getCollection()
                                            ->addFieldToFilter('estatus', 'Enabled')
                                            ->addFieldToFilter('store_id', ['in'=>$storeIds])
                                            ->addFieldToFilter('id', ['in' => $feesId]);

                            foreach ($feeCollection->getData() as $col) {
                                if ($col['type']=="Fixed") {
                                    $amount=$col['amount'];
                                } else {
                                    $amount=($extraprice*$col['amount'])/100;
                                }
                                $price=$priceHelper->currency($amount, true, false);
                                if ($feesLabel == '') {
                                    $feesLabel = $price.' - '.$col['feesname'];
                                } else {
                                    $feesLabel = $feesLabel.' + '.$price.' - '.$col['feesname'];
                                }
                            }
                        } else {
                            $productId=$items->getProduct()->getId();
                            $product=$this->_productRepository->getById($productId);
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
                                $feesId=$ExtraFeeCollection->getData();
                            } elseif ($overrideCat=="No") {
                                $ExtraFeeCollection=$model->getCollection()
                                ->addFieldToSelect('id')
                                ->addFieldToFilter('estatus', 'Enabled')
                                ->addFieldToFilter('is_mandatory', 'Yes')
                                ->addFieldToFilter('apply_to', [
                                        ['finset'=> 'Category']
                                    ]);

                                $feesId=$ExtraFeeCollection->getData();
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

                                $ExtraCatFee=[];
                                $ExtraPrdFee=[];
                                
                                if (count($ExtraFeeCatCollection) > 0) {
                                    $data = $ExtraFeeCatIdCollection->getData();
                                    $result = [];
                                    foreach ($data as $datas) {
                                        $feeCatId = explode(",", $datas['category_ids']);
                                        $result=array_intersect($feeCatId, $categoryId);
                                        
                                        if (count($result) > 0) {
                                            $ExtraCatFee=$ExtraFeeCatCollection->getData();
                                        }
                                    }
                                }
                                if (count($ExtraFeePrdCollection) > 0) {
                                    $ExtraPrdFee=$ExtraFeePrdCollection->getData();
                                }
                                $feesId=array_merge($ExtraCatFee, $ExtraPrdFee);
                            }
                            if (count($feesId) > 0) {
                                foreach ($feesId as $fid) {
                                    $data=$model->load($fid);
                                    $info=$data->getData();
                                    if ($info['type']=='Fixed') {
                                        $amount=$info['amount'];
                                    } else {
                                        $amount=($extraprice*$info['amount'])/100;
                                    }
                                    $feeprice=$priceHelper->currency($amount, true, false);
                                    if ($feesLabel == '') {
                                        $feesLabel = $feeprice.' - '.$info['feesname'];
                                    } else {
                                        $feesLabel = $feesLabel.' + '.$feeprice.' - '.$info['feesname'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $feesLabel;
    }

    public function getCategoryFeeLabels()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create(\Magento\Framework\Pricing\Helper\Data::class);
        $cart = $objectManager->get(\Magento\Checkout\Model\Cart::class);
        $subTotal = $cart->getQuote()->getSubtotal();
        $model = $objectManager->create(\Mageants\ExtraFee\Model\ExtraFee::class);
        $feesLabel = ['catelable'=>'', 'prdid'=>''];
        if ($cart->getItemsCount() != 0) {
            $storeId=$this->getStoreId();
            $storeIds=['0' =>'0','1'=>$storeId];
            $collection = $this->_collection->getCollection()
                ->addFieldToFilter('estatus', 'Enabled')
                ->addFieldToFilter('store_id', ['in'=>$storeIds]);
            if (sizeof($collection) > 0) {
                $allItems = $cart->getItems();
                
                foreach ($allItems as $items) {
                    $options = $items->getProduct()->getTypeInstance(true)->getOrderOptions($items->getProduct());
                    $customOptions = $options['info_buyRequest'];
                    $extraprice=0;
                    $extraprice=$items->getPrice();
                    
                    if (isset($customOptions['feesname'])) {
                        $feesname = $customOptions['feesname'];
                        
                        $feesId = [];
                        foreach ($feesname as $fname) {
                            $feesId[] = $fname;
                        }
                        $feeCollection = $this->_collection->getCollection()
                                            ->addFieldToFilter('estatus', 'Enabled')
                                            ->addFieldToFilter('store_id', ['in'=>$storeIds])
                                            ->addFieldToFilter('id', ['in' => $feesId]);
                        $cateFeePrice = 0;
                        foreach ($feeCollection->getData() as $col) {
                            if ($col['apply_to']=="Category") {
                                if ($feesLabel['prdid'] == "") {
                                    $feesLabel['prdid'] = $items->getProduct()->getId();
                                } else {
                                    $feesLabel['prdid'] = $feesLabel['prdid'].",".$items->getProduct()->getId();
                                }
                                
                                if ($col['type']=="Fixed") {
                                    $amount=$col['amount'];
                                } else {
                                    $amount=($subTotal*$col['amount'])/100;
                                }
                                $price=$priceHelper->currency($amount, true, false);
                                if ($feesLabel['catelable'] == '') {
                                    $feesLabel['catelable'] = $col['feesname'].' : '.$price;
                                    $cateFeePrice = $price;
                                } else {
                                    if ($cateFeePrice == $price) {
                                        $feesLabel['catelable'] = $feesLabel['catelable'];
                                    } else {
                                        $feesLabel['catelable'] = $feesLabel['catelable'].','.$col['feesname'].' : '.$price;
                                        $cateFeePrice = $price;
                                    }
                                }
                            }
                        }
                    } else {
                        
                        $productId=$items->getProduct()->getId();
                        $product=$this->_productRepository->getById($productId);
                        $overrideCat=$product->getOverrideCat();
                        $cateFeePrice = 0;
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
                             $feesId=$ExtraFeeCollection->getData();
                        } elseif ($overrideCat=="No") {
                            $ExtraFeeCollection=$model->getCollection()
                                ->addFieldToSelect('id')
                                ->addFieldToFilter('estatus', 'Enabled')
                                ->addFieldToFilter('is_mandatory', 'Yes')
                                ->addFieldToFilter('apply_to', [
                                        ['finset'=> 'Category']
                                    ]);

                             $feesId=$ExtraFeeCollection->getData();
                        } else {
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
                            $ExtraCatFee=[];
                            
                            
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
                             $feesId=$ExtraCatFee;
                        }
                        if (sizeof($feesId) > 0) {
                            foreach ($feesId as $fid) {
                                $data=$model->load($fid);
                                $info=$data->getData();
                                if ($info['apply_to']=="Category") {
                                    if ($feesLabel['prdid'] == "") {
                                        $feesLabel['prdid'] = $productId;
                                    } else {
                                        $feesLabel['prdid'] = $feesLabel['prdid'].",".$items->getProduct()->getId();
                                    }

                                    if ($info['type']=='Fixed') {
                                        $amount=$info['amount'];
                                    } else {
                                        $amount=($extraprice*$info['amount'])/100;
                                    }
                                    $feeprice=$priceHelper->currency($amount, true, false);

                                    if ($feesLabel['catelable'] == '') {
                                        $feesLabel['catelable'] = $info['feesname'].' : '.$feeprice;
                                        $cateFeePrice = $feeprice;
                                    } else {
                                        if ($cateFeePrice == $feeprice) {
                                            $feesLabel['catelable'] = $feesLabel['catelable'];
                                        } else {
                                            $feesLabel['catelable'] = $feesLabel['catelable'].','.$info['feesname'].' : '.$feeprice;
                                            $cateFeePrice = $feeprice;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $feesLabel;
    }

    public function getProductFeeLabels()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $priceHelper = $objectManager->create(\Magento\Framework\Pricing\Helper\Data::class);
        $cart = $objectManager->get(\Magento\Checkout\Model\Cart::class);
        $subTotal = $cart->getQuote()->getSubtotal();
        
        $model = $objectManager->create(\Mageants\ExtraFee\Model\ExtraFee::class);
        $feesLabel = ['prdlable'=>'', 'prdid'=>''];
        if ($cart->getItemsCount() != 0) {
            $storeId=$this->getStoreId();
            $storeIds=['0' =>'0','1'=>$storeId];
            $collection = $this->_collection->getCollection()
                ->addFieldToFilter('estatus', 'Enabled')
                ->addFieldToFilter('store_id', ['in'=>$storeIds]);
            if (sizeof($collection) > 0) {
                $allItems = $cart->getItems();
                
                foreach ($allItems as $items) {
                    $options = $items->getProduct()->getTypeInstance(true)->getOrderOptions($items->getProduct());
                    $customOptions = $options['info_buyRequest'];
                    $extraprice=0;
                    $extraprice=$items->getPrice();
                    if (isset($customOptions['feesname'])) {
                        $feesname = $customOptions['feesname'];
                        
                        $feesId = [];
                        foreach ($feesname as $fname) {
                            $feesId[] = $fname;
                        }
                        $feeCollection = $this->_collection->getCollection()
                                            ->addFieldToFilter('estatus', 'Enabled')
                                            ->addFieldToFilter('store_id', ['in'=>$storeIds])
                                            ->addFieldToFilter('id', ['in' => $feesId]);
                        $prodFeePrice = 0;
                        foreach ($feeCollection->getData() as $col) {
                            if ($col['apply_to']=="Product") {
                                if ($feesLabel['prdid'] == "") {
                                    $feesLabel['prdid'] = $items->getProduct()->getId();
                                } else {
                                    $feesLabel['prdid'] = $feesLabel['prdid'].",".$items->getProduct()->getId();
                                }
                                
                                if ($col['type']=="Fixed") {
                                    $amount=$col['amount'];
                                } else {
                                    $amount=($subTotal*$col['amount'])/100;
                                    
                                }
                                $price=$priceHelper->currency($amount, true, false);
                                if ($feesLabel['prdlable'] == '') {
                                    $feesLabel['prdlable'] = $col['feesname'].' : '.$price;
                                    $prodFeePrice = $price;
                                } else {
                                    if ($prodFeePrice == $price) {
                                        $feesLabel['prdlable'] = $feesLabel['prdlable'];
                                    } else {
                                        $feesLabel['prdlable'] = $feesLabel['prdlable'].','.$col['feesname'].' : '.$price;
                                        $prodFeePrice = $price;
                                    }
                                }
                            }
                        }
                    } else {
                        $productId=$items->getProduct()->getId();
                        $product=$this->_productRepository->getById($productId);
                        $overrideCat=$product->getOverrideCat();
                        $prodFeePrice = 0;
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
                             $feesId=$ExtraFeeCollection->getData();
                        } elseif ($overrideCat=="No") {
                            $ExtraFeeCollection=$model->getCollection()
                                ->addFieldToSelect('id')
                                ->addFieldToFilter('estatus', 'Enabled')
                                ->addFieldToFilter('is_mandatory', 'Yes')
                                ->addFieldToFilter('apply_to', [
                                        ['finset'=> 'Category']
                                    ]);

                             $feesId=$ExtraFeeCollection->getData();
                        } else {
                            $ExtraFeeList=$product->getExtrafeelist();
                            $feeIds=explode(",", $ExtraFeeList);
                            
                            $ExtraFeePrdCollection=$model->getCollection()
                                ->addFieldToSelect('id')
                                ->addFieldToFilter('estatus', 'Enabled')
                                ->addFieldToFilter('is_mandatory', 'Yes')
                                ->addFieldToFilter('apply_to', 'Product')
                                ->addFieldToFilter('id', ['in' => $feeIds]);

                            $ExtraPrdFee=[];
                            
                             
                            if (sizeof($ExtraFeePrdCollection) > 0) {
                                $ExtraPrdFee=$ExtraFeePrdCollection->getData();
                            }
                            $feesId=$ExtraPrdFee;
                        }
                        if (sizeof($feesId) > 0) {
                            foreach ($feesId as $fid) {
                                $data=$model->load($fid);
                                $info=$data->getData();
                                if ($info['apply_to']=="Product") {
                                    if ($feesLabel['prdid'] == "") {
                                        $feesLabel['prdid'] = $items->getProduct()->getId();
                                    } else {
                                        $feesLabel['prdid'] = $feesLabel['prdid'].",".$items->getProduct()->getId();
                                    }
                                    
                                    if ($info['type']=='Fixed') {
                                        $amount=$info['amount'];
                                    } else {
                                        $amount=($extraprice*$info['amount'])/100;
                                        
                                    }
                                    $feeprice=$priceHelper->currency($amount, true, false);
                                    if ($feesLabel['prdlable'] == '') {
                                        $feesLabel['prdlable'] = $info['feesname'].' : '.$feeprice;
                                        $prodFeePrice = $feeprice;
                                    } else {
                                        if ($prodFeePrice == $feeprice) {
                                            $feesLabel['prdlable'] = $feesLabel['prdlable'];
                                        } else {
                                            $feesLabel['prdlable'] = $feesLabel['prdlable'].','.$info['feesname'].' : '.$feeprice;
                                            $prodFeePrice = $feeprice;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $feesLabel;
    }

    public function getOrderFeeIds()
    {
        $feeLabels = '';
        $orderExtrafeeId = $this->_cookieManager->getCookie('orderExtrafeeId');
        $mandatoryOrderExtraFeeIdsStr = $this->_cookieManager->getCookie('mandatoryOrderExtraFeeIdsStr');
        $mandatoryOrderExtraFeeIds=explode(',', $mandatoryOrderExtraFeeIdsStr);
        $mandatoryOrderExtraFeeIds[]=$orderExtrafeeId;
        $orderExtraFeeIdsFinal = implode(',', $mandatoryOrderExtraFeeIds) ;
        return $orderExtraFeeIdsFinal;
    }
}
