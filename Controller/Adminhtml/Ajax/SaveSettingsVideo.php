<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SaveSettingsVideo extends \Magento\Backend\App\Action
{
    protected $messageManager;
    protected $_objectManager;
    protected $Axvideo;
    
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Ax\Zoom\Model\Axvideo $Axvideo
    ) {
        $this->messageManager = $context->getMessageManager();
        $this->_objectManager = $objectManager;
        $this->Axvideo = $Axvideo;
        parent::__construct($context);
    }

    public function execute()
    {
        $get = $this->getRequest();
        $id_product =   $get->getParam('id_product');
        $id_video     =   $get->getParam('id_video');

        $names = explode('|', $get->getParam('names'));
        $values = explode('|', $get->getParam('values'));

        $combinations = $get->getParam('combinations');
        if ($combinations && $combinations != 'all') {
            $combinations = explode('|', $combinations);
        } else {
            $combinations = '';
        }

        $count_names = count($names);
        $settings = [];

        for ($i = 0; $i < $count_names; $i++) {
            $key = $names[$i];
            $value = $values[$i];
            if ($key != 'name_placeholder' && !empty($key)) {
                $settings[$key] = $value;
            }
        }

        $name = $get->getParam('name');
        $uid = $get->getParam('uid');
        $type = $get->getParam('type');
        $uid_int = $get->getParam('uid_int');

        $data = [
            'uid' => json_decode($uid_int, true)
        ];

        $this->Axvideo->load($id_video)->addData([
            'settings' => json_encode($settings),
            'combinations'  => (empty($combinations) ? '' : implode(',', $combinations)),
            'name'  => $name,
            'uid' => $uid,
            'type' => $type,
            'data'  => json_encode($data)
        ])->setId($id_video)->save();

        $r = [];
        $videos = $this->Axvideo->getVideos($id_product);
        foreach ($videos as $video) {
            $r[$video['id_video']] = $video;
        }

        $return_arr = [
            'status' => 'ok',
            'id_product' => $id_product,
            'id_video' => $id_video,
            'videos' => $r,
            'confirmations' => ['The settings have been updated.']
            ];

        $jsonResult = $this->_objectManager->create(\Magento\Framework\Controller\Result\JsonFactory::class)->create();
        $jsonResult->setData($return_arr);

        return $jsonResult;
    }
}
