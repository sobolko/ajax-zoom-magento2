<?php

namespace Ax\Zoom\Model\Resources\Ax360set;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('Ax\Zoom\Model\Ax360set', 'Ax\Zoom\Model\Resources\Ax360set');
    }
}
