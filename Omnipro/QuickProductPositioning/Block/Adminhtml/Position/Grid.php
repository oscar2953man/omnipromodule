<?php
namespace Omnipro\QuickProductPositioning\Block\Adminhtml\Position;

use Magento\Backend\Block\Template;
use Magento\Catalog\Model\CategoryFactory; // Asegúrate de importar la clase CategoryFactory
use Omnipro\QuickProductPositioning\Helper\Data as DataHelper;
use Omnipro\QuickProductPositioning\Model\ResourceModel\Position\CollectionFactory;

class Grid extends Template
{
    protected $_template = 'Omnipro_QuickProductPositioning::position/grid.phtml';
    
    protected $dataHelper;
    protected $collectionFactory;
    protected $_categoryFactory;  // Declaramos la propiedad

    /**
     * Constructor para la clase Grid.
     */
    public function __construct(
        Template\Context $context,
        DataHelper $dataHelper,
        CollectionFactory $collectionFactory,
        CategoryFactory $categoryFactory,  // Inyectamos CategoryFactory
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
        $this->collectionFactory = $collectionFactory;
        $this->_categoryFactory = $categoryFactory;  // Asignamos la fábrica de categorías
    }

    /**
     * Obtener el nombre de la categoría a partir de su ID.
     *
     * @param int $categoryId
     * @return string
     */
    public function getCategoryName($categoryId)
    {
        $category = $this->_categoryFactory->create()->load($categoryId);
        return $category->getName();
    }

    /**
     * Obtener la colección dinámica de productos.
     *
     * @return array
     */
    public function getDynamicCollection()
    {
        $collection = $this->collectionFactory->create();
        $dynamicData = $collection->getDynamicData(); // Aquí puedes obtener los datos de tu colección

        // Iteramos sobre la colección y añadimos el nombre de la categoría
        foreach ($dynamicData as &$item) {
            // Asegúrate de que 'category_id' está presente en los datos
            $item['category_name'] = $this->getCategoryName($item['category_id']);
        }

        return $dynamicData;
    }
}
