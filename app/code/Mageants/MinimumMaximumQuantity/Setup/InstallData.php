<?php

namespace Mageants\MinimumMaximumQuantity\Setup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Model\Config;

class InstallData implements InstallDataInterface
{
   
    private $eavSetupFactory;

    
    public function __construct(
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
       
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'minimum_quantity',
            [
                'group' => 'Limit Quantity For Grouped Products',
                'type' => 'int',
                'backend' => '\Mageants\MinimumMaximumQuantity\Model\Attribute\Backend\Validation',
                'frontend' => '',
                'label' => 'Minimum Quantity',
                'input' => 'text',
                'frontend_class' => 'validate-not-negative-number',
                'note' => 'Minimum Quantity',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => 0,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'grouped'
            ]
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'maximum_quantity',
            [
                'group' => 'Limit Quantity For Grouped Products',
                'type' => 'int',
                'backend' => '\Mageants\MinimumMaximumQuantity\Model\Attribute\Backend\Validation',
                'frontend' => '',
                'label' => 'Maximum Quantity',
                'input' => 'text',
                'frontend_class' => 'validate-not-negative-number',
                'note' => 'Maximum Quantity',
                'class' => '',
                'source' => '',
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => 0,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => true,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => 'grouped'
            ]
        );
      
    }
}
  


  


  
   