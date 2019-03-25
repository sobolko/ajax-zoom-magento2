<?php

namespace Ax\Zoom\Model\Config\Source;

class Galleryposition implements \Magento\Framework\Option\ArrayInterface
{
	/**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'top',    'label' => __('top')),
            array('value' => 'right',  'label' => __('right')),
            array('value' => 'bottom', 'label' => __('bottom')),
            array('value' => 'left',   'label' => __('left'))
        );
    }
}