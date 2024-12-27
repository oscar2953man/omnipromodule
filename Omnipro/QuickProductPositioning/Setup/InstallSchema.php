<?php
namespace Omnipro\QuickProductPositioning\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable('omnipro_product_position');

        if (!$setup->tableExists($tableName)) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true], // Añade 'unsigned'
                    'Entity ID'
                )
                ->addColumn(
                    'product_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true], // Añade 'unsigned'
                    'Product ID'
                )
                ->addColumn(
                    'position',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false],
                    'Position'
                )
                ->addIndex(
                    $setup->getIdxName($tableName, ['product_id']),
                    ['product_id'],
                    ['type' => \Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_UNIQUE]
                )
                ->addForeignKey(
                    $setup->getFkName($tableName, 'product_id', 'catalog_product_entity', 'entity_id'),
                    'product_id',
                    $setup->getTable('catalog_product_entity'),
                    'entity_id',
                    \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
                )
                ->setComment('Omnipro Product Position Table');

            $setup->getConnection()->createTable($table);
        }

        $setup->endSetup();
    }
}