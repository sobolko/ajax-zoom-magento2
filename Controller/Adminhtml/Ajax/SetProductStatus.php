<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SetProductStatus extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360;
    protected $Ax360set;
    protected $Axproducts;
    protected $_resource;
    
    public function __construct(

        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360 $Ax360,
        \Ax\Zoom\Model\Ax360set $Ax360set,
        \Ax\Zoom\Model\Axproducts $Axproducts,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360 = $Ax360;
        $this->Ax360set = $Ax360set;
        $this->Axproducts = $Axproducts;
        $this->_resource = $resource;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        $productId =   $get->getParam('id_product');
        $az_active     =   $get->getParam('az_active');

        // save status
        if (isset($az_active)) {
            if ($az_active == 1) {
                    $this->activateAx($productId);
            } elseif ($az_active === '0') {
                $this->Axproducts->setData(['id_product' => $productId])->save();
            }
        }

        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode([
            'status' => 'ok',
            'confirmations' => ['The status has been updated.']
            ]));
    }

    public function activateAx($productId)
    {
        $con = $this->_resource->getConnection();
        $table = 'ajaxzoomproducts';
        $query = "DELETE FROM {$table} WHERE id_product = " . (int)$productId;
        $con->query($query);
    }
}
