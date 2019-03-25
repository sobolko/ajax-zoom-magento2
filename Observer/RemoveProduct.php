<?php
namespace Ax\Zoom\Observer;

class RemoveProduct implements \Magento\Framework\Event\ObserverInterface
{
	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		try{
			$object = $observer->getEvent()->getDataObject();
			$productId = $object->getId();
			
			// do something
			error_log("delete product event 2\n", 3, '_log.txt');
			
		}catch (\Exception $e){
			//$this->_helper->debug((string)$e, array());
		}
	}
}