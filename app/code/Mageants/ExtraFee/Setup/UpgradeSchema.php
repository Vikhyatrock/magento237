<?php
/**
 * @category Mageants ExtraFee
 * @package Mageants_ExtraFee
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\ExtraFee\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $quoteAddressTable = 'quote_address';
        $quoteTable = 'quote';
        $orderTable = 'sales_order';
        $invoiceTable = 'sales_invoice';
        $creditmemoTable = 'sales_creditmemo';

        //Setup two columns for quote, quote_address and order
        //Quote address tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteAddressTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    'length' => '10,2',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Fee'
                ]
            );
       
        //Quote tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($quoteTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    'length' => '10,2',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Fee'

                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable('quote_item'),
                'extrafee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '255',
                    'default' => '',
                    'nullable' => true,
                    'comment' =>'Fee Content'

                ]
            );

        //Order tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    'length' => '10,2',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Fee'

                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'efeeids',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '255',
                    'default' => null,
                    'nullable' => true,
                    'comment' =>'FeeIds'
                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'extrafeecomment',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '700',
                    'default' => null,
                    'nullable' => true,
                    'comment' =>'extrafee comment'
                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'categoryfeelable',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '700',
                    'default' => null,
                    'nullable' => true,
                    'comment' =>'Category fee lable'
                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'productfeelable',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '700',
                    'default' => null,
                    'nullable' => true,
                    'comment' =>'Product fee lable'
                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'categoryfeeapplyprdid',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '700',
                    'default' => null,
                    'nullable' => true,
                    'comment' =>'Category fee Apply product id'
                ]
            );
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($orderTable),
                'productfeeapplyprdid',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    '700',
                    'default' => null,
                    'nullable' => true,
                    'comment' =>'Product fee Apply product id'
                ]
            );

        //Invoice tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($invoiceTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    'length' => '10,2',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Fee'

                ]
            );
        //Credit memo tables
        $setup->getConnection()
            ->addColumn(
                $setup->getTable($creditmemoTable),
                'fee',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                    'length' => '10,2',
                    'default' => 0.00,
                    'nullable' => true,
                    'comment' =>'Fee'

                ]
            );
        $setup->endSetup();
    }
}
