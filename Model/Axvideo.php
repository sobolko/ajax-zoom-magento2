<?php
namespace Ax\Zoom\Model;

class Axvideo extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Ax\Zoom\Model\Resource\Axvideo');
    }

    public function getVideos($productId)
    {
		$collection = $this->getCollection();
		$collection->getSelect();
		$videos = $collection->getData();

        $r = array();
        foreach ($videos as $video) {
            $r[$video['id_video']] = $video;
        }

		return $r;
    }    
}