<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class ActionUpdate extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Ax360set;
    protected $driverFile;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Ax360set $Ax360set,
        \Magento\Framework\Filesystem\Driver\File $driverFile
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Ax360set = $Ax360set;
        $this->driverFile = $driverFile;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        
        $txt_file = $this->Ax360set->getBaseDir() . '/axzoom/axZm/readme.txt';
        $version = '';
        $date = '';
        $review = '';

        if ($this->driverFile->isExists($txt_file)) {
            $content = $this->driverFile->fileGetContents($txt_file);
            $lines = explode("\n", $content);
            
            foreach ($lines as $line) {
                if (strstr($line, 'Version:')) {
                    $version = explode(':', $line);
                    $version = trim($version[1]);
                }

                if (strstr($line, 'Date:')) {
                    $date = explode(':', $line);
                    $date = trim($date[1]);
                }

                if (strstr($line, 'Review:')) {
                    $review = explode(':', $line);
                    $review = trim($review[1]);
                }
            }
            
        }

        $return_arr = [
            'version' => $version,
            'date' => $date,
            'review' => $review
        ];
        
        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);
        
        return $jsonResult;
    }
}
