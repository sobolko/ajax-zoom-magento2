<?php
namespace Ax\Zoom\Model\Resources\Axhotspot;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Ax\Zoom\Model\Axhotspot', 'Ax\Zoom\Model\Resources\Axhotspot');
    }
}
