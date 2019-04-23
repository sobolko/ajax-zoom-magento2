<?php
namespace Ax\Zoom\Model\Resource;

class Axhotspot extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ajaxzoomimagehotspots', 'id');
    }
}