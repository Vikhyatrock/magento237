<?php
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Ui\Component\Listing\Column;
 

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use \Magento\Store\Model\StoreRepository;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Payment\Model\Config;

/**
 * Shows customer group name in admin grids instead of group id
 */
class Payment extends Column
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
     * Constructor
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param array $components
     * @param \Magento\Customer\Model\Group $customerGroup
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Config $paymentModelConfig,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_paymentModelConfig=$paymentModelConfig;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }
 
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) { 
                $name=str_replace('0,','',$item[$this->getData('name')]);
                $paycodeArray=explode(",",$name);
                $sizeofarray=sizeof($paycodeArray);
                $paymentTitle="";
                $step=1;
                if($sizeofarray>0){
                    foreach ($paycodeArray as $code) {
                        
                        if($step==$sizeofarray)
                        {
                            $paymentTitle = $paymentTitle.$this->_scopeConfig->getValue('payment/'.$code.'/title');
                        }else{
                        $paymentTitle = $paymentTitle.$this->_scopeConfig->getValue('payment/'.$code.'/title').", ";}
                        $step=$step+1;
                    }
                }
                if($name=="0" && empty($item[$this->getData('name')])==1)
                {
                    $paymentTitle = "Default Apply";    
                }
                $item[$this->getData('name')] = $paymentTitle;
            }
        }
 
        return $dataSource;
    }
}