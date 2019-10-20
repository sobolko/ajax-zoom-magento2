<?php
namespace Ax\Zoom\Model\Resources;

class Axproducts extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    public function _construct()
    {
        $this->_init('ajaxzoomproducts', null);
    }
}
