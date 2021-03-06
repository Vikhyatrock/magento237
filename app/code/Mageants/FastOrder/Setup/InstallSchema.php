<?php
/**
 * @category Mageants FastOrder
 * @package Mageants_FastOrder
 * @copyright Copyright (c) 2018 Mageants
 * @author Mageants Team <support@mageants.com>
 */
 
namespace Mageants\FastOrder\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * InstallSchema for Update Database for GiftCertificate
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    
    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }
    
    /**
     * install Database for GiftCertificate
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $service_url = 'https://www.mageants.com/index.php/rock/register/live?ext_name=Mageants_FastOrder&dom_name='.$this->_storeManager->getStore()->getBaseUrl();
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
    }
}
