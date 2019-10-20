<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class DeleteVideo extends \Magento\Backend\App\Action
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
        $productId = $get->getParam('id_product');
        $id_video  = $get->getParam('id_video');

        $this->Axvideo->setId($id_video)->delete();
        
        $return_arr = [
            'id_video' => $id_video,
            'removed' => ($this->Axvideo->load($id_video)->getName() ? 0 : 1),
            'confirmations' => ['The video was successfully removed.']
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
