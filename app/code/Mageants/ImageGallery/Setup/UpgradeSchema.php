<?php
/**
 * Copyright © 2015 Mageants. All rights reserved.
 */

namespace Mageants\ImageGallery\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '2.0.2', '<=')) {
            $setup->startSetup();
            $setup->getConnection()->addColumn(
                $setup->getTable('imagegallery_category'),
                'url_key',
                [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'comment' => 'url key'
                ]
            );

            $setup->getConnection()->addColumn(
                $setup->getTable('imagegallery_category'),
                'video_id',
                [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'comment' => 'Video Id'
                ]
            );

            $table = $setup->getConnection()->newTable(
                $setup->getTable('imagegallery_video')
            )
              ->addColumn(
                  'video_id',
                  Table::TYPE_SMALLINT,
                  null,
                  ['identity' => true, 'nullable' => false, 'primary' => true],
                  'Video ID'
              )
              ->addColumn('video_title', Table::TYPE_TEXT, 255, ['nullable' => false], 'Video Title')
              ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Video Active?')
              ->addColumn('video', Table::TYPE_TEXT, '64k', [], 'video')
              ->addIndex(
                  $setup->getIdxName(
                      $setup->getTable('imagegallery_video'),
                      ['video_title'],
                      \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
                  ),
                  ['video_title'],
                  ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
              )->setComment(
                  'Mageants VideoGallery imagegalley video'
              );
            
            $setup->getConnection()->createTable($table);
      
            $setup->endSetup();
        }
    }
}
