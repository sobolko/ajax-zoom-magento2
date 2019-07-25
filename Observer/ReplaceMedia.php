<?php
namespace Ax\Zoom\Observer;

class ReplaceMedia implements \Magento\Framework\Event\ObserverInterface
{
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        $object = $observer->getEvent();
        $vars = get_object_vars($object);
        $class = get_class($object);
        $block = $object->getBlock();
        $name = $object->getName();

        if ($observer->getBlock() instanceof Mage_Catalog_Block_Product_View_Media) {
            //error_log("replace media Y:\n", 3, '_log.txt');
        } else {
            //error_log("11replace media N:" . gettype($object) . $class . "[$block - $name]" . print_r($vars, true) . "\n", 3, '_log.txt');
            //error_log('111' . print_r($this->_response, true), 3, '_log.txt');
        }

        try {
            //error_log("observer:" . print_r($observer, true) . "\n", 3, '_log.txt');

            //$object = $observer->getEvent()->getDataObject();
            //$productId = $object->getId();
            //$test = $observer->getBlock();
            
            // do something
            //error_log("replace media3:" . print_r($object, true) . "\n", 3, '_log.txt');
            
        } catch (\Exception $e) {
            //$this->_helper->debug((string)$e, array());
        }
    }
}
