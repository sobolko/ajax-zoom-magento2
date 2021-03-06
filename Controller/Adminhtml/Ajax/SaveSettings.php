<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SaveSettings extends \Magento\Backend\App\Action
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
        $settings =   $get->getParam('settings');
        $comb =   $get->getParam('comb');

        if (!empty($settings)) {
            foreach ($settings as $id_360 => $string) {
                $this->Ax360->load($id_360)->addData([
                'settings' => urldecode($string),
                'combinations' => isset($comb[$id_360]) ? urldecode($comb[$id_360]) : ''
                ])->setId($id_360)->save();
            }
        }

        $return_arr = [
            'status' => 'ok',
            'confirmations' => ['The settings has been updated.']
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
