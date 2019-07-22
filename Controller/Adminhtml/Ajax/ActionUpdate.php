<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class ActionUpdate extends \Magento\Backend\App\Action
{
	protected $messageManager;
	protected $_objectManager;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager
	)
	{
		$this->messageManager = $context->getMessageManager();
		$this->_objectManager = $objectManager;
		parent::__construct($context);
	}

	public function execute()
	{
        $get = $this->getRequest();
        //$productId = $get->getParam('id_product');
        

        
        
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode(array(
            'confirmations' => array('The data has been updated')
            )));
	}
}