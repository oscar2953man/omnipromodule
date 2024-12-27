<?php
namespace Omnipro\QuickProductPositioning\Model;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\App\ResourceConnection;

class ProductPositionUpdater
{
    protected $categoryCollectionFactory;
    protected $productCollectionFactory;
    protected $resourceConnection;

    public function __construct(
        CategoryCollectionFactory $categoryCollectionFactory,
        ProductCollectionFactory $productCollectionFactory,
        ResourceConnection $resourceConnection
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->resourceConnection = $resourceConnection;
    }

    public function updatePositionsFromFile($filePath)
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $connection->getTableName('catalog_category_product');
        $data = $this->parseCsv($filePath); // Implementar la lógica para leer el archivo.

        foreach ($data as $row) {
          $productId = $row['product_id'];
          $categoryId = $row['category_id'];
          $position = $row['position'];

          $connection->update(
            $tableName,
            ['position' => $position],
            ['product_id = ?' => $productId, 'category_id = ?' => $categoryId]
          );
        }
    }

    private function parseCsv($filePath) {
        // Implementar la lógica para parsear el archivo CSV.
        // Debe retornar un array con los datos: product_id, category_id, position.
        // Ejemplo: [['product_id' => 1, 'category_id' => 10, 'position' => 1], ...]
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $data = [];
            $header = fgetcsv($handle, 1000, ",");
            while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $data[] = array_combine($header, $row);
            }
            fclose($handle);
            return $data;
        } else {
            throw new \Exception("Error al abrir el archivo CSV.");
        }
    }
}