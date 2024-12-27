<?php
namespace Omnipro\QuickProductPositioning\Model\ResourceModel\Position;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \Omnipro\QuickProductPositioning\Model\Position::class,
            \Omnipro\QuickProductPositioning\Model\ResourceModel\Position::class
        );
    }

    /**
     * Método para recuperar datos personalizados
     */
    public function getDynamicData()
    {
        $connection = $this->getConnection();

        // Escribir la consulta SQL
        $select = $connection->select()
            ->from(['ccp' => $this->getTable('catalog_category_product')], [
                'product_id',
                'category_id',
                'position',
            ])
            ->joinLeft(
                ['cpe' => $this->getTable('catalog_product_entity')],
                'ccp.product_id = cpe.entity_id',
                ['sku']
            )
            ->joinLeft(
                ['cc' => $this->getTable('catalog_category_entity')],
                'ccp.category_id = cc.entity_id',
                ['entity_id']
            )
            ->joinLeft(
                ['ccev' => $this->getTable('catalog_category_entity_varchar')],
                'cc.entity_id = ccev.entity_id AND ccev.attribute_id = 4',
                ['category_name' => 'value']
            );

        // Ejecutar la consulta y devolver los resultados
        return $connection->fetchAll($select);
    }
}
