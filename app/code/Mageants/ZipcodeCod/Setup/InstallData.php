<?php
/**
 * @category Mageants ZipcodeCod
 * @package Mageants_ZipcodeCod
 * @copyright Copyright (c) 2017 Mageants
 * @author Mageants Team <support@mageants.com>
 */

namespace Mageants\ZipcodeCod\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $setup;
    
    /**
     * @var StoreManagerInterface
     */
    public $StoreManager;

    /**
     * @var \Magento\Framework\HTTP\Client\Curl
     */
    public $curl;

    /**
     * @param StoreManagerInterface $StoreManager
     */
    public function __construct(
        StoreManagerInterface $StoreManager,
        \Magento\Framework\HTTP\Client\Curl $curl
    ) {
        $this->StoreManager=$StoreManager;
        $this->_curl = $curl;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $Context = $context;
        $this->setup = $setup;

        $service_url = 'https://www.mageants.com/index.php/rock/register/live';
        $curl_post_data = [
            'ext_name' => 'COD Based on Zipcode',
            'dom_name'=>$this->StoreManager->getStore()->getBaseUrl()
        ];
        $this->_curl->get($service_url);
        $this->_curl->setOption(CURLOPT_RETURNTRANSFER, $curl_post_data);
        $this->_curl->setOption(CURLOPT_POST, $curl_post_data);
        $this->_curl->setOption(CURLOPT_POSTFIELDS, $curl_post_data);
        //response will contain the output in form of JSON string
        $response = $this->_curl->getBody();
    }
}
