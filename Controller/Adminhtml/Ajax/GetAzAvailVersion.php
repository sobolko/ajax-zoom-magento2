<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class GetAzAvailVersion extends \Magento\Backend\App\Action
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
        $return_arr = [];

        $arrContextOptions=[
            "ssl"=>[
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ],
        ];

        $output_az = file_get_contents('http://www.ajax-zoom.com/getlatestversion.php', false, stream_context_create($arrContextOptions));

        if ($output_az != false) {
            $return_arr = json_decode($output_az, true);
        } else {
            $return_arr = [
            'error' => 1
            ];
        }
        
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode($return_arr));
    }
}
