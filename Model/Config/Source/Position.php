<?php

namespace Ax\Zoom\Model\Config\Source;

class Position implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'inside', 'label' => __('inside')],
            ['value' => 'top',    'label' => __('top')],
            ['value' => 'right',  'label' => __('right')],
            ['value' => 'bottom', 'label' => __('bottom')],
            ['value' => 'left',   'label' => __('left')]
        ];
    }
}
