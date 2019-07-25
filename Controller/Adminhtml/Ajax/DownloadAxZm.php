<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class DownloadAxZm extends \Magento\Backend\App\Action
{
	protected $messageManager;
	protected $_objectManager;
	protected $Ax360set;

	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Ax\Zoom\Model\Ax360set $Ax360set

	)
	{
		$this->messageManager = $context->getMessageManager();
		$this->_objectManager = $objectManager;
		$this->Ax360set = $Ax360set;
		parent::__construct($context);
	}

	public function execute()
	{
        $return_arr = array();
        $update = true;
        $dir = $this->Ax360set->getBaseDir() . '/axzoom/';
        $backups_dir = $dir.'backups';

        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        if ($update !== true) {
            $update = false;
        }

        if ($update) {
            if (!is_dir($backups_dir)) {
                @mkdir($backups_dir);
            }

            if (!is_writable($backups_dir)) {
                $return_arr = array(
                    'error' => $backups_dir.' is not writable by PHP'
                );
            }
        }

        // download axZm if not exists
        if (($update || !file_exists($dir.'axZm')) && is_writable($dir.'pic/tmp/')) {
            $latest_ver = 'http://www.ajax-zoom.com/download.php?ver=latest&module=magento';
            if ($update) {
                $latest_ver .= '&update=1';
            }

            $remoteFileContents = file_get_contents($latest_ver, false, stream_context_create($arrContextOptions));

            if ($remoteFileContents != false) {
                $localFilePath = $dir.'pic/tmp/jquery.ajaxZoom_ver_latest.zip';
                if (file_exists($localFilePath)) {
                    @unlink($localFilePath);
                }

                $target_bck_dir = '';

                file_put_contents($localFilePath, $remoteFileContents);

                
                $zip = new \ZipArchive();
                $zip->open($localFilePath);
                $zip->extractTo($dir.'pic/tmp/');
                $zip->close();

                if ($update && file_exists($dir.'axZm')) {
                    $target_bck_dir = $backups_dir.'/axZm_'.(microtime(true)*10000);
                    @rename($dir.'axZm', $target_bck_dir);
                }

                rename($dir.'pic/tmp/axZm', $dir.'axZm');
                unlink($localFilePath);

                if ($update) {
                    $return_arr = array(
                        'success' => 1,
                        'backupdir' => $target_bck_dir
                    );
                }
                
            }
        }
        
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode($return_arr));
	}
}