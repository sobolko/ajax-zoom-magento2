<?php
namespace Ax\Zoom\Model\Resources;

class Axvideo extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ajaxzoomvideo', 'id_video');
    }
}
