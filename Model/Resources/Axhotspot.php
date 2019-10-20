<?php
namespace Ax\Zoom\Model\Resources;

class Axhotspot extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ajaxzoomimagehotspots', 'id');
    }
}
