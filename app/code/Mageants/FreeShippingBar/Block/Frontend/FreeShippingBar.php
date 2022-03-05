<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Block\Frontend;

use Magento\Framework\View\Element\Template\Context;
use Mageants\FreeShippingBar\Model\FreeShippingBarFactory;
use Magento\Checkout\Model\Cart;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Cache\Frontend\Pool;

class FreeShippingBar extends \Magento\Framework\View\Element\Template
{
    protected $httpContext;
    public function __construct(
        Context $context,
        \Magento\Framework\App\Http\Context $httpContext,
        FreeShippingBarFactory $freeshippingbarfactory,
        \Magento\Directory\Model\Currency $currency,
        \Mageants\FreeShippingBar\Observer\Cart $cart,
        Filesystem $filesystem,
        Cart $checkoutSession,
        \Magento\Framework\UrlInterface $urlInterface,
        \Magento\Framework\App\Request\Http $http,
        \Magento\Framework\Stdlib\DateTime\Timezone $datetime,
        \Magento\Framework\Session\SessionManagerInterface $coresession,
        \Magento\Customer\Model\Session $customerSession,
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool,
        array $data = []
    ) {
        $this->currency = $currency;
        $this->cart = $cart;
        $this->_http = $http;
        $this->_datetime = $datetime;
        $this->httpContext = $httpContext;
        $this->storemanager = $context->getStoreManager();
        $this->_checkoutSession = $checkoutSession;
        $this->_customerSession = $customerSession;
        $this->freeshippingbarfactory = $freeshippingbarfactory;
        $this->_filesystem = $filesystem;
        $this->_urlInterface = $urlInterface;
        $this->_coresession = $coresession;
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
        parent::__construct($context, $data);
    }

    public function getCustomerIsLoggedIn()
    {
        return (bool)$this->httpContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
    }

    public function getSession()
    {
        return $this->_coresession;
    }
    public function getCollections()
    {
        $types = ['config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice'];
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }

