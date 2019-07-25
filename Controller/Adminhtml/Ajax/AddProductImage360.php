<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class AddProductImage360 extends \Magento\Backend\App\Action
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

        $productId = $get->getParam('id_product');
        $id360set = $get->getPost('id_360set');
        $tmp     = $this->Ax360set->load($id360set)->getData();
        $id360 = $tmp['id_360'];
        
        $folder = $this->createProduct360Folder($productId, $id360set);
                
        if (isset($_FILES['file360']['name']) && $_FILES['file360']['name'] != '') {
            try {
                $fileName       = $_FILES['file360']['name'];
                $fileExt        = strtolower(substr(strrchr($fileName, '.'), 1));
                $fileNamewoe    = $productId . '_' . $id360set . '_' . $this->imgNameFilter(rtrim($fileName, '.' . $fileExt));
                $fileName       = $fileNamewoe . '.' . $fileExt;

                $uploader = $this->_objectManager->create(
                    'Magento\MediaStorage\Model\File\Uploader',
                    ['fileId' => 'file360']
                );
                $uploader->setAllowedExtensions(['png', 'jpg']);
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);

                $uploader->save($folder, $fileName);
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        }

        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'file360' => [[
            'status' => 'ok',
            'name' => $fileName,
            'id' => $fileNamewoe,
            'id_product' => $productId,
            'id_360' => $id360,
            'id_360set' => $id360set,
            'path' => $this->Ax360set->getBaseUrl() . 'axzoom/axZm/zoomLoad.php?qq=1&azImg=' . $this->Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId . '/' . $id360 . '/' . $id360set . '/' . $fileName . '&width=100&height=100&qual=90'
            ]]]));
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
        @chmod(rtrim($imgPath, '/'), 0777);

        if (!file_exists($imgPath . '.htaccess')) {
            file_put_contents($imgPath . '.htaccess', 'deny from all');
        }

        if (!file_exists($imgPath . $productId)) {
            mkdir($imgPath . $productId, 0777);
        }

        if (!file_exists($imgPath . $productId . '/' . $id360)) {
            mkdir($imgPath . $productId . '/' . $id360, 0777);
        }

        $folder = $imgPath . $productId . '/' . $id360 . '/' . $id360set;

        if (!file_exists($folder)) {
            mkdir($folder, 0777);
        } else {
            chmod($folder, 0777);
        }

        return $folder;
    }
}
