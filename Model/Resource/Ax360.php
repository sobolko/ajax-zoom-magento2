<?php
namespace Ax\Zoom\Model\Resource;

class Ax360 extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ajaxzoom360', 'id_360');
    }
}
