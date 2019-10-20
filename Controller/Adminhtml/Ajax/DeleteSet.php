<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class DeleteSet extends \Magento\Backend\App\Action
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
        $productId = $get->getParam('id_product');
        $id360set  = $get->getParam('id_360set');
        $tmp     = $this->Ax360set->load($id360set)->getData();
        $id360 = $tmp['id_360'];

        // clear AZ cache
        $images = $this->Ax360->get360Images($productId, $id360set);

        foreach ($images as $image) {
            $this->Ax360->deleteImageAZcache($image['filename']);
        }

        $this->Ax360set->setId($id360set)->delete();
        
        if (!$this->Ax360set->getCollection()->addFieldToFilter('id_360', $id360)->getData()) {
            $this->Ax360->setId($id360)->delete();
        }
        
        $path = $this->Ax360set->getBaseDir() . '/axzoom/pic/360/' . $productId . '/' . $id360 . '/' . $id360set;
        $this->deleteDirectory($path);

        $return_arr = [
            'id_360set' => $id360set,
            'id_360' => $id360,
            'path' => $path,
            'removed' => (!$this->Ax360->load($id360)->getData() ? 1 : 0),
            'confirmations' => ['The 360 image set was successfully removed.']
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }

    public function deleteDirectory($dirname, $delete_self = true)
    {
        $dirname = rtrim($dirname, '/') . '/';
        if ($this->driverFile->isExists($dirname)) {
            if ($files = $this->driverFile->readDirectory($dirname)) {
                foreach ($files as $file) {
                    if ($file != '.' && $file != '..' && $file != '.svn') {
                        if ($this->driverFile->isDirectory($file)) {
                            $this->deleteDirectory($file, true);
                        } elseif ($this->driverFile->isExists($file)) {
                            $this->driverFile->changePermissions($file, 0777); // NT ?
                            $this->driverFile->deleteFile($file);
                        }
                    }
                }
                if ($delete_self && $this->driverFile->isExists($dirname)) {
                    if (!$this->driverFile->deleteDirectory($dirname)) {
                        $this->driverFile->changePermissions($dirname, 0777); // NT ?
                        return false;
                    }
                }
                    return true;
            }
        }
        return false;
    }
}
