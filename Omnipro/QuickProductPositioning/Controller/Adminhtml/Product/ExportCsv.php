<?php
namespace Omnipro\QuickProductPositioning\Controller\Adminhtml\Product;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Omnipro\QuickProductPositioning\Model\ResourceModel\Position\CollectionFactory;
use Magento\Framework\File\Csv;

class ExportCsv extends Action
{
    protected $collectionFactory;
    protected $csvProcessor;

    public function __construct(
        Action\Context $context,
        CollectionFactory $collectionFactory,
        Csv $csvProcessor
    ) {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->csvProcessor = $csvProcessor;
    }

    public function execute()
    {
        // Obtener la colección de posiciones
        $collection = $this->collectionFactory->create();
        
        // Crear un archivo CSV vacío
        $csvData = [];
        
        // Definir los encabezados del CSV
        $csvData[] = ['Product ID', 'Category', 'Position'];

        // Iterar sobre la colección y agregar los datos al CSV
        foreach ($collection as $item) {
            $csvData[] = [
                $item->getProductId(),
                $item->getCategory(),
                $item->getPosition(),
            ];
        }

        // Definir el nombre del archivo
        $fileName = 'product_positions.csv';

        // Preparar el archivo CSV para la descarga
        $result = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        $result->setHeader('Content-Type', 'text/csv');
        $result->setHeader('Content-Disposition', 'attachment; filename=' . $fileName);
        
        // Convertir los datos al formato CSV y enviarlos al navegador
        $csvFile = $this->csvProcessor->arrayToCsv($csvData);
        $result->setContents($csvFile);

        return $result;
    }
}
