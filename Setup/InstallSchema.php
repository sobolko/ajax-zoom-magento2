<?php
namespace Ax\Zoom\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Filesystem\Io\File;
 
class InstallSchema implements InstallSchemaInterface
{
    protected $io;

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->io = new File();

        $installer = $setup;
        $installer->startSetup();

        $installer->run("CREATE TABLE IF NOT EXISTS `ajaxzoom360` (
            `id_360` int(11) NOT NULL AUTO_INCREMENT,  
            `id_product` int(11) NOT NULL,  `name` varchar(255) NOT NULL,  
            `num` int(11) NOT NULL DEFAULT '1',  
            `settings` text NOT NULL,  
            `status` tinyint(1) NOT NULL DEFAULT '0',  
            `combinations` text NOT NULL, 
            PRIMARY KEY (`id_360`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;");

        $installer->run("CREATE TABLE IF NOT EXISTS `ajaxzoom360set` (
            `id_360set` int(11) NOT NULL AUTO_INCREMENT,  
            `id_360` int(11) NOT NULL,  
            `sort_order` int(11) NOT NULL, 
            PRIMARY KEY (`id_360set`)) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");

        $installer->run("CREATE TABLE IF NOT EXISTS `ajaxzoomproducts` (
            `id_product` int(11) NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
         
        $installer->endSetup();


        $this->createFolders();
        $this->downloadAxZm();
    }

    private function createFolders()
    {
        foreach (array('360', 'cache', 'zoomgallery', 'zoommap', 'zoomthumb', 'zoomtiles_80', 'tmp') as $folder) {
            $this->io->checkAndCreateFolder(BP . '/axzoom/pic/' . $folder, 0777);
        }
    }

    private function downloadAxZm()
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );

        // download axZm if not exists
        if (!file_exists(BP . '/axzoom/axZm') && ini_get('allow_url_fopen') ) {
            $remoteFileContents = file_get_contents('http://www.ajax-zoom.com/download.php?ver=latest', false, stream_context_create($arrContextOptions));
            $localFilePath = BP . '/axzoom/pic/tmp/jquery.ajaxZoom_ver_latest.zip';

            file_put_contents($localFilePath, $remoteFileContents);
    
            $zip = new \ZipArchive();
            $res = $zip->open($localFilePath);
            $zip->extractTo(BP . '/axzoom/pic/tmp/');
            $zip->close();
            
            rename(BP . '/axzoom/pic/tmp/axZm', BP . '/axzoom/axZm');
        }        
    }
}