<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class AddSet extends \Magento\Backend\App\Action
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
	)
	{
		$this->messageManager = $context->getMessageManager();
		$this->_objectManager = $objectManager;
		$this->Ax360 = $Ax360;
		$this->Ax360set = $Ax360set;
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
            $newSettings = $settings = '{"position":"first","spinReverse":"true","spinBounce":"false","spinDemoRounds":"3","spinDemoTime":"4500"}';
            $data = array(
                'id_product' => $productId,
                'name' => $name,
                'settings' => $settings,
                'status' =>  $status
                );
            $id360 = $newId = $this->Ax360->setData($data)->save()->getId();
            $newName = $name;
        }
        
        $id360set = $this->Ax360set->setData(array('id_360' => $id360, 'sort_order' => 0))->save()->getId();

        $sets = array();
        if ($zip == 'true') {
            $sets = $this->addImagesArc($arcfile, $productId, $id360, $id360set, $delete);
        }
                
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode(array(
            'status' => $status,
            'name' => $name,
            'path' => $this->Ax360set->getBaseUrl() . 'axzoom/no_image-100x100.jpg',
            'sets' => $sets,
            'id_360' => $id360,
            'id_product' => $productId,
            'id_360set' => $id360set,
            'confirmations' => array('The image set was successfully added.'),
            'new_id' => $newId,
            'new_name' => $newName,
            'new_settings' => urlencode($newSettings)
            )));  
	}

    public function addImagesArc($arcfile, $productId, $id360, $id360set, $delete = '')
    {
        set_time_limit(0);

        $baseDir = $this->Ax360set->getBaseDir();
        $baseUrlJs = $this->Ax360set->getBaseUrl();
        $path = $baseDir . '/axzoom/zip/' . $arcfile;
        $dst = is_dir($path) ? $path : $this->extractArc($path);
        
        if (!$dst || !is_dir($dst) || !strstr($dst, '/axzoom/')){
        	return false;
		}
        
        @chmod($dst, 0777);
        $data = $this->getFolderData($dst);
        $name = $this->Ax360->load($id360)->getName();

        $sets = array(array(
                'name' => $name,
                'path' => $baseUrlJs . 'axzoom/axZm/zoomLoad.php?qq=1&azImg360=' . $this->Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId . '/' . $id360 . '/' . $id360set . '&width=100&height=100&thumbMode=contain',
                'id_360set' => $id360set,
                'id_360' => $id360,
                'status' => '1'
                ));

        $move = is_dir($path) ? false : true;

        if (count($data['folders']) == 0) { // files (360)
            $this->copyImages($productId, $id360, $id360set, $dst, $move);
        } elseif (count($data['folders']) == 1) { // 1 folder (360)
            $this->copyImages($productId, $id360, $id360set, $dst . '/' . $data['folders'][0], $move);
        } else { // 3d
            $this->copyImages($productId, $id360, $id360set, $dst . '/' . $data['folders'][0], $move);
            for ($i=1; $i < count($data['folders']); $i++) { 
                $id360set = $this->Ax360set->setData(array('id_360' => $id360, 'sort_order' => 0))->save()->getId();
                $this->copyImages($productId, $id360, $id360set, $dst . '/' . $data['folders'][$i], $move);

                $sets[] = array(
                    'name' => $name,
                    'path' => $baseUrlJs . 'axzoom/axZm/zoomLoad.php?qq=1&azImg360=' . $this->Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId . '/' . $id360 . '/' . $id360set . '&width=100&height=100&thumbMode=contain',
                    'id_360set' => $id360set,
                    'id_360' => $id360
                    );
            }
        }
        
        // delete temp directory which was created when zip extracted
        if(!is_dir($path)) {
            $this->deleteDirectory($dst);
        }

        // delete the sourece file (zip/dir) if checkbox is checked
        if($delete == 'true') {
            if(is_dir($path)) {
                $this->deleteDirectory($dst);
            } else {
                @unlink($path);
            }
        }        
        return $sets;
    }

	public function extractArc($file)
    {
		$folder = uniqid(getmypid());
		$path = $this->Ax360set->getBaseDir().'/axzoom/pic/tmp/'.$folder;
		mkdir($path, 0777);
        		
		$zip = new \ZipArchive();
		$res = $zip->open($file);
		if ($res === TRUE) {
			$zip->extractTo("$path/");
			$zip->close();
			return $path;
		} else {
			return false;
		}
	}

    public function getFolderData($path)
    {
        $files = array();
        $folders = array();
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '__MACOSX') {
                    if (is_dir($path . '/' . $entry)) {
                        array_push($folders, $entry);
                    } else {
                        array_push($files, $entry);
                    }
                }
            }
            closedir($handle);
        }
        
        sort($folders);
        sort($files);

        return array(
            'folders' => $folders,
            'files' => $files
            );
    }

    public function copyImages($productId, $id360, $id360set, $path, $move)
    {
        $files = $this->getFilesFromFolder($path);
        $folder = $this->createProduct360Folder($productId, $id360set);

        foreach ($files as $file)
        {
            $name = $productId . '_' . $id360set . '_' . $this->imgNameFilter($file);
            $tmp = explode('.', $name);
            $ext = end($tmp);
            $dst = $folder . '/' . $name;

            if($move) {
                if(@!rename($path.'/'.$file, $dst)) {
                    copy($path.'/'.$file, $dst);
                }
            } else {
                copy($path.'/'.$file, $dst);
            }
        }
    }

    public function getFilesFromFolder($path)
    {
        $files = array();
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && $entry != '.htaccess' && $entry != '__MACOSX') {
                    $files[] = $entry;
                }
            }
            closedir($handle);
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
    
    public function imgNameFilter($filename)
    {
        $filename = preg_replace('/[^A-Za-z0-9_\.-]/', '-', $filename);
        return $filename;
    }         

    public function deleteDirectory($dirname, $delete_self = true)
    {
        $dirname = rtrim($dirname, '/') . '/';
        
        if (!strstr($dirname, '/axzoom/')){
        	return false;
		}
        
        if (file_exists($dirname))
            if ($files = scandir($dirname)) {
                foreach ($files as $file)
                    if ($file != '.' && $file != '..' && $file != '.svn') {
                        if (is_dir($dirname.$file))
                            $this->deleteDirectory($dirname.$file, true);
                        elseif (file_exists($dirname.$file)) {
                            @chmod($dirname.$file, 0777); // NT ?
                            unlink($dirname.$file);
                        }
                    }
                    if ($delete_self && file_exists($dirname))
                        if (!rmdir($dirname)) {
                            @chmod($dirname, 0777); // NT ?
                            return false;
                        }
                    return true;
            }
        return false;
    }
}