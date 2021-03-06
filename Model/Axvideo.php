<?php
namespace Ax\Zoom\Model;

class Axvideo extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Ax\Zoom\Model\Resources\Axvideo');
    }

    public function getVideos($productId)
    {
        $collection = $this->getCollection()->addFieldToFilter('id_product', $productId);
        $collection->getSelect();
        $videos = $collection->getData();

        $r = [];
        foreach ($videos as $video) {
            $r[$video['id_video']] = $video;
        }

        return $r;
    }
}
