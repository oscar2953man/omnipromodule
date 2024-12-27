<?php
namespace Omnipro\QuickProductPositioning\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\ResourceConnection;
use Magento\Catalog\Model\Category as CategoryModel;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    /**
     * @var ResourceConnection
     */
    protected $resourceConnection;

    /**
     * @var EavConfig
     */
    protected $_eavConfig;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param ResourceConnection $resourceConnection
     * @param EavConfig $eavConfig
     * @param ScopeConfigInterface $scopeConfig
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        ResourceConnection $resourceConnection,
        EavConfig $eavConfig,
        ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->resourceConnection = $resourceConnection;
        $this->_eavConfig = $eavConfig;
        $this->scopeConfig = $scopeConfig;
        $this->logger = $logger;
    }

    /**
     * Retorna las posiciones de los productos en las categorías
     *
     * @return array
     */
    public function getPositions(): array
    {
        $connection = $this->resourceConnection->getConnection();
        $attribute = $this->getAttribute('name'); // Asegúrate de que 'name' es el código correcto para el atributo de la categoría

        try {
            $this->logger->info('Iniciando getPositions() - SOLUCIÓN DEFINITIVA - VERIFICACIÓN ATRIBUTO');

            if (!$attribute) {
                $this->logger->critical('ERROR CRÍTICO: Atributo "name" NO ENCONTRADO. Revise la configuración de EAV en Catálogo -> Atributos -> Gestionar Atributos. Asegúrese de que exista un atributo con el código "name" para las categorías.');
                return [];
            }

            $attributeId = $attribute->getId();
            $this->logger->info('Atributo "name" encontrado. ID: ' . $attributeId);

            $dbPrefix = $this->scopeConfig->getValue('db/table_prefix', ScopeInterface::SCOPE_STORE);
            $this->logger->info('Prefijo de la base de datos (desde configuración): ' . $dbPrefix);

            // Tablas con el prefijo de la base de datos
            $tableNameCcp = $dbPrefix . 'catalog_category_product';
            $tableNameCpe = $dbPrefix . 'catalog_product_entity';
            $tableNameCc = $dbPrefix . 'catalog_category_entity';
            $tableNameCcev = $dbPrefix . 'catalog_category_entity_varchar';

            // Construcción de la consulta SQL
            $select = $connection->select()
                ->from(['ccp' => $tableNameCcp], ['product_id', 'category_id', 'position'])
                ->joinLeft(['cpe' => $tableNameCpe], 'ccp.product_id = cpe.entity_id', ['sku'])
                ->joinLeft(['cc' => $tableNameCc], 'ccp.category_id = cc.entity_id', ['entity_id'])
                ->joinLeft(['ccev' => $tableNameCcev], "cc.entity_id = ccev.entity_id", ['category_name' => 'value'])
                ->where('ccev.attribute_id = ?', $attributeId); // Corregido el uso del attribute_id en la cláusula WHERE

            $this->logger->info('Consulta SQL FINAL: ' . $select->__toString());

            // Ejecutar la consulta y obtener los resultados
            $result = $connection->fetchAll($select);
            $this->logger->info('Resultados de la consulta FINAL: ' . print_r($result, true));

            return $result;

        } catch (\Exception $e) {
            $this->logger->critical($e);
            return [];
        }
    }

    /**
     * Retorna el atributo por código
     *
     * @param string $code
     * @return \Magento\Eav\Model\Attribute
     */
    public function getAttribute($code)
    {
        return $this->_eavConfig->getAttribute(CategoryModel::ENTITY, $code);
    }

    /**
     * Función de Debug para mostrar el prefijo (ESTA FUNCIÓN *NO* SE USA EN LA SOLUCIÓN FINAL)
     *
     * @return string
     */
    public function showPrefix()
    {
        return $this->scopeConfig->getValue('db/table_prefix', ScopeInterface::SCOPE_STORE);
    }
}
