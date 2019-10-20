<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class DeleteProductImage360 extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360;
    protected $Ax360set;
    protected $driverFile;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360 $Ax360,
        \Ax\Zoom\Model\Ax360set $Ax360set,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360 = $Ax360;
        $this->Ax360set = $Ax360set;
        $this->driverFile = $driverFile;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();

        $imageId = $get->getParam('id_image');
        $productId = $get->getParam('id_product');
        $id360set = $get->getParam('id_360set');
        $tmp     = $this->Ax360set->load($id360set)->getData();
        $id360 = $tmp['id_360'];
        $tmp = explode('&', $get->getParam('ext'));
        $ext = reset($tmp);
        $filename = $imageId . '.' . $ext;

        $dst = $this->Ax360set->getBaseDir() . '/axzoom/pic/360/' . $productId . '/' .
            $id360 . '/' . $id360set . '/' . $filename;
        $this->driverFile->deleteFile($dst);

        $this->Ax360->deleteImageAZcache($filename);

        $return_arr = [
            'status' => 'ok',
            'content' => (object)['id' => $imageId],
            'confirmations' => ['The image was successfully deleted.']
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
