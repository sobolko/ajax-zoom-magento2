<?php

namespace Ax\Zoom\Model\Config\Source;

class Truefalse implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 'true', 'label' => __('Yes')),
            array('value' => 'false',    'label' => __('No'))
        );
    }
}