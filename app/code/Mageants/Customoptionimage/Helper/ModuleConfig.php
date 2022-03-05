<?php
namespace Mageants\Customoptionimage\Helper;

use Magento\Catalog\Model\Product\Option;

class ModuleConfig extends \Magento\Framework\App\Helper\AbstractHelper
{
    public $scopeConfig;

    public $storeManager;

    public $storeId;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }
    public function isModuleEnable()
    {
        return $this->scopeConfig->getValue(
            'customoption/Customoptionimage/Enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
    }
    public function getBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl();
    }
    public function getCheckboxSizeX()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Checkbox_x',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 50 : (int)$size;
    }
    public function getCheckboxSizeY()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Checkbox_y',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 50 : (int)$size;
    }
    public function getRadioSizeX()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Radio_x',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 50 : (int)$size;
    }
    public function getRadioSizeY()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Radio_y',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 50 : (int)$size;
    }
    public function getMultipleSizeX()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Multiple_x',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 40 : (int)$size;
    }
    public function getMultipleSizeY()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Multiple_y',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 40 : (int)$size;
    }
    public function getDropdownSizeX()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Dropdown_x',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 60 : (int)$size;
    }
    public function getDropdownSizeY()
    {
        $size = $this->scopeConfig->getValue(
            'customoption/image_size/Dropdown_y',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($size === null) ? 60 : (int)$size;
    }
    public function getDropdownView()
    {
        $config = $this->scopeConfig->getValue(
            'customoption/frontend_view/Dropdown',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($config === null) ? 0 : (int)$config;
    }
    public function getMultipleSelectView()
    {
        $config = $this->scopeConfig->getValue(
            'customoption/frontend_view/Multiple',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($config === null) ? 0 : (int)$config;
    }
    public function getRadiobuttonView()
    {
        $config = $this->scopeConfig->getValue(
            'customoption/frontend_view/Radiobutton',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($config === null) ? 0 : (int)$config;
    }
    public function getCheckboxView()
    {
        $config = $this->scopeConfig->getValue(
            'customoption/frontend_view/Checkbox',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId()
        );
        return ($config === null) ? 0 : (int)$config;
    }

    public function getImageY($type)
    {
        switch ($type) {
            case Option::OPTION_TYPE_DROP_DOWN:
                return $this->getDropdownSizeY();
            
            case Option::OPTION_TYPE_MULTIPLE:
                return $this->getMultipleSizeY();
            
            case Option::OPTION_TYPE_RADIO:
                return $this->getRadioSizeY();
            
            case Option::OPTION_TYPE_CHECKBOX:
                return $this->getCheckboxSizeY();
        }
    }

    public function getImageX($type)
    {
        switch ($type) {
            case Option::OPTION_TYPE_DROP_DOWN:
                return $this->getDropdownSizeX();

            case Option::OPTION_TYPE_MULTIPLE:
                return $this->getMultipleSizeX();
            
            case Option::OPTION_TYPE_RADIO:
                return $this->getRadioSizeX();
            
            case Option::OPTION_TYPE_CHECKBOX:
                return $this->getCheckboxSizeX();
        }
    }

    public function getStoreId()
    {
        if ($this->storeId === null) {
            $this->storeId = $this->storeManager->getStore()->getId();
        }
        return $this->storeId;
    }

}
