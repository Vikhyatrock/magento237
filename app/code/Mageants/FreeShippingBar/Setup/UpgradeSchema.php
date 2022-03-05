<?php
/**
 * @category Mageants FreeShippingBar
 * @package Mageants_FreeShippingBar
 * @copyright Copyright (c) 2019 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\FreeShippingBar\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{

    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.2', '<')) {
            $installer = $setup;
            $installer->startSetup();
            $freeShipping = $setup->getTable('mageants_freeshippingbar');
            if ($setup->getConnection()->isTableExists($freeShipping) == true) {
                
                $installer->getConnection()->addColumn(
                    $freeShipping,
                    'products',
                    [
                        'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                        '64k',
                        'nullable' => true,
                        'comment' => 'Selected Products'
                    ]
                );
            }
            $installer->endSetup();
        }
    }
}
