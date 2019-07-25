<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class ActionReset extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $_cacheTypeList;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->_cacheTypeList = $cacheTypeList;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();

        
        /*
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $db_prefix = (string)Mage::getConfig()->getTablePrefix();
        $db->query('DELETE FROM `'.$db_prefix.'core_config_data` WHERE `path` LIKE \'axzoom_options/%\' AND path != \'axzoom_options/license/lic\'');

        $this->sendJsonResponse(
            array(
                'status' => 'ok'
            )
        );
        */

        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('core_config_data'); //gives table name with prefix
        $q = $connection->query('DELETE FROM `' . $tableName . '` WHERE `path` LIKE \'axzoom_options/%\' AND path != \'axzoom_options/license/lic\'');
        $this->_cacheTypeList->cleanType('config');
        
        
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'status' => 'ok'
            ]));
    }
}
