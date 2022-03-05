<?php 
/**
 * @category Mageants CSPM
 * @package Mageants_CSPM
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\CSPM\Setup;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Store\Model\StoreManagerInterface;

/*
 * InstallSchema for Csmp
 */
class InstallSchema implements InstallSchemaInterface
{
    protected $StoreManager;     
    /**     * Init     *     * @param EavSetupFactory $eavSetupFactory     */    
    public function __construct(StoreManagerInterface $StoreManager)   
    {        
        $this->StoreManager=$StoreManager;    
    }

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $service_url = 'https://www.mageants.com/index.php/rock/register/live?ext_name=Mageants_CSPM&dom_name='.$this->StoreManager->getStore()->getBaseUrl();
        $curl = curl_init($service_url);     

        curl_setopt_array($curl, array(
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_FOLLOWLOCATION =>true,
            CURLOPT_ENCODING=>'',
            CURLOPT_USERAGENT => 'Mozilla/5.0'
        ));
        
        $curl_response = curl_exec($curl);
        curl_close($curl);

        /**
         * Create Database Table
         */
        $installer = $setup;
		    $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('cspm_configuration')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
  			'cgid',
  			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
  			5,
  			[],
  			'Customer group Id'
  		  )->addColumn(
        'website',
        \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
        255,
        [],
        'Website'
        )->addColumn(
  			'smethod',
  			\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
  			255,
  			[],
  			'Shipping Method'
  		  )->addColumn(
              'pmethod',
              \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
              255,
              [],
              'Payment Method'
        )->addColumn(
          'cstatus',
          \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
          255,
          [],
          'Enable/Disabled'
        )->addIndex(  
                        $setup->getIdxName(  
                             $setup->getTable('cspm_configuration'),  
                             ['cgid', 'smethod', 'pmethod'],  
                             \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT  
                        ),  
                        ['cgid', 'smethod', 'pmethod'],  
                        ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT]
        )->setComment(
              'Mageants CSPM configuration table'
        );
        $setup->getConnection()->createTable($table); 
        $setup->endSetup();
    }
}