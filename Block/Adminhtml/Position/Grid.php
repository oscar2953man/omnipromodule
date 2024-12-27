<?php
namespace Omnipro\QuickProductPositioning\Block\Adminhtml\Position;

use Magento\Backend\Block\Template;
use Magento\Catalog\Model\CategoryFactory; // Aseg�rate de importar la clase CategoryFactory
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
        $this->_categoryFactory = $categoryFactory;  // Asignamos la f�brica de categor�as
    }

    /**
     * Obtener el nombre de la categor�a a partir de su ID.
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
     * Obtener la colecci�n din�mica de productos.
     *
     * @return array
     */
    public function getDynamicCollection()
    {
        $collection = $this->collectionFactory->create();
        $dynamicData = $collection->getDynamicData(); // Aqu� puedes obtener los datos de tu colecci�n

        // Iteramos sobre la colecci�n y a�adimos el nombre de la categor�a
        foreach ($dynamicData as &$item) {
            // Aseg�rate de que 'category_id' est� presente en los datos
            $item['category_name'] = $this->getCategoryName($item['category_id']);
        }

        return $dynamicData;
    }
}
