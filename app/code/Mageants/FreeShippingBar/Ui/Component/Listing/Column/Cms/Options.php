<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Ui\Component\Listing\Column\Cms;

use Magento\Store\Ui\Component\Listing\Column\Store\Options as StoreOptions;

class Options extends StoreOptions
{
    const ALL_STORE_VIEWS = '0';

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\System\Store $systemStore
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_systemStore = $systemStore;
    }

    /* public function toOptionArray()
     {
         if ($this->options !== null) {
             return $this->options;
         }
         $this->currentOptions['All Store Views']['label'] = __('All Store Views');
         $this->currentOptions['All Store Views']['value'] = self::ALL_STORE_VIEWS;
         $this->generateCurrentOptions();
         $this->options = array_values($this->currentOptions);
         return $this->options;
     }*/
    public function toOptionArray($isActiveOnlyFlag = false)
    {
        $storeId=$this->_systemStore->getStoreValuesForForm(false, true);
        return $storeId;
    }
}
