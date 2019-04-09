<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class AddVideo extends \Magento\Backend\App\Action
{
	protected $messageManager;
	protected $_objectManager;
	protected $Axvideo;
	
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Ax\Zoom\Model\Axvideo $Axvideo
	)
	{
		$this->messageManager = $context->getMessageManager();
		$this->_objectManager = $objectManager;
		$this->Axvideo = $Axvideo;
		parent::__construct($context);
	}

	public function execute()
	{
        $get = $this->getRequest();
        $id_product =   $get->getParam('id_product');
        $name = $get->getParam('name');
        $type = $get->getParam('type');
        $uid = $get->getParam('uid');

        if (empty($name)) {
            $name = 'Unnamed '.uniqid(getmypid());
        }

        $id_video = $this->Axvideo->setData(array(
            'id_product' => $id_product, 
            'name' => $name,
            'uid' => $uid,
            'type' => $type,
            'status' => 1,
            'settings' => '{"position":"last"}',
            'combinations' => '[]'
        ))->save()->getId();

        $r = array();
        $videos = $this->Axvideo->getVideos($id_product);
        foreach ($videos as $video) {
            $r[$video['id_video']] = $video;
        }
               
        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode(array(
            'status' => '1',
            'name' => $name,
            'uid' => $uid,
            'id_video' => (int)$id_video,
            'type' => $type,
            'id_product' => $id_product,
            'videos' => $r,
            'confirmations' => array('New video entry was added.')
            )));  
	}


}