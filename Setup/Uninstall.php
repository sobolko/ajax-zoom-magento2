<?php
namespace Ax\Zoom\Setup;

class Uninstall implements \Magento\Framework\Setup\UninstallInterface
{
    /**
     * Module uninstall code
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     */
    public function uninstall(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    ) {
        $setup->startSetup();

        // !!! AZ: use prefix
        $setup->run('DROP TABLE ajaxzoom360;');
        $setup->run('DROP TABLE ajaxzoom360set;');
        $setup->run('DROP TABLE ajaxzoomproducts;');
        $setup->run('DROP TABLE ajaxzoomimagehotspots;');
        $setup->run('DROP TABLE ajaxzoomproductsettings;');
        $setup->run('DROP TABLE ajaxzoomvideo;');

        $setup->endSetup();
    }
}