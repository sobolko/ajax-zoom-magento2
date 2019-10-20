<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class AddProductImage360 extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360;
    protected $Ax360set;
    protected $driverFile;
    private $logger;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360 $Ax360,
        \Ax\Zoom\Model\Ax360set $Ax360set,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360 = $Ax360;
        $this->Ax360set = $Ax360set;
        $this->driverFile = $driverFile;
        $this->logger = $logger;
        parent::__construct($context);
    }
    
    public function execute()
    {
        $get = $this->getRequest();

        $productId = $get->getParam('id_product');
        $id360set = $get->getPost('id_360set');
        $tmp     = $this->Ax360set->load($id360set)->getData();
        $id360 = $tmp['id_360'];
        
        $files = $this->getRequest()->getFiles();
        $folder = $this->createProduct360Folder($productId, $id360set);
                
        if (isset($files['file360']['name']) && $files['file360']['name'] != '') {
            try {
                $fileName       = $files['file360']['name'];
                $fileExt        = strtolower(substr(strrchr($fileName, '.'), 1));
                $fileNamewoe    = $productId . '_' . $id360set . '_' .
                    $this->imgNameFilter(rtrim($fileName, '.' . $fileExt));
                $fileName       = $fileNamewoe . '.' . $fileExt;

                $uploader = $this->_objectManager->create(
                    \Magento\MediaStorage\Model\File\Uploader::class,
                    ['fileId' => 'file360']
                );
                $uploader->setAllowedExtensions(['png', 'jpg']);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $uploader->save($folder, $fileName);
            } catch (Exception $e) {
                $this->logger->debug($e->getMessage());
                throw $e;
            }
        }

        $return_arr = [
            'file360' => [[
            'status' => 'ok',
            'name' => $fileName,
            'id' => $fileNamewoe,
            'id_product' => $productId,
            'id_360' => $id360,
            'id_360set' => $id360set,
            'path' => $this->Ax360set->getBaseUrl() . 'axzoom/axZm/zoomLoad.php?qq=1&azImg=' .
                $this->Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId . '/' . $id360 . '/' .
                $id360set . '/' . $fileName . '&width=100&height=100&qual=90'
            ]]];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }

    public function imgNameFilter($filename)
    {
        $filename = preg_replace('/[^A-Za-z0-9_\.-]/', '-', $filename);
        return $filename;
    }

    public function createProduct360Folder($productId, $id360set)
    {
        $productId =  (int)($productId);
        $id360set =  (int)($id360set);
        $tmp     = $this->Ax360set->load($id360set)->getData();
        $id360 = $tmp['id_360'];
                
        $imgPath = $this->Ax360set->getBaseDir() . '/axzoom/pic/360/';
        $this->driverFile->changePermissions(rtrim($imgPath, '/'), 0777);

        if (!$this->driverFile->isExists($imgPath . '.htaccess')) {
            $this->driverFile->filePutContents($imgPath . '.htaccess', 'deny from all');
        }

        if (!$this->driverFile->isExists($imgPath . $productId)) {
            $this->driverFile->createDirectory($imgPath . $productId, 0777);
        }

        if (!$this->driverFile->isExists($imgPath . $productId . '/' . $id360)) {
            $this->driverFile->createDirectory($imgPath . $productId . '/' . $id360, 0777);
        }

        $folder = $imgPath . $productId . '/' . $id360 . '/' . $id360set;

        if (!$this->driverFile->isExists($folder)) {
            $this->driverFile->createDirectory($folder, 0777);
        } else {
            $this->driverFile->changePermissions($folder, 0777);
        }

        return $folder;
    }
}
