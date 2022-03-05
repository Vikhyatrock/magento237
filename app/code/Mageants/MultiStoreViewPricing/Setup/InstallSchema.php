<?php
/**
 * @category Mageants MultiStoreViewPricing
 * @package Mageants_MultiStoreViewPricing
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\MultiStoreViewPricing\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * InstallSchema for Update Database for MultiStoreViewPricing
 */
class InstallSchema implements InstallSchemaInterface
{
    
    /**
     *
     * store manager
     *
     * @var Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;

     /**
      *
      *
      *
      * @param Magento\Store\Model\StoreManagerInterface
      */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager=$storeManager;
    }

    /**
     * install Database for GiftCertificate
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('multi_store_view_pricing')
        )
         ->addColumn(
             'id',
             \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
             null,
             ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
             'id'
         )->addColumn(
             'entity_id',
             \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
             10,
             ['nullable' => false],
             'entity_id'
         )->addColumn(
             'store_id',
             \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
             null,
             ['nullable' => false],
             'store_id'
         )->addColumn(
             'price',
             \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
             '12,4',
             ['nullable' => false, 'default' => '0.0000'],
             'Price'
         )->setComment(
             'Mageants_MultiStoreViewPricing'
         );
        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }
}
