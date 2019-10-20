<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class AddSet extends \Magento\Backend\App\Action
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
        $productId =   $get->getParam('id_product');
        $name       =   $get->getParam('name');
        $existing   =   $get->getParam('existing');
        $zip        =   $get->getParam('zip');
        $delete        =   $get->getParam('delete');
        $arcfile    =   $get->getParam('arcfile');
        $newId = '';
        $newName = '';
        $newSettings = '';
        $status = ($zip == 'true' ? 1 : 0);
        
        if (!empty($existing)) {
            $id360 = $existing;
            $name = $this->Ax360->load($id360)->getName();
        } else {
            $settings = '{"position":"first","spinReverse":"true"';
            $settings .= ',"spinBounce":"false","spinDemoRounds":"3","spinDemoTime":"4500"}';
            $newSettings = $settings;
            $data = [
            'id_product' => $productId,
            'name' => $name,
            'settings' => $settings,
            'status' =>  $status
            ];
            $id360 = $newId = $this->Ax360->setData($data)->save()->getId();
            $newName = $name;
        }
        
        $id360set = $this->Ax360set->setData(['id_360' => $id360, 'sort_order' => 0])->save()->getId();

        $sets = [];
        if ($zip == 'true') {
            $sets = $this->addImagesArc($arcfile, $productId, $id360, $id360set, $delete);
        }
                
        $return_arr = [
            'status' => $status,
            'name' => $name,
            'path' => $this->Ax360set->getBaseUrl() . 'axzoom/no_image-100x100.jpg',
            'sets' => $sets,
            'id_360' => $id360,
            'id_product' => $productId,
            'id_360set' => $id360set,
            'confirmations' => ['The image set was successfully added.'],
            'new_id' => $newId,
            'new_name' => $newName,
            'new_settings' => urlencode($newSettings)
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);
        
        return $jsonResult;
    }

    public function addImagesArc($arcfile, $productId, $id360, $id360set, $delete = '')
    {
        //set_time_limit(0);

        $baseDir = $this->Ax360set->getBaseDir();
        $baseUrlJs = $this->Ax360set->getBaseUrl();
        $path = $baseDir . '/axzoom/zip/' . $arcfile;
        $dst = $this->driverFile->isDirectory($path) ? $path : $this->extractArc($path);
        
        if (!$dst || !$this->driverFile->isDirectory($dst) || !strstr($dst, '/axzoom/')) {
            return false;
        }
        
        $this->driverFile->changePermissions($dst, 0777);
        $data = $this->getFolderData($dst);
        $name = $this->Ax360->load($id360)->getName();

        $sets = [[
                'name' => $name,
                'path' => $baseUrlJs . 'axzoom/axZm/zoomLoad.php?qq=1&azImg360='
                    . $this->Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId
                    . '/' . $id360 . '/' . $id360set . '&width=100&height=100&thumbMode=contain',
                'id_360set' => $id360set,
                'id_360' => $id360,
                'status' => '1'
                ]];

        $move = $this->driverFile->isDirectory($path) ? false : true;

        if (count($data['folders']) == 0) { // files (360)
            $this->copyImages($productId, $id360, $id360set, $dst, $move);
        } elseif (count($data['folders']) == 1) { // 1 folder (360)
            $this->copyImages($productId, $id360, $id360set, $dst . '/' . $data['folders'][0], $move);
        } else { // 3d
            $this->copyImages($productId, $id360, $id360set, $dst . '/' . $data['folders'][0], $move);

            foreach ($data['folders'] as $folder) {
                $id360set = $this->Ax360set->setData(['id_360' => $id360, 'sort_order' => 0])->save()->getId();
                $this->copyImages($productId, $id360, $id360set, $dst . '/' . $folder, $move);

                $sets[] = [
                    'name' => $name,
                    'path' => $baseUrlJs . 'axzoom/axZm/zoomLoad.php?qq=1&azImg360=' .
                        $this->Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId .
                        '/' . $id360 . '/' . $id360set . '&width=100&height=100&thumbMode=contain',
                    'id_360set' => $id360set,
                    'id_360' => $id360
                    ];
            }

        }
        
        // delete temp directory which was created when zip extracted
        if (!$this->driverFile->isDirectory($path)) {
            $this->deleteDirectory($dst);
        }

        // delete the sourece file (zip/dir) if checkbox is checked
        if ($delete == 'true') {
            if ($this->driverFile->isDirectory($path)) {
                $this->deleteDirectory($dst);
            } else {
                $this->driverFile->deleteFile($path);
            }
        }
        return $sets;
    }

    public function extractArc($file)
    {
        $folder = uniqid(getmypid());
        $path = $this->Ax360set->getBaseDir().'/axzoom/pic/tmp/'.$folder;
        $this->driverFile->createDirectory($path, 0777);
                
        $zip = new \ZipArchive();
        $res = $zip->open($file);
        if ($res === true) {
            $zip->extractTo("$path/");
            $zip->close();
            return $path;
        } else {
            return false;
        }
    }

    public function getFolderData($path)
    {
        $files = [];
        $folders = [];

        $items = $this->driverFile->readDirectory($path);
        foreach ($items as $item) {
            $entry = str_replace($path . '/', '', $item);

            if ($entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '__MACOSX') {
                if ($this->driverFile->isDirectory($item)) {
                    array_push($folders, $entry);
                } else {
                    array_push($files, $entry);
                }
            }
        }

        sort($folders);
        sort($files);

        return [
            'folders' => $folders,
            'files' => $files
            ];
    }

    public function copyImages($productId, $id360, $id360set, $path, $move)
    {
        $files = $this->getFilesFromFolder($path);
        $folder = $this->createProduct360Folder($productId, $id360set);

        foreach ($files as $file) {
            $name = $productId . '_' . $id360set . '_' . $this->imgNameFilter($file);
            $tmp = explode('.', $name);
            $ext = end($tmp);
            $dst = $folder . '/' . $name;

            if ($move) {
                if (!$this->driverFile->rename($path.'/'.$file, $dst)) {
                    $this->driverFile->copy($path.'/'.$file, $dst);
                }
            } else {
                $this->driverFile->copy($path.'/'.$file, $dst);
            }
        }
    }

    public function getFilesFromFolder($path)
    {
        $files = [];
        $items = $this->driverFile->readDirectory($path);
        foreach ($items as $item) {
            $entry = str_replace($path . '/', '', $item);
            if ($entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '__MACOSX') {
                $files[] = $entry;
            }
        }
        
        return $files;
    }

    public function createProduct360Folder($productId, $id360set)
    {
        $productId =  (int)($productId);
        $id360set =  (int)($id360set);
        $tmp = $this->Ax360set->load($id360set)->getData();
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
    
    public function imgNameFilter($filename)
    {
        $filename = preg_replace('/[^A-Za-z0-9_\.-]/', '-', $filename);
        return $filename;
    }

    public function deleteDirectory($dirname, $delete_self = true)
    {
        $dirname = rtrim($dirname, '/') . '/';
        
        if (!strstr($dirname, '/axzoom/')) {
            return false;
        }
        
        if ($this->driverFile->isExists($dirname)) {
            if ($items = $this->driverFile->readDirectory($dirname)) {
                foreach ($items as $item) {

                    $file = str_replace($dirname . '/', '', $item);

                    if ($file != '.' && $file != '..' && $file != '.svn') {
                        if ($this->driverFile->isDirectory($dirname.$file)) {
                            $this->deleteDirectory($dirname.$file, true);
                        } elseif ($this->driverFile->isExists($dirname.$file)) {
                            $this->driverFile->changePermissions($dirname.$file, 0777); // NT ?
                            $this->driverFile->deleteFile($dirname.$file);
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
