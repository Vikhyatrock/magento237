<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Model\Config\Source;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Payment\Model\Config;
/**
 * return all payment method 
 */
class PaymentMethod implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * App configuration scope Interface 
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_appConfigScopeConfigInterface;

    /**
     * Payment Model confif
     *
     * @var \Magento\Payment\Model\Config
     */
    protected $_paymentModelConfig;

    /**
     * @param ScopeConfigInterface $appConfigScopeConfigInterface
     * @param Config $paymentModelConfig
     */
    public function __construct(
        ScopeConfigInterface $appConfigScopeConfigInterface,
        Config $paymentModelConfig)
    {
        $this->_appConfigScopeConfigInterface = $appConfigScopeConfigInterface;
        $this->_paymentModelConfig = $paymentModelConfig;
    }

    /**
     * return payment method array
     */
    public function toOptionArray()
    {
        $payments = $this->_paymentModelConfig->getActiveMethods();
        $methods = array();
        $methods["0"] = array('label' => "Default Apply",'value' => "0");
        foreach ($payments as $paymentCode=>$paymentModel)
        {
        $paymentTitle = $this->_appConfigScopeConfigInterface->getValue('payment/'.$paymentCode.'/title');
        $methods[$paymentCode] = array(
        'label' => $paymentTitle,
        'value' => $paymentCode
        );
        }
        return $methods;
    }
}

