<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

//use Symfony\Component\Finder\Iterator\RecursiveDirectoryIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

class ActionGetNumberImages extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360set;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360set $Ax360set
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360set = $Ax360set;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('catalog_product_entity_media_gallery'); //gives table name with prefix
        $q = $connection->fetchAll('SELECT count(DISTINCT value) as numImg FROM `'.$tableName.'`');
        $dst = $this->Ax360set->getBaseDir() . '/axzoom/pic/360/';
        $img360 = count($this->rSearch($dst, '/\.(jpeg|jpg|png|gif|bmp|tif|tiff)/i'))/2;

        
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'images2d' => $q[0]['numImg'],
            'images360' => $img360
            ]));
    }

    public function rSearch($folder, $pattern)
    {
        $dir = new RecursiveDirectoryIterator($folder);
        $ite = new RecursiveIteratorIterator($dir);
        $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
        $fileList = [];

        foreach ($files as $file) {
            $fileList = array_merge($fileList, $file);
        }

        return $fileList;
    }
}
