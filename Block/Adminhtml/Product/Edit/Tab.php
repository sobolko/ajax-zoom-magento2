<?php
namespace Ax\Zoom\Block\Adminhtml\Product\Edit;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Data\Collection;

class Tab extends \Magento\Backend\Block\Widget\Tab
{
    protected $_objectManager;
    protected $_configurableType;
    protected $_coreRegistry;
    protected $_template = 'tab.phtml';
    protected $_scopeConfig;
    protected $driverFile;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\ConfigurableProduct\Model\Product\Type\Configurable $configurableType,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        array $data = []
    ) {
        $this->_objectManager = $objectManager;
        $this->driverFile = $driverFile;
        parent::__construct($context, $data);
        $this->_configurableType = $configurableType;
        $this->_coreRegistry = $coreRegistry;
        $this->_scopeConfig = $scopeConfig;

        $this->_loadMedia();
    }

    private function _loadMedia()
    {
        $this->_addCss('axzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css');
        $this->_addCss('axzoom/jquery.editable-select.css');
    }

    private function _addCss($path)
    {
        $this->pageConfig->addRemotePageAsset($this->getBaseUrl() . $path, 'css', ['attributes' => 'media="all"']);
    }

    private function _addJs($path)
    {
        $this->pageConfig->addRemotePageAsset($this->getBaseUrl() . $path, 'js');
    }

    public function getProductId()
    {
        return $this->getRequest()->getParam('id');
    }

    public function getUsedProducts()
    {
        $product = $this->getProduct();
        
        return $this->_configurableType->getUsedProducts($product);
    }

    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }

    public function getGroups()
    {
        $productId = $this->getProductId();

        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360');
        $groups = $model->getCollection()->addFieldToFilter('id_product', $productId)->getData();

        return $groups;
    }

    public function getSetGroups()
    {
        $productId = $this->getProductId();

        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360');
        
        return $model->getSetsGroups($productId);
    }

    public function isActive()
    {
        $productId = $this->getProductId();

        $model = $this->_objectManager->create(\Ax\Zoom\Model\Axproducts::class);

        $active = $model->getCollection()->addFieldToFilter('id_product', $productId)->count() > 0 ? 0 : 1;

        return $active;
    }

    public function getSets()
    {
        $productId = $this->getProductId();

        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');
        
        return $model->getSets($productId);
    }

    public function rootFolder()
    {
        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');
        
        return $model->rootFolder();
    }

    public function getBaseUrl()
    {
        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');
        
        return $model->getBaseUrl();
    }

    public function getBaseDir()
    {
        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');
        
        return $model->getBaseDir();
    }

    public function getUrlAjax($action)
    {
        return $this->getUrl('axzoom/Ajax/' . $action);
    }

    public function getArcList()
    {
        $baseDir = $this->getBaseDir();
        $files = [];
        
        $path = $baseDir . '/axzoom/zip/';
        $items = $this->driverFile->readDirectory($path);
        foreach ($items as $item) {
            $entry = str_replace($path, '', $item);
            if ($entry != '.' && $entry != '..' && (strtolower(substr($entry, -3)) == 'zip' ||
                $this->driverFile->isDirectory($path . $entry))) {
                array_push($files, $entry);
            }
        }
          
        return $files;
    }

    public function getAxModel()
    {
        return $this->_objectManager->create('Ax\Zoom\Model\Ax360');
    }

    public function getFormKey()
    {
        $formKey = $this->_objectManager->get(\Magento\Framework\Data\Form\FormKey::class);
        return $formKey->getFormKey();
    }

    public function getVideos()
    {
        $productId = $this->getProductId();

        $model = $this->_objectManager->create(\Ax\Zoom\Model\Axvideo::class);
        
        return $model->getVideos($productId);
    }

    public function getStoreLanguages()
    {
        $langugaes_array = ['en' => 'en'];

        $storeManager = $this->_objectManager->get(\Magento\Store\Model\StoreManagerInterface::class);
        $stores = $storeManager->getStores($withDefault = false);
        foreach ($stores as $store) {
            $data = $store->getData();
            $storelang = $this->_scopeConfig->getValue(
                'general/locale/code',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $data['store_id']
            );
            $langugaes_array[substr($storelang, 0, 2)] = substr($storelang, 0, 2);
        }

        return $langugaes_array;
    }

    public function getImagesBackendHotspots($id_product, $sub = false)
    {
        $id_product = (int)$id_product;
        $az_pictures_lst = [];
        $az_az_load = $this->getBaseUrl() . 'axzoom/axZm/zoomLoad.php?azImg=';
        
        $product = $this->_objectManager->create(\Magento\Catalog\Model\Product::class)->load($id_product);
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
        $product = Mage::getModel('catalog/product')->load($id_product);

        $az_images_collection = $this->getMediaGalleryImagesAll($id_product);
        $az_az_load = Mage::getBaseUrl('js') . 'axzoom/axZm/zoomLoad.php?azImg=';
        $product_id = $id_product;

        if (count($az_images_collection) > 0) {
            foreach ($az_images_collection as $image) {
                $id = $image->getId();
                $label = $image->getLabel();

                if ($id && !stristr($label, '-swatch')) {
                    $urli = parse_url($image->getUrl());
                    $pathi = pathinfo($urli['path']);
                    $az_pictures_lst[$id] = array(
                        'id_media' => (int)$id,
                        'id_product' => (int)$product_id,
                        'image_name' => $pathi['basename'],
                        'path' => $urli['path'],
                        'label' => $label,
                        'thumb' => $az_az_load.$urli['path'].'&width=100&height=128&qual=128'
                    );
                }
            }

            if ($product->isConfigurable()) {
                $childProducts = Mage::getModel('catalog/product_type_configurable')->getUsedProducts(null, $product);

                foreach ($childProducts as $child) {
                    $additional_images = $this->getImagesBackendHotspots($child->getId(), true);
                    if (!empty($additional_images)) {
                        $az_pictures_lst = array_merge($az_pictures_lst, $additional_images);
                    }
                }
            }

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
        }
        */

        return $az_pictures_lst;
    }

    public function getPluginOptNameLabel($flip = false)
    {
        $model = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');
        //require_once $model->moduleDir().'/AzMouseoverSettings.php';
        require $model->moduleDir().'/AzMouseoverConfig.php';
        $mouseover_settings = new \Ax\Zoom\AzMouseoverSettings($az_mouseover_config_magento);

        $excl_arr = [
            'displayOnlyForThisProductID',
            'uploadNoCompress',
            'default360settings',
            'headerZindex',
            'magentoAdminThumb'
        ];

        $opt_arr = [];

        $cfg = $mouseover_settings->getConfig();
        //$cat = $mouseover_settings->getCategories();

        foreach ($cfg as $k => $v) {
            if (in_array($k, $excl_arr)) {
                continue;
            }

            $varname = 'AJAXZOOM_'.strtoupper($k);
            $opt_arr[$varname] = $k;
        }

        if ($flip === true) {
            return array_flip($opt_arr);
        }

        return $opt_arr;
    }

    public function filter360data($arr)
    {
        $groups_360 = [];

        if (is_array($arr) && !empty($arr)) {
            foreach ($arr as $k => $v) {
                $id_360 = $v['id_360'];
                $groups_360[$id_360] = $v;
                unset($groups_360[$id_360]['hotspot']);
                unset($groups_360[$id_360]['crop']);
                unset($groups_360[$id_360]['status']);
            }
        }

        return $groups_360;
    }

    public function getProductPluginOpt($id_product = 0)
    {
        $resource = $this->_objectManager->get(\Magento\Framework\App\ResourceConnection::class);
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('ajaxzoomproductsettings'); //gives table name with prefix

        $conf = $connection->fetchAll('SELECT * FROM '.$tableName.' 
            WHERE id_product = '.(int)$id_product);

        if (isset($conf[0]['psettings'])) {
            return $conf[0]['psettings'];
        } else {
            return '{}';
        }
    }

    public function returnBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int)$val;

        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }
}
