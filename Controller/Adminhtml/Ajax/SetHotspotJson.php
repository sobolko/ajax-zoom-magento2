<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SetHotspotJson extends \Magento\Backend\App\Action
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

        $this->Ax360->load($id_360)->addData(['hotspot' => addslashes($json)])->save();

        $return_arr = [
            'status' => 1 // !!!
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
