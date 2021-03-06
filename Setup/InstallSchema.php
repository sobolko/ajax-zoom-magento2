<?php
namespace Ax\Zoom\Setup;
 
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
 
class InstallSchema implements InstallSchemaInterface
{
    protected $io;

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->io = new File();
        $this->fileDriver = new FileDriver();

        $installer = $setup;
        $installer->startSetup();
        $db_prefix = ''; // !!!

        // !!! below get table prefix: NOT TESTET YET
        /*
        use Magento\Framework\App\ObjectManager;
        ObjectManager::getInstance()->get(CountryWithWebsitesSource::class);

        $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
        $obj = $bootstrap->getObjectManager();
        $deploymentConfig = $obj->get('Magento\Framework\App\DeploymentConfig');
        var_dump($deploymentConfig->get('db/table_prefix'));
        */

        $installer->run("CREATE TABLE IF NOT EXISTS `ajaxzoom360` (
            `id_360` int(11) NOT NULL AUTO_INCREMENT,  
            `id_product` int(11) NOT NULL,  `name` varchar(255) NOT NULL,  
            `num` int(11) NOT NULL DEFAULT '1',  
            `settings` text NOT NULL,  
            `crop` text NOT NULL,  
            `hotspot` text NOT NULL,  
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
         
        $installer->run("CREATE TABLE IF NOT EXISTS `{$db_prefix}ajaxzoomvideo` 
            (`id_video` int(11) NOT NULL AUTO_INCREMENT,
            `uid` varchar(255) NOT NULL,
            `id_product` int(11) NOT NULL,
            `name` varchar(255) NOT NULL,
            `type` varchar(64) NOT NULL DEFAULT '',
            `thumb` varchar(255) NOT NULL,
            `settings` text NOT NULL,
            `status` tinyint(1) NOT NULL DEFAULT '1',
            `combinations` text NOT NULL,
            `auto` tinyint(1) NOT NULL DEFAULT '1',
            `data` text CHARACTER SET utf16 NOT NULL,
            PRIMARY KEY (`id_video`),
            KEY `id_product` (`id_product`),
            KEY `uid` (`uid`)) 
            ENGINE=InnoDB 
            DEFAULT CHARSET=utf8;");

        $installer->run("CREATE TABLE IF NOT EXISTS `{$db_prefix}ajaxzoomimagehotspots` 
            (`id` int(11) NOT NULL AUTO_INCREMENT, 
            `id_media` int(11) NOT NULL,
            `id_product` int(11) NOT NULL,
            `image_name` varchar(255) NOT NULL,
            `hotspots_active` int(1) NOT NULL DEFAULT 1,
            `hotspots` text NOT NULL,
            PRIMARY KEY (`id`)) 
            ENGINE=InnoDB 
            DEFAULT CHARSET=utf8;");

        $installer->run("CREATE TABLE IF NOT EXISTS `{$db_prefix}ajaxzoomproductsettings` 
            (`id_product` int(11) NOT NULL, 
            `psettings` text NOT NULL, 
            `psettings_embed` text NOT NULL, 
            PRIMARY KEY (`id_product`)) 
            ENGINE=InnoDB 
            DEFAULT CHARSET=utf8;");

        $installer->endSetup();

        $this->createFolders();
        $this->downloadAxZm();
    }


    private function copyR($source, $dest)
    {
        mkdir($dest, 0755);
        foreach (
         $iterator = new \RecursiveIteratorIterator(
          new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS),
          \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
          if ($item->isDir()) {
            mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
          } else {
            copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
          }
        }
    }

    private function createFolders()
    {
        $path = dirname(dirname(__FILE__));
        
        $this->copyR($path . '/axzoom', BP . '/pub/axzoom');


        $this->io->checkAndCreateFolder(BP . '/pub/axzoom/zip', 0777);

        foreach (['360', 'cache', 'zoomgallery', 'zoommap', 'zoomthumb', 'zoomtiles_80', 'tmp'] as $folder) {
            $this->io->checkAndCreateFolder(BP . '/pub/axzoom/pic/' . $folder, 0777);
        }
    }

    private function downloadAxZm()
    {
        $arrContextOptions=[
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];

        // download axZm if not exists
        if (!file_exists(BP . '/pub/axzoom/axZm') && ini_get('allow_url_fopen')) {
            $remoteFileContents = file_get_contents(
                'http://www.ajax-zoom.com/download.php?ver=latest&magento2=1',
                false,
                stream_context_create($arrContextOptions)
            );
            $localFilePath = BP . '/pub/axzoom/pic/tmp/jquery.ajaxZoom_ver_latest.zip';

            file_put_contents($localFilePath, $remoteFileContents);
    
            $zip = new \ZipArchive();
            $res = $zip->open($localFilePath);
            $zip->extractTo(BP . '/pub/axzoom/pic/tmp/');
            $zip->close();
            
            rename(BP . '/pub/axzoom/pic/tmp/axZm', BP . '/pub/axzoom/axZm');
        }
    }
}
