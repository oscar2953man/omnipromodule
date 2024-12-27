<?php
namespace Omnipro\QuickProductPositioning\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Position extends AbstractDb
{
    /**
     * Definir la tabla y el campo ID
     */
    protected function _construct()
    {
        $this->_init('omnipro_position_table', 'position_id'); // Ajusta el nombre de la tabla
    }
}
