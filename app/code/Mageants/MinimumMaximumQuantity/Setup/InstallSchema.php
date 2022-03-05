<?php 
namespace Mageants\MinimumMaximumQuantity\Setup;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\DB\Ddl\Table;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface{
    public function install(SchemaSetupInterface $setup,ModuleContextInterface $context){
        $setup->startSetup();
        $conn = $setup->getConnection();
        $tableName = $setup->getTable('mageants_minmaxqty');
        if($conn->isTableExists($tableName) != true){
            $table = $conn->newTable($tableName)
                            ->addColumn(
                                'id',
                                Table::TYPE_INTEGER,
                                null,
                     ['identity'=>true,'unsigned'=>true,'nullable'=>false,'primary'=>true],
                     'Id'
                                )
                            ->addColumn(
                                'p_id',
                                Table::TYPE_INTEGER,
                                null,
                                ['nullable'=>false,'default'=>'0'],
                                'Product Id'
                                )
                            ->addColumn(
                                'min_qty',
                                Table::TYPE_INTEGER,
                                10,
                                [],
                                'Minimum Quantity'
                                )
                            ->addColumn(
                                'max_qty',
                                Table::TYPE_INTEGER,
                                10,
                                [],
                                'Maximum Quantity'
                                )
                            ->setOption('charset','utf8');
            $conn->createTable($table);
        }
        $setup->endSetup();
    }
}
 ?>