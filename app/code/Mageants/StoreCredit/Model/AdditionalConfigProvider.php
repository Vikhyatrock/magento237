<?php
/**
 * @category Mageants StoreCredit
 * @package Mageants_StoreCredit
 * @copyright Copyright (c) 2020 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\StoreCredit\Model;

/**
 * AdditionalConfigProvider class for fetch store credit configuration
 */
class AdditionalConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Magento\Framework\Pricing\Helper\Data $currency
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Mageants\StoreCredit\Model\StoreCreditFactory $storecreditfactory,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Framework\Pricing\Helper\Data $currency
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_storeManager = $storeManager;
        $this->_storecreditfactory = $storecreditfactory;
        $this->_customerSession = $customerSession;
        $this->currency = $currency;
        $this->_checkoutSession=$checkoutSession;
    }

    public function getStockcreditbalance()
    {
        $id = $this->_customerSession->getCustomerId();
        $credit_balance = '0';
        $collection = $this->_storecreditfactory->create()->getCollection()
                   ->addFieldToSelect('new_balance')
                   ->addFieldToFilter('customer_id', $id)
                   ->setOrder(
                       'id',
                       'DESC'
                   )
                    ->setPageSize(1)
                    ->getLastItem();
        $amount_data = $collection->getData();
        if (!empty($amount_data['new_balance'])) {
            $credit_balance = $amount_data['new_balance'];
        }
        return $credit_balance;
    }
    
    public function getConfig()
    {
        $output['store_credit_balance'] = $this->getStockcreditbalance();
            
        $output['store_config_enable'] = $this->_scopeConfig->getValue('store_credit/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        
        $output['customer_id'] = $this->_customerSession->getCustomerId();
        
        return $output;
    }
}
