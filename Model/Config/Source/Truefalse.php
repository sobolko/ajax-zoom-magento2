<?php

namespace Ax\Zoom\Model\Config\Source;

class Truefalse implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'true', 'label' => __('Yes')],
            ['value' => 'false',    'label' => __('No')]
        ];
    }
}
