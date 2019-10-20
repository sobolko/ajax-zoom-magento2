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

        $resource = $this->_objectManager->get(\Magento\Framework\App\ResourceConnection::class);
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('core_config_data'); //gives table name with prefix
        $q = $connection->query('DELETE FROM `' . $tableName . '` WHERE `path` LIKE \'axzoom_options/%\' AND path != \'axzoom_options/license/lic\'');
        $this->_cacheTypeList->cleanType('config');

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData([
            'status' => 'ok'
            ]);
        return $jsonResult;
    }
}
