<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();

        
            $connection->addColumn(
                $installer->getTable('cataloginventory_stock_item'),
                'backstock_preorders',
                [
                   'type' => Table::TYPE_INTEGER,
                   'length' => 5,
                   'nullable' => true,
                   'comment' => 'Backstock PreOrders'
                ]
            );

            $connection->addColumn(
                $installer->getTable('cataloginventory_stock_item'),
                'preorder_availability_date',
                [
                   'type' => Table::TYPE_DATE,
                   'nullable' => true,
                   'default' => '2018-09-28',
                   'comment' => 'Preorder Availability Date'
                ]
            );
            $connection->addColumn(
                $installer->getTable('cataloginventory_stock_item'),
                'preorder_note',
                [
                   'type' => Table::TYPE_TEXT,
                   'length' => 256,
                   'nullable' => true,
                   'comment' => 'Preorder Note'
                ]
            );
        
         $installer->endSetup();
    }
}
