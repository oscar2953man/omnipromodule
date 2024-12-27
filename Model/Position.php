<?php
namespace Omnipro\QuickProductPositioning\Model;

use Magento\Framework\Model\AbstractModel;
use Omnipro\QuickProductPositioning\Model\ResourceModel\Position as PositionResource;

class Position extends AbstractModel
{
    /**
     * Definir el campo ID
     */
    protected $_idFieldName = 'position_id'; // Ajusta el campo de ID
    protected $_resourceName = PositionResource::class;
}
