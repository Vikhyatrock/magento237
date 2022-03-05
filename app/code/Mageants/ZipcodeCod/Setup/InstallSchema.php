<?php
namespace Mageants\ZipcodeCod\Setup;

/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */



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
    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $Context = $context;
        $installer = $setup;

        $installer->startSetup();

        if (!$installer->tableExists('mageants_zipcodecod')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('mageants_zipcodecod'))
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    10,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true]
                )
                ->addColumn('zipcode', Table::TYPE_TEXT, 255, ['nullable' => false])
                ->addColumn('city', Table::TYPE_TEXT, 255, ['nullable' => false])
                ->addColumn('estimated_delivery_time', Table::TYPE_TEXT, 255, ['nullable' => false])
                ->addColumn('is_cod_available', Table::TYPE_TEXT, 255, ['nullable' => false])
                ->addIndex(
                    $setup->getIdxName(
                        $setup->getTable('mageants_zipcodecod'),
                        ['zipcode','city'],
                        \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                    ),
                    ['zipcode','city'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
                )
                ->setComment('ZipcodeCod table');

            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
