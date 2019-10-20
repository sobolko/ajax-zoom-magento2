<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SetHotspotImgJson extends \Magento\Backend\App\Action
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

        $json = $get->getPost('json');
        $id_media = $get->getParam('id_media');
        $id_product = $get->getParam('id_product');
        $image_name = $get->getParam('image_name');
        if ($json) {
            if ($this->hasImgHotspots($id_media) === 1) {
                $this->Axhotspot->load($id_media, 'id_media')->addData(['hotspots' => addslashes($json)])->save();
            } else {
                $this->Axhotspot->load($id_media, 'id_media')->addData([
                    'hotspots' => addslashes($json),
                    'id_media'    => (int)$id_media,
                    'id_product'    => (int)$id_product,
                    'image_name'    => (string)$image_name,
                    'hotspots_active'    => 1
                    ])->save();
            }

        } else {
            $hotspot = $this->Axhotspot->load($id_media, 'id_media');
            $this->Axhotspot->setId($hotspot->getId())->delete();
      
        }

        $return_arr = [
            'status' => 1 // !!!
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
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