        $storeId = $this->freeshippingbarfactory->create()->getCollection()->addFieldToFilter('status', 1)->
        addFieldToSelect('storeview')->setOrder('position', 'ASC')->load()->getData();
        $incremeter = 0;
        $record = [];
        if ($this->getCustomerIsLoggedIn()) {
            $customerGroupId = $this->httpContext->getValue('customer_group_id');
            // $customerGroupId = $this->_customerSession->getCustomer()->getGroupId();
            // echo $customerGroupId;
            // exit();
        } else {
            $customerGroupId =  0 ;
        }
        while ($incremeter < count($storeId)) {
            if ($storeId[$incremeter]['storeview'] == 0) {
                $record = $incremeter;
            }
            $incremeter++;
        }
        if (!empty($storeId)) {
            if (!empty($record) || $record == 0) {
                if ($storeId[0]['storeview'] == 0) {
                    // echo "Hello 1";
                    // exit;
                    $collection = $this->freeshippingbarfactory->create()->getCollection()->
                    addFieldToFilter('status', 1)->addFieldToFilter('storeview', ['in'=> [0,$this->getStoreId()]])->
                    addFieldToFilter('customer_group', [
                        ['like' => '%,'.$customerGroupId.',%'],
                        ['like' => '%,'.$customerGroupId],
                        ['like' => $customerGroupId.',%'],
                        ['like' => $customerGroupId]])->setOrder('position', 'ASC')->load();
                    return $collection;
                    // var_dump($collection->getData());
                    // exit;
                } else {
                    // echo "Hello 2";
                    // exit;
                    $collection = $this->freeshippingbarfactory->create()->getCollection()->
                    addFieldToFilter('status', 1)->addFieldToFilter('storeview', ['in'=> [0,$this->getStoreId()]])->
                    addFieldToFilter('customer_group', [
                        ['like' => '%,'.$customerGroupId.',%'],
                        ['like' => '%,'.$customerGroupId],
                        ['like' => $customerGroupId.',%'],
                        ['like' => $customerGroupId]])->setOrder('position', 'ASC')->load();
                    return $collection;
                }
            } else {
            // echo "Hello 3";
            // exit;
                $collection = $this->freeshippingbarfactory->create()->getCollection()->addFieldToFilter('status', 1)->
                addFieldToFilter('storeview', $this->getStoreId())->addFieldToFilter('customer_group', [
                        ['like' => '%,'.$customerGroupId.',%'],
                        ['like' => '%,'.$customerGroupId],
                        ['like' => $customerGroupId.',%'],
                        ['like' => $customerGroupId]])->setOrder('position', 'ASC')->load();
                return $collection;
            }
            // var_dump(get_class_methods($collection));
            // exit;
            // var_dump($collection->getData());
            // return $collection;
        } else {
            $collection = $this->freeshippingbarfactory->create()->getCollection()->addFieldToFilter('status', 1)->
            load();
            // var_dump(get_class_methods($collection));
            // exit;
            // echo $collection;
            return $collection;
        }
    }
    public function getCurrencySymbol()
    {
        return $this->currency->getCurrencySymbol();
    }
    public function getCartProductPrice()
    {

        // $quote = $this->_checkoutSession->getQuote();
        $quote = $this->httpContext->getValue('quote');
        $cartTotal = (int)$this->cart->getTotalCartPrice();
        if (empty($cartTotal)) {
            $allItems = $quote->getAllItems();
            foreach ($allItems as $value) {
                $cartTotal += $value->getCalculationPrice() * $value->getQty();
            }
        }
        return $cartTotal;
    }
    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
    public function getActionName()
    {
        return $this->_http->getFullActionName();
    }
    public function getFullUrl()
    {
        return $this->_urlInterface->getCurrentUrl();
    }
    public function getStoreId()
    {
        return $this->storemanager->getStore()->getId();
    }
    public function getCurrentDateTime()
    {
        $date = $this->_datetime->date();
        return $date->format('Y-m-d H:i:s');
    }
    public function setStatus($id)
    {
        $Model = $this->freeshippingbarfactory->create();
        $data = $Model->load($id)->getData();
        $data['status'] = 0;
        $Model->setData($data);
        $Model->save();
    }
    public function cleanCache()
    {
        $types = ['config','layout','block_html','collections','reflection','db_ddl','eav','config_integration','config_integration_api','full_page','translate','config_webservice'];
        foreach ($types as $type) {
            $this->_cacheTypeList->cleanType($type);
        }
        foreach ($this->_cacheFrontendPool as $cacheFrontend) {
            $cacheFrontend->getBackend()->clean();
        }
    }
    public function freeshippingbarCode(
        $id,
        $goal,
        $firstMessage,
        $belowGoalMessage,
        $achiveGoalMessage,
        $cartPrice,
        $goaltextColor,
        $currencySymbol,
        $barBackgroundColor,
        $barTextColor,
        $barBackgroundOpacity,
        $barLinkColor,
        $goalTextColor,
        $fontSize,
        $fonts,
        $getClickable,
        $linkUrl,
        $openInNewPage,
        $getImage,
        $position,
        $products
    ) {
        $scope = "'shippingbar".$id."'";
        $html = '<div class="freeshippingbar'.$id.'">
            <a class="link">
            <div id="control-bar" data-bind="scope:'.$scope.'">
                <!-- ko template: getTemplate() --><!-- /ko -->
                <script type="text/x-magento-init">
                {
                    "#control-bar": {
                        "Magento_Ui/js/core/app": {
                           "components": {
                                "shippingbar'.$id.'": {
                                    "component": "Mageants_FreeShippingBar/js/shippingbar",
                                    "config": {
                                      "rule_id": "'.$id.'",
                                      "goal": "'.$goal.'",
                                      "first_message": "'.$firstMessage.'",
                                      "below_goal_message": "'.$belowGoalMessage.'",
                                      "achieve_message": "'.$achiveGoalMessage.'",
                                      "cartTotal": "'.$cartPrice.'",
                                      "goalcolor" : "'.$goaltextColor.'",
                                      "currency" : "'.$currencySymbol.'",
                                      "products" : "'.$products.'"
                                    }
                                }
                            }
                        }
                    }
                }
                </script>
            </div>
          </a>
        </div>
        <script type="text/javascript">
        (function  () { 
            require(["jquery" , "freeshippingbar"],function($) {
                $(document).ready(function() {
                  var classid = "'.$id.'";
                  var backcolor =  "'.$barBackgroundColor.'";
                  var pagetextcolor =  "'.$barTextColor.'";
                  var opacity =  "'.$barBackgroundOpacity.'";
                  var linkcolor =  "'.$barLinkColor.'";
                  var goaltextcolor =  "'.$goalTextColor.'";
                  var fontsize =  "'.$fontSize.'";
                  var fonts =  "'.$fonts.'";';
        if ($getClickable) {
            $html .= 'var link =  "'.$linkUrl.'";
                  var openinnew =  "'.$openInNewPage.'";';
        } else {
            $html .= 'var link =  "";
                  var openinnew =  "";';
        }
        if (!empty($getImage)) {
            $html .= 'var image =  "'.$this->getMediaUrl().$getImage.'";';
        } else {
            $html .='var image =  "";';
        }
        $html .='var position =  "'.$position.'";
                  setBarValues(backcolor,classid,pagetextcolor,opacity,linkcolor,goaltextcolor,fontsize,fonts,link,
                  openinnew,image,position);
                });
            });
        })();
        </script>';
        return $html;
    }
}
