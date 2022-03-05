<?php
namespace Mageants\Customoptionimage\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();
        if (!$installer->tableExists('mageants_catalog_product_option_type_image')) {
            $table = $installer->getConnection()
                ->newTable(
                    $installer->getTable('mageants_catalog_product_option_type_image')
                )
                ->addColumn(
                    'option_type_image_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Option Type Image ID'
                )
                ->addColumn(
                    'option_type_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['primary' => true, 'unsigned' => true, 'nullable' => false],
                    'Option Type ID'
                )
                ->addColumn(
                    'image_url',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    255,
                    ['nullable' => true, 'default' => null],
                    'Url'
                )->addIndex(
                    $installer->getIdxName('mageants_catalog_product_option_type_image', ['option_type_id']),
                    ['option_type_id']
                )
                ->setComment(
                    'Catalog Product Option Type Image Url'
                );
                $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}
