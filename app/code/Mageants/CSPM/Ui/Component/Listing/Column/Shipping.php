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
/**
 * Shows customer group name in admin grids instead of group id
 */
class Shipping extends Column
{
    /**
     * customer Group
     *
     * @var \Magento\Customer\Model\Group
     */
    protected $shippingConfig;
    
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
        \Magento\Shipping\Model\Config $shippingConfig,
        array $data = []
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->shippingConfig=$shippingConfig;
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
        $carriers = $this->shippingConfig->getActiveCarriers();
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) { 
                $name=str_replace('0,', '',$item[$this->getData('name')]);
                $carrcodeArray=explode(",",$name);
                $sizeofarray=sizeof($carrcodeArray);
                $carrierTitle="";
                $step=1;
                if($sizeofarray>0){
                
                    foreach ($carrcodeArray as $code) {
                        
                        if($step==$sizeofarray)
                        {
                            $carrierTitle = $carrierTitle.$this->_scopeConfig->getValue(
                                'carriers/' . $code . '/title',
                                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                            );
                        }else{
                        $carrierTitle = $carrierTitle.$this->_scopeConfig->getValue(
                            'carriers/' . $code . '/title',
                            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                        ).", ";}
                        $step=$step+1;
                    }
                }
                if($name=="0" && empty($item[$this->getData('name')])==1)
                {
                    $carrierTitle = "Default Apply";    
                }
                
                $item[$this->getData('name')] = $carrierTitle;
            }
        }
 
        return $dataSource;
    }
}