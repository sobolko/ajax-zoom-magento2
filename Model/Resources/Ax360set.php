<?php
namespace Ax\Zoom\Model\Resources;

class Ax360set extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ajaxzoom360set', 'id_360set');
    }
}
