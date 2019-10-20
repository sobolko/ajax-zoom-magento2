<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class RefreshPicturesList extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Axhotspot;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Axhotspot $Axhotspot
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Axhotspot = $Axhotspot;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        $id_product = (int)$get->getParam('id_product');
        
        $return_arr = $this->Axhotspot->getImagesBackendHotspots($id_product);

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
