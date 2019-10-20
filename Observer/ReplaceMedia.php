<?php
namespace Ax\Zoom\Observer;

class ReplaceMedia implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // !!! check if this observer is needed
        $object = $observer->getEvent();
        $vars = get_object_vars($object);
        $class = get_class($object);
        $block = $object->getBlock();
        $name = $object->getName();
        $c = 0;

        if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_View_Media) {
            $c++;
        } else {
            $c++;
        }

        try {
            $c++;
        } catch (\Exception $e) {
            $c++;
        }
    }
}
