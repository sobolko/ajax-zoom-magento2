<?php
namespace Ax\Zoom\Model\Resources\Axvideo;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Ax\Zoom\Model\Axvideo', 'Ax\Zoom\Model\Resources\Axvideo');
    }
}
