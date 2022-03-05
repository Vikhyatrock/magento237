<?php
namespace Mageants\Customoptionimage\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        if (version_compare($context->getVersion(), '1.0.5', '<')) {
            $this->recreateImageTable($setup);
        }
    }
    public function recreateImageTable($setup)
    {
        $setup->startSetup();
        
        $mageantsImageTable = $setup->getTable('mageants_catalog_product_option_type_image');
        $connection = $setup->getConnection();
        if ($connection->isTableExists($mageantsImageTable)) {
            $connection->dropIndex(
                $mageantsImageTable,
                'PRIMARY'
            );
            $connection->dropIndex(
                $mageantsImageTable,
                'INDEX'
            );
            $connection->dropColumn(
                $mageantsImageTable,
                'option_type_image_id'
            );
            $connection->modifyColumn(
                $mageantsImageTable,
                'option_type_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'unsigned' => true,
                    'comment' => 'Option type ID'
                ]
            );
            $connection->addColumn(
                $mageantsImageTable,
                'image_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    'length' => 10,
                    'auto_increment' => true,
                    'primary' => true,
                    'index' => true,
                    'nullable' => false,
                    'comment' => 'image_id'
                ]
            );
            $connection->addForeignKey(
                $connection->getForeignKeyName(
                    $mageantsImageTable,
                    'option_type_id',
                    $setup->getTable('catalog_product_option_type_value'),
                    'option_type_id'
                ),
                $mageantsImageTable,
                'option_type_id',
                $setup->getTable('catalog_product_option_type_value'),
                'option_type_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            );
        }
        $setup->endSetup();
    }
}
