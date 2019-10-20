<?php
namespace Ax\Zoom\Model;

class Axhotspot extends \Magento\Framework\Model\AbstractModel
{
    protected $_objectManager;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Store\Model\StoreManager $storeManager,
        \Magento\Framework\App\ResourceConnection $res,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_res = $res;
        $this->_objectManager = $objectManager;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Ax\Zoom\Model\Resources\Axhotspot');
    }

    public function getFrontendHotspots($productId)
    {
        $collection = $this->getCollection()->addFieldToFilter('id_product', $productId);
        $collection->getSelect();
        $rows = $collection->getData();

        $return = [];
        foreach ($rows as $r) {
            $mid = $r['id_media'];
            $return[$mid] = [];
            if ($r['hotspots_active'] == 1) {
                $return[$mid]['hotspots'] = stripslashes(trim(preg_replace('/\s+/', ' ', $r['hotspots'])));
                $return[$mid]['image_name'] = $r['image_name'];
            }
        }

        return $return;
    }

    public function getImagesBackendHotspots($id_product, $sub = false)
    {
        $id_product = (int)$id_product;
        $az_pictures_lst = [];
        $az_az_load = $this->getBaseUrl() . 'axzoom/axZm/zoomLoad.php?azImg=';
        
        $product = $this->_objectManager->create('Magento\Catalog\Model\Product')->load($id_product);
        $images = $product->getMediaGalleryImages();
        foreach ($images as $image) {
            $data = $image->getData();

            if ($data['id'] && !stristr($data['label'], '-swatch')) {
                $urli = parse_url($data['url']);
                $pathi = pathinfo($urli['path']);
                $az_pictures_lst[$data['id']] = [
                    'id_media' => (int)$data['id'],
                    'id_product' => (int)$id_product,
                    'image_name' => $pathi['basename'],
                    'path' => $urli['path'],
                    'label' => $data['label'],
                    'thumb' => $az_az_load.$urli['path'].'&width=100&height=128&qual=128'
                ];
            }
        }

            /* !!!
            if ($product->isConfigurable()) {
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);

                foreach ($childProducts as $child) {
                    $additional_images = $this->getImagesBackendHotspots($child->getId(), true);
                    if (!empty($additional_images)) {
                        $az_pictures_lst = array_merge($az_pictures_lst, $additional_images);
                    }
                }
            }
            */

            /* !!!
            if ($sub === false && !empty($az_pictures_lst)) {
                $new_arr = array();
                $ids = array();
                foreach ($az_pictures_lst as $k => $v) {
                    if (isset($v['id_media']) && $v['id_media']) {
                        array_push($ids, $v['id_media']);
                        $new_arr[$v['id_media']] = $v;
                    }
                }

                $az_pictures_lst = $new_arr;

                if (!empty($ids)) {
                    $about_hs = $this->getImagesHotspots($ids, true);
                    if (!empty($about_hs)) {
                        foreach ($about_hs as $k => $v) {
                            $az_pictures_lst[$k]['hotspots'] = $v['hotspots'];
                            $az_pictures_lst[$k]['active'] = $v['active'];
                        }
                    }
                }
            }
            */
        
        return $az_pictures_lst;
    }

    public function getBaseUrl()
    {
        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');
        
        return $model->getBaseUrl();
    }
}
