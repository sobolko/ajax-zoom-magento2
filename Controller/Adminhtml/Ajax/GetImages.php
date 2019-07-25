<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class GetImages extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360;
    protected $Ax360set;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360 $Ax360,
        \Ax\Zoom\Model\Ax360set $Ax360set
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360 = $Ax360;
        $this->Ax360set = $Ax360set;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        
        $productId =  $get->getParam('id_product');
        $id360set =  $get->getParam('id_360set');
        $images = $this->Ax360->get360Images($productId, $id360set);

        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'status' => 'ok',
            'id_product' => $productId,
            'id_360set' => $id360set,
            'images' => $images
            ]));
    }
}
