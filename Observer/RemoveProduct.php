<?php
namespace Ax\Zoom\Observer;

class RemoveProduct implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $object = $observer->getEvent()->getDataObject();
            $productId = $object->getId();
            
        } catch (\Exception $e) {
            $productId = $object->getId();
        }
    }
}
