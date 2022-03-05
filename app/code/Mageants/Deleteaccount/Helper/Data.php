<?php
/**
 * @category Mageants DeleteAccount
 * @package Mageants_DeleteAccount
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\Deleteaccount\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Get Store code
     *
     * @return string
     */
    public function getStoreCode()
    {
        return $this->_storeManager->getStore()->getCode();
    }

    /**
     * @return bool|string
     */
    public function getConfig($config_path)
    {
        $storeCode=$this->getStoreCode();
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
                );
    }

    /**
     * @return bool
     */
    public function isEnableDeleteAccount()
    {
        return $this->getConfig('deleteaccount/general/enable');
    }
}
