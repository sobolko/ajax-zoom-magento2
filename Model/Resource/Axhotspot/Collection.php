<?php

namespace Ax\Zoom\Model\Resource\Axhotspot;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
	 * Initialize resource collection
	 *
	 * @return void
	 */
    public function _construct()
    {
        //parent::_construct();
        $this->_init('Ax\Zoom\Model\Axhotspot', 'Ax\Zoom\Model\Resource\Axhotspot');
    }
}