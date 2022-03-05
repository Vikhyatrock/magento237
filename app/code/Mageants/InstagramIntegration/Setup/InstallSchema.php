<?php
/**
 * @category Mageants InstagramIntegration
 * @package Mageants_InstagramIntegration
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\InstagramIntegration\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Mageants\InstagramIntegration\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        
        if (!$installer->tableExists('instagram_integration_data')) {
            /**
             * Create table 'instagram_integration_data'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('instagram_integration_data')
            )
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                ],
                'Instagram Id'
            )
            ->addColumn(
                'insta_media_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Instagram Media Id'
            )
            ->addColumn(
                'insta_media_thumbnail',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Thumbnail URL'
            )
            ->addColumn(
                'insta_media_medium',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Medium URL'
            )
            ->addColumn(
                'insta_media_large',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Large URL'
            )
            ->addColumn(
                'insta_caption',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Caption'
            )
            ->addColumn(
                'insta_likes',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Instagram Like Count'
            )
            ->addColumn(
                'insta_comments',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Instagram Comment Count'
            )
            ->addColumn(
                'insta_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Instagram Like Count'
            )
            ->addColumn(
                'insta_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                [
                'nullable'  => true,
                ],
                'Instagram Integration Status'
            )
            ->addColumn(
                'update_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => true,
                ],
                'Instagram Integration Update By'
            )
            ->addColumn(
                'link',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Redirect URL'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Image Title'
            )
            ->addColumn(
                'title1',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Title 1'
            )
            ->addColumn(
                'url_title1',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Link URL Title 1'
            )
            ->addColumn(
                'position_top1',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Top 1'
            )
            ->addColumn(
                'position_left1',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Left 1'
            )
            ->addColumn(
                'product_id_1',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Product ID 1'
            )

            ->addColumn(
                'title2',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Title 2'
            )
            ->addColumn(
                'url_title2',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Link URL Title 2'
            )
            ->addColumn(
                'position_top2',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Top 2'
            )
            ->addColumn(
                'position_left2',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Left 2'
            )
            ->addColumn(
                'product_id_2',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Product ID 2'
            )
            ->addColumn(
                'title3',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Title 3'
            )
            ->addColumn(
                'url_title3',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Link URL Title 3'
            )
            ->addColumn(
                'position_top3',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Top 3'
            )
            ->addColumn(
                'position_left3',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Left 3'
            )
            ->addColumn(
                'product_id_3',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Product ID 3'
            )
            ->addColumn(
                'title4',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Title 4'
            )
            ->addColumn(
                'url_title4',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Link URL Title 4'
            )
            ->addColumn(
                'position_top4',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Top 4'
            )
            ->addColumn(
                'position_left4',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Left 4'
            )
            ->addColumn(
                'product_id_4',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Product ID 4'
            )
            ->addColumn(
                'title5',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Title 5'
            )
            ->addColumn(
                'url_title5',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Link URL Title 5'
            )
            ->addColumn(
                'position_top5',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Top 5'
            )
            ->addColumn(
                'position_left5',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Left 5'
            )
            ->addColumn(
                'product_id_5',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Product ID 5'
            )
            ->addColumn(
                'title6',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Title 6'
            )
            ->addColumn(
                'url_title6',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Link URL Title 6'
            )
            ->addColumn(
                'position_top6',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Top 6'
            )
            ->addColumn(
                'position_left6',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Position Left 6'
            )
            ->addColumn(
                'product_id_6',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'=> 0,
                ],
                'Product ID 6'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'  => 0,
                ],
                'Store Id'
            );

            $installer->getConnection()->createTable($table);
        }

        if (!$installer->tableExists('instagram_carousel_media')) {
            /**
             * Create table 'instagram_carousel_media'
             */
            $table = $installer->getConnection()->newTable(
                $installer->getTable('instagram_carousel_media')
            )
            ->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'identity'  => true,
                'unsigned'  => true,
                'nullable'  => false,
                'primary'   => true,
                ],
                'Instagram Carousel Id'
            )
            ->addColumn(
                'instagram_data_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                [
                'nullable'  => false,
                ],
                'Instagram Data Id'
            )
            ->addColumn(
                'insta_media_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Instagram Media Id'
            )
            ->addColumn(
                'insta_carousel_thumbnail',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Carousel Thumbnail URL'
            )
            ->addColumn(
                'insta_carousel_medium',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Carousel Medium URL'
            )
            ->addColumn(
                'insta_carousel_large',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                [
                'nullable'  => false,
                ],
                'Instagram Carousel Large URL'
            )
            ->addColumn(
                'insta_carousel_media_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                [
                'nullable'  => false,
                ],
                'Instagram Carousel Media Type'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                [
                'nullable'  => false,
                'default'  => 0,
                ],
                'Store Id'
            );
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
