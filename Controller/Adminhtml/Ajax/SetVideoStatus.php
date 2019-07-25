<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SetVideoStatus extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Axvideo;
    
    public function __construct(

        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Axvideo $Axvideo
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Axvideo = $Axvideo;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        $productId    =   $get->getParam('id_product');
        $id_video    =   $get->getParam('id_video');
        $status     =   $get->getParam('status');

        $this->Axvideo->load($id_video)->addData(['status' => $status])->setId($id_video)->save();

        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'status' => $status,
            'confirmations' => ['The status has been updated.']
            ]));
    }
}
