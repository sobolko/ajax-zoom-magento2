<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

use Zend\Http\Client\Adapter\Socket;

class DownloadAxZm extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360set;
    protected $driverFile;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360set $Ax360set,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360set = $Ax360set;
        $this->driverFile = $driverFile;
        parent::__construct($context);
    }

    public function execute()
    {
        $return_arr = [];
        $update = true;
        $dir = $this->Ax360set->getBaseDir() . '/axzoom/';
        $backups_dir = $dir.'backups';

        $arrContextOptions=[
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];

        if ($update !== true) {
            $update = false;
        }

        if ($update) {
            if (!$this->driverFile->isDirectory($backups_dir)) {
                $this->driverFile->createDirectory($backups_dir);
            }

            if (!$this->driverFile->isWritable($backups_dir)) {
                $return_arr = [
                    'error' => $backups_dir.' is not writable by PHP'
                ];
            }
        }

        // download axZm if not exists
        if (($update || !$this->driverFile->isExists($dir.'axZm')) && $this->driverFile->isWritable($dir.'pic/tmp/')) {
            $latest_ver = 'http://www.ajax-zoom.com/download.php?ver=latest&module=magento';
            if ($update) {
                $latest_ver .= '&update=1';
            }

            // to avoid using the function stream_context_create($arrContextOptions)
            $socket = new Socket();
            $socket->setStreamContext($arrContextOptions);

            $remoteFileContents = $this->driverFile->fileGetContents($latest_ver, false, $socket->context);

            if ($remoteFileContents != false) {
                $localFilePath = $dir.'pic/tmp/jquery.ajaxZoom_ver_latest.zip';
                if ($this->driverFile->isExists($localFilePath)) {
                    $this->driverFile->deleteFile($localFilePath);
                }

                $target_bck_dir = '';

                $this->driverFile->filePutContents($localFilePath, $remoteFileContents);

                $zip = new \ZipArchive();
                $zip->open($localFilePath);
                $zip->extractTo($dir.'pic/tmp/');
                $zip->close();

                if ($update && $this->driverFile->isExists($dir.'axZm')) {
                    $target_bck_dir = $backups_dir.'/axZm_'.(microtime(true)*10000);
                    $this->driverFile->rename($dir.'axZm', $target_bck_dir);
                }

                $this->driverFile->rename($dir.'pic/tmp/axZm', $dir.'axZm');
                $this->driverFile->deleteFile($localFilePath);

                if ($update) {
                    $return_arr = [
                        'success' => 1,
                        'backupdir' => $target_bck_dir
                    ];
                }
                
            }
        }
        
        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
