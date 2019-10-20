<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class GetHotspotImgJson extends \Magento\Backend\App\Action
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

        $id_media = $get->getParam('id_media');
        $id_product = $get->getParam('id_product');
        $image_name = $get->getParam('image_name');
        
        $hotspot = $this->Axhotspot->load($id_media, 'id_media');
        if ($hotspot->getId()) {
            $res = stripslashes($hotspot->getHotspots());
        } else {
            $res = '{}';
        }

        die($res);
        //die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode($res));
    }

    public function hasImgHotspots($id_media)
    {
        $res = $this->Axhotspot->load($id_media, 'id_media');

        if ($res->getId()) {
            return 1;
        } else {
            return 0;
        }
    }
}
