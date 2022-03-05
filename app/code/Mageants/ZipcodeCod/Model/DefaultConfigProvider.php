<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Checkout\Model\Session;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class DefaultConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public $scopeConfig;

    /**
     * @var  \Mageants\ZipcodeCod\Helper\Data
     */
    public $zipCodeHelper;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Mageants\ZipcodeCod\Helper\Data $zipCodeHelper
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Mageants\ZipcodeCod\Helper\Data $zipCodeHelper,
        Session $checkoutSession
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        $this->zipCodeHelper = $zipCodeHelper;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        $output['cusDefaultPostcode'] = $this->checkoutSession->getCheckoutZipCode();
        $output['zipcodeStatus']  = $this->zipCodeHelper->isZipcodeEnable();
        return $output;
    }
}
