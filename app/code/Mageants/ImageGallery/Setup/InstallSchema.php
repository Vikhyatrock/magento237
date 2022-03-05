<?php
/**
 * Copyright © 2015 Mageants. All rights reserved.
 */

namespace Mageants\ImageGallery\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    protected $StoreManager;
    /**     * Init     *     * @param EavSetupFactory $eavSetupFactory     */
    public function __construct(StoreManagerInterface $StoreManager)
    {
        $this->StoreManager=$StoreManager;
    }
    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $service_url = 'https://www.mageants.com/index.php/rock/register/live?ext_name=Mageants_ImageGallery&dom_name='.$this->StoreManager->getStore()->getBaseUrl();
        $curl = curl_init($service_url);

        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION =>true,
            CURLOPT_ENCODING=>'',
            CURLOPT_USERAGENT => 'Mozilla/5.0'
        ]);
        
        $curl_response = curl_exec($curl);
        curl_close($curl);
    
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'imagegallery_category'
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('imagegallery_category')
        )
        ->addColumn(
            'category_id',
            Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Category ID'
        )
        ->addColumn('image_id', Table::TYPE_TEXT, 255, ['nullable' => false], 'Attached Image id')
        ->addColumn('category_name', Table::TYPE_TEXT, 255, ['nullable' => false], 'Category Name')
        ->addColumn('image', Table::TYPE_TEXT, '64k', [], 'image')
        ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Category Active?')
        ->addIndex(
            $setup->getIdxName(
                $setup->getTable('imagegallery_category'),
                ['category_name'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['category_name'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Mageants ImageGalley imagegallery_category'
        );
        
        
        $table1 = $installer->getConnection()->newTable(
            $installer->getTable('imagegallery_gallery')
        )
        ->addColumn(
            'image_id',
            Table::TYPE_SMALLINT,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Image ID'
        )
        ->addColumn('image_title', Table::TYPE_TEXT, 255, ['nullable' => false], 'Image Title')
        ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Image Active?')
        ->addColumn('image', Table::TYPE_TEXT, '64k', [], 'image')
        ->addIndex(
            $setup->getIdxName(
                $setup->getTable('imagegallery_gallery'),
                ['image_title'],
                \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['image_title'],
            ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
            'Mageants ImageGallery imagegalley_gallery'
        );
        
        $installer->getConnection()->createTable($table);
        /*{{CedAddTable}}*/

        
        $installer->getConnection()->createTable($table1);
        /*{{CedAddTable}}*/
        
        $installer->endSetup();
    }
}
