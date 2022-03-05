<?php
/**
 * @category Mageants FastOrder
 * @package Mageants_FastOrder
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FastOrder\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Catalog\Api\ProductRepositoryInterface;

class Add extends \Magento\Checkout\Controller\Cart\Add
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;
    
    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;
    
    /**
     * @var \Magento\Framework\Data\Form\FormKey
     */
    protected $formKey;

    protected $storeManager;

    protected $registry;

    protected $escaper;

    protected $helper;

    protected $resolverInterface;

    public function __construct(
        Context $context,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Framework\Data\Form\FormKey $formKey,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Escaper $escaper,
        \Mageants\FastOrder\Helper\Data $helper,
        \Magento\Framework\Locale\ResolverInterface $resolverInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart,
            $productRepository
        );
        $this->productFactory = $productFactory;
        $this->cart = $cart;
        $this->formKey = $formKey;
        $this->storeManager = $storeManager;
        $this->registry = $registry;
        $this->escaper = $escaper;
        $this->helper = $helper;
        $this->resolverInterface = $resolverInterface;
    }

    public function execute()
    {
        $productIds = $this->getRequest()->getParam('productIds');
        $qtys = $this->getRequest()->getParam('qtys');
        $fastorderSuperAttribute = $this->getRequest()->getParam('mgantsfastorder-super_attribute');
        $fastorderLinks = $this->getRequest()->getParam('mgantsfastorder_links');
        $fastorderSuperGroup = $this->getRequest()->getParam('mageants-fastorder-super_group');
        $fastorderCustomOption = $this->getRequest()->getParam('mgantsfastorder-options');

        $result = [];
        $success = false;
        try {
            $storeId = $this->storeManager->getStore()->getId();
            $productNames = [];
            $i=0;
            
            foreach ($productIds as $key => $productId) {
                if ($qtys[$key] <= 0 || !$productId) {
                    continue;
                }
                $params = [];
                $this->registry->unregister('row_product');
                $this->registry->register('row_product', $key);
                $product = $this->productFactory->create()->setStoreId($storeId)->load($productId);
                
                $params = $this->addOptionProduct($params, $product, $fastorderSuperAttribute, $fastorderLinks, $fastorderSuperGroup, $key);
                // add custom option

                $hasoptions = $product->getOptions();

                if ($hasoptions) {
                    if (!empty($fastorderCustomOption)) {
                        $params['options'] = $this->addCustomOption($fastorderCustomOption, $key);
                    }
                }
                if (isset($qtys[$key])) {
                    $filter = new \Zend_Filter_LocalizedToNormalized(
                        ['locale' => $this->resolverInterface->getLocale()]
                    );
                    $params['qty'] = $filter->filter($qtys[$key]);
                }
                $productNames[] = '"' . $product->getName() . '"';
                $this->cart->addProduct($product, $params);
                $success = true;
                $i++;
            }

            if ($success) {
                $this->cart->save();
                $result['status'] = true;
                $message = __(
                    'You added %1 to your shopping cart.',
                    join(', ', $productNames)
                );
                $this->messageManager->addSuccessMessage($message);
            } else {
                $result['status'] = false;
                $this->messageManager->addError(
                    __('Please insert product(s).')
                );
            }
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            if ($this->_checkoutSession->getUseNotice(true)) {
                $this->messageManager->addNotice(
                    $this->escaper->escapeHtml($e->getMessage())
                );
                $result['status'] = false;
                $result['row'] = $this->registry->registry('row_product');
            } else {
                $messages = array_unique(explode("\n", $e->getMessage()));
                foreach ($messages as $message) {
                    $this->messageManager->addError(
                        $this->escaper->escapeHtml($message)
                    );
                }
                $result['status'] = false;
                $result['row'] = $this->registry->registry('row_product');
            }
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('We can\'t add this item to your shopping cart right now.'));
            $result['status'] = false;
            $result['row'] = $this->registry->registry('row_product');
        }
        $respon = json_encode($result);
        $this->getResponse()->setBody(($respon));
        return;
    }

    protected function addOptionProduct($params, $product = null, $fastorderSuperAttribute = null, $fastorderLinks = null, $fastorderSuperGroup = null, $key = null)
    {
        if ($product->getTypeId() == 'configurable' && !empty($fastorderSuperAttribute)) {
            $params['super_attribute'] = $fastorderSuperAttribute[$key];
        } elseif ($product->getTypeId() == 'downloadable' && !empty($fastorderLinks)) {
            $params['links'] = $fastorderLinks[$key];
        } elseif ($product->getTypeId() == 'grouped' && !empty($fastorderSuperGroup)) {
            $params['super_group'] = $fastorderSuperGroup[$key];
        }
        if (!empty($params)) {
            return $params;
        }
        return false;
    }

    protected function addCustomOption($fastorderCustomOption = null, $key = null)
    {
        if (isset($fastorderCustomOption[$key])) {
            foreach ($fastorderCustomOption[$key] as $id => $value) {
                if (is_array($value)) {
                    continue;
                }
                $valueArr = explode(',', $value);
                if ($valueArr && count($valueArr) > 1) {
                    $newValue = rtrim($value, ',');
                    $fastorderCustomOption[$key][$id] = explode(',', $newValue);
                };
            };
            return $fastorderCustomOption[$key];
        }
        return false;
    }
}
