<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class GetCropJson extends \Magento\Backend\App\Action
{
	protected $messageManager;
	protected $_objectManager;
	protected $Ax360;
	
	public function __construct(
		
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Ax\Zoom\Model\Ax360 $Ax360
	)
	{
		$this->messageManager = $context->getMessageManager();
		$this->_objectManager = $objectManager;
		$this->Ax360 = $Ax360;
		parent::__construct($context);
	}

	public function execute()
	{
        $get = $this->getRequest();

        $id_360 = $get->getParam('id_360');
        
        $ax360 = $this->Ax360->load($id_360);
        if($ax360->getId()){
            $res = stripslashes($ax360->getCrop());
        } else {
            $res = '{}';
        }

        die($res);
	}
}