<?php
/**
 * @category Mageants PreOrder
 * @package Mageants_PreOrder
 * @copyright Copyright (c) 2018  Mageants
 * @author Mageants Team <support@mageants.com>
 */
namespace Mageants\PreOrder\Setup;

use Exception;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Sales\Model;
use Magento\Sales\Model\Order\StatusFactory;
use Magento\Sales\Model\ResourceModel\Order\Status;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallData
 */
class InstallData implements InstallDataInterface
{
    /**
     * Custom Processing Order-Status code
     */
    const ORDER_STATUS_PROCESSING_FULFILLMENT_CODE = 'preorder';

    /**
     * Custom Processing Order-Status label
     */
    const ORDER_STATUS_PROCESSING_FULFILLMENT_LABEL = 'PreOrder';

    
    /**
     * Status Factory
     *
     * @var StatusFactory
     */
    protected $statusFactory;

    /**
     * Status Resource Factory
     *
     * @var StatusResourceFactory
     */
    protected $status;

    /**
     * InstallData constructor
     *
     * @param StatusFactory $statusFactory
     * @param StatusResourceFactory $statusResourceFactory
     */
    public function __construct(
        StatusFactory $statusFactory,
        Status $status
    ) {
         $this->_statusFactory = $statusFactory;
        $this->_status = $status;
    }

    /**
     * Installs data for a module
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     *
     * @return void
     *
     * @throws Exception
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
 
        $data[] = ['status' => 'preorder', 'label' => 'PreOrder'];
        $data[] = ['status' => 'pending_preorder', 'label' => 'Pending Pre-Order'];
        $setup->getConnection()->insertArray($setup->getTable('sales_order_status'), ['status', 'label'], $data);
 
        $setup->getConnection()->insertArray(
            $setup->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default','visible_on_front'],
            [
            ['preorder','new', '0', '1'],
            ['pending_preorder', 'new', '0', '1']
            ]
        );
 
        $setup->endSetup();
    }
}
