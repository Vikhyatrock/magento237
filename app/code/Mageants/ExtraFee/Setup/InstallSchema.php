<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('mageants_extrafee')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageants_extrafee'))
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true]
                )
                ->addColumn('feesname', Table::TYPE_TEXT, 250, ['nullable' => false])
                ->addColumn('type', Table::TYPE_TEXT, 10, ['nullable' => false])
                ->addColumn('amount', Table::TYPE_DECIMAL, '10,2', ['nullable' => false])
                ->addColumn('apply_to', Table::TYPE_TEXT, 25, ['nullable' => true])
                ->addColumn('category_ids', Table::TYPE_TEXT, 255, ['nullable' => true])
                ->addColumn('is_mandatory', Table::TYPE_TEXT, 25, ['nullable' => true])
                ->addColumn('estatus', Table::TYPE_TEXT, 25, ['nullable' => true])
                   ->addColumn('store_id', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' =>'0'])
                ->addIndex(
                    $setup->getIdxName(
                        $setup->getTable('mageants_extrafee'),
                        ['feesname','type','apply_to','is_mandatory','estatus','store_id'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['feesname','type','apply_to','is_mandatory','estatus','store_id'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
                )
                ->setComment('ExtraFee table');

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
