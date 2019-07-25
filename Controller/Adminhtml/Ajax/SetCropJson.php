<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SetCropJson extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360;
    
    public function __construct(

        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360 $Ax360
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360 = $Ax360;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();

        $json = $get->getPost('json');
        $id_360 = $get->getParam('id_360');

        $this->Ax360->load($id_360)->addData(['crop' => addslashes($json)])->save();


        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'status' => 1 // !!!
            ]));
    }
}
