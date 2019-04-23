<?php
namespace Ax\Zoom\Block;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Data\Collection;

/**
* Ajaxzoom block
*/
class AxProduct extends \Magento\Framework\View\Element\Template
{
	protected $productRepository;
	protected $_coreRegistry = null;
	protected $_imageHelper;
	protected $Ax360;
	protected $Ax360set;
    protected $Axproducts;
    protected $Axvideo;
    protected $Axhotspot;
    protected $swatchHelper;
    protected $productModelFactory;
    public $_store;
    public $product_has_video_html5 = false;


	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Catalog\Block\Product\Context $context2,
        \Magento\Swatches\Helper\Data $swatchHelper,
        \Magento\Catalog\Model\ProductFactory $productModelFactory,
		ProductRepositoryInterface $productRepository,
		\Magento\Framework\Registry $registry,
		\Ax\Zoom\Model\Ax360 $Ax360,
		\Ax\Zoom\Model\Ax360set $Ax360set,
        \Ax\Zoom\Model\Axproducts $Axproducts,
        \Ax\Zoom\Model\Axvideo $Axvideo,
        \Ax\Zoom\Model\Axhotspot $Axhotspot,
        \Magento\Framework\Locale\Resolver $store,

		array $data = []
	)
	{
        $this->swatchHelper = $swatchHelper;
        $this->productModelFactory = $productModelFactory;
		$this->Ax360 = $Ax360;
		$this->Ax360set = $Ax360set;
        $this->Axproducts = $Axproducts;
        $this->Axvideo = $Axvideo;
        $this->Axhotspot = $Axhotspot;
		$this->_imageHelper = $context2->getImageHelper();
		$this->productRepository = $productRepository;
		$this->_coreRegistry = $registry;
        $this->_store = $store;

		parent::__construct(
            $context,
            $data
        );

        $this->_loadCSS();
                  
        $productId = $this->getProductId();
        if($this->isProductActive($productId) && $this->isOnlyProductActive($productId)) {
            $this->setTemplate('ajaxzoom.phtml');
        }
	}

    public function isProductActive($productId)
    {
        return $this->Axproducts->getCollection()->addFieldToFilter('id_product', $productId)->count() > 0 ? 0 : 1;
    } 

    public function isOnlyProductActive($productId)
    {
        $products = $this->_scopeConfig->getValue(
            'axzoom_options/products/displayOnlyForThisProductID',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        
        if (empty($products)) {
            return true;
        }
        
        $arr = $this->getCSV($products);
        if (in_array($productId, $arr)) {
            return true;
        }
        return false;
    }

    public function getCSV($input, $delimiter = ",", $enclosure = '"', $escape = "\\")
    {
        if (function_exists('str_getcsv')) {
            return str_getcsv($input, $delimiter, $enclosure, $escape);
        }
        else {
            $temp = fopen('php://memory', 'rw');
            fwrite($temp, $input);
            fseek($temp, 0);
            $r = fgetcsv($temp, 0, $delimiter, $enclosure);
            fclose($temp);
            return $r;
        }
    }

    public function _loadCSS()
    {
        $conf = $this->_scopeConfig->getValue('axzoom_options', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

/*
print '<pre>';
print_r($conf);
print '</pre>';
return;
*/
        $this->_addCss('axzoom/axZm/axZm.css');
        $this->_addCss('axzoom/axZm/axZmCustom.css');
            

        if($conf['general_settings']['galleryAxZmThumbSlider'] == 'true') {
            $this->_addCss('axzoom/axZm/extensions/axZmThumbSlider/skins/default/jquery.axZm.thumbSlider.css');
        }

        
        $this->_addCss('axzoom/axZm/extensions/jquery.axZm.expButton.css');

        $this->_addCss('axzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoom.5.css');
        $this->_addCss('axzoom/axZm/extensions/axZmMouseOverZoom/mods/jquery.axZm.mouseOverZoomMagento.4.css');


        if($conf['general_settings']['ajaxZoomOpenMode'] == 'fancyboxFullscreen' || $conf['general_settings']['ajaxZoomOpenMode'] == 'fancybox') {
            $this->_addCss('axzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css');
        } elseif($conf['general_settings']['ajaxZoomOpenMode'] == 'colorbox') {
            $this->_addCss('axzoom/axZm/plugins/demo/colorbox/example2/colorbox.css');
        }


        if($conf['plugin_settings']['pngModeCssFix'] == 'true') {
            $this->_addCss('axzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoomPng.5.css');
        }

        $this->_addCss('axzoom/axzoom.css');
        
    }

    private function _addCss($path)
    {
        $this->pageConfig->addRemotePageAsset($this->getBaseUrl() . $path, 'css', ['attributes' => 'media="all"']);
    }

    public function _loadJS()
    {
        $baseUrl = $this->getBaseUrl();
        $conf = $this->_scopeConfig->getValue('axzoom_options', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

        //$scripts = ['jquery', 'jquery/ui', 'swatchRenderer', $baseUrl . 'axzoom/axZm/jquery.axZm.js'];
        $scripts = ['jquery', 'jquery/ui', $baseUrl . 'axzoom/axZm/jquery.axZm.js'];
        
        if($conf['general_settings']['galleryAxZmThumbSlider'] == 'true') {
            array_push($scripts, $baseUrl . "axzoom/axZm/extensions/axZmThumbSlider/lib/jquery.mousewheel.min.js");
            array_push($scripts, $baseUrl . "axzoom/axZm/extensions/axZmThumbSlider/lib/jquery.axZm.thumbSlider.js");
        }

        if($conf['mouseover']['spinner'] == 'true') {
            array_push($scripts, $baseUrl . "axzoom/axZm/plugins/spin/spin.min.js");
        }

        
        
        array_push($scripts, $baseUrl . "axzoom/axZm/extensions/jquery.axZm.expButton.js");
        array_push($scripts, $baseUrl . "axzoom/axZm/extensions/jquery.axZm.imageCropLoad.js");
        


        array_push($scripts, $baseUrl . "axzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoom.5.js");
        array_push($scripts, $baseUrl . "axzoom/axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoomInit.5.js");

        if($conf['general_settings']['ajaxZoomOpenMode'] == 'fancyboxFullscreen' || $conf['general_settings']['ajaxZoomOpenMode'] == 'fancybox') {
            array_push($scripts, $baseUrl . "axzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.pack.js");
            array_push($scripts, $baseUrl . "axzoom/axZm/extensions/jquery.axZm.openAjaxZoomInFancyBox.js");
        } elseif($conf['general_settings']['ajaxZoomOpenMode'] == 'colorbox') {
            $page->addPageAsset($baseUrl . 'axzoom/axZm/plugins/demo/colorbox/jquery.colorbox-min.js');
        }
        
        array_push($scripts, $baseUrl . "axzoom/axZm/plugins/JSON/jquery.json-2.3.min.js");


        array_push($scripts, $baseUrl . "axzoom/axzoom.js");

        return $scripts;
    }

    public function images360Json($productId)
    {
    	return $this->Ax360->images360Json($productId);
    }

    public function getTitle()
    {	
        return "AJAX-ZOOM";
    }

    public function getBaseUrl()
    {
    	return $this->_storeManager->getStore()->getBaseUrl();
    }


    public function getProductId()
    {
    	return $this->getRequest()->getParam('id');
    }

    /**
     * Retrieve current product model
     *
     * @return \Magento\Catalog\Model\Product
     */
    public function getProduct()
    {
        if (!$this->_coreRegistry->registry('product') && $this->getProductId()) {
            $product = $this->productRepository->getById($this->getProductId());
            $this->_coreRegistry->register('product', $product);
        }
        
        return $this->_coreRegistry->registry('product');
    }     

    /**
     * Retrieve collection of gallery images
     *
     * @return Collection
     */
    /*
    public function getGalleryImages()
    {
        $product = $this->getProduct();
        $images = $product->getMediaGalleryImages();

        if ($images instanceof \Magento\Framework\Data\Collection) {
            foreach ($images as $image) {

                $image->setData(
                    'small_image_url',
                    $this->_imageHelper->init($product, 'product_page_image_small')
                        ->setImageFile($image->getFile())
                        ->getUrl()
                );
                $image->setData(
                    'medium_image_url',
                    $this->_imageHelper->init($product, 'product_page_image_medium')
                        ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                        ->setImageFile($image->getFile())
                        ->getUrl()
                );
                $image->setData(
                    'large_image_url',
                    $this->_imageHelper->init($product, 'product_page_image_large')
                        ->constrainOnly(true)->keepAspectRatio(true)->keepFrame(false)
                        ->setImageFile($image->getFile())
                        ->getUrl()
                );
            }
        }

        return $images;
    }


    public function getGalleryImagesArray()
    {
        $imagesItems = [];
        foreach ($this->getGalleryImages() as $image) {
            $imagesItems[] = [
                'thumb' => $image->getData('small_image_url'),
                'img' => $image->getData('medium_image_url'),
                'full' => $image->getData('large_image_url'),
                'caption' => $image->getLabel(),
                'position' => $image->getPosition(),
                'isMain' => $this->isMainImage($image),
            ];
        }
        if (empty($imagesItems)) {
            $imagesItems[] = [
                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'caption' => '',
                'position' => '0',
                'isMain' => true,
            ];
        }
        return $imagesItems;
    }
    */

    public function getGalleryImagesArray()
    {
        $product = $this->getProduct();
        $images = $product->getMediaGalleryImages();
        $res = array();

        if ($images instanceof \Magento\Framework\Data\Collection) {
            foreach ($images as $image) {
                $res[] = $image->getData();
            }
        }

        return $res;
    }    

    public function isMainImage($image)
    {
        $product = $this->getProduct();
        return $product->getImage() == $image->getFile();
    }

    public function getImagesJson() {
    	$images = $this->getGalleryImagesArray();
    	$tmp = array();
    	$cnt = 1;
    	foreach($images as $image) {
    		$p = parse_url($image['url']);
    		$tmp[] = $cnt . ': {img: "' . $p['path'] . '", title: ""}';
    		$cnt++;
    	}
    	return '{' . implode(',', $tmp) . '}';
    }

    public function getImagesJsonComb()
    {
        $product = $this->getProduct();

        // !!! AZ: does not work for simple products
        $attributes = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
        $_children = $product->getTypeInstance()->getUsedProducts($product);

        // method (1)
        $result = array();
        $result_idx = array();
        $attributes_codes = array();
        $idx = 0;
        foreach ($attributes as $attribute) {
            
            array_push($attributes_codes, $attribute['attribute_code']);

            foreach ($_children as $child) {
                $attributeValue = $child->getData($attribute['attribute_code']);
                if ($attributeValue) {

                    if(!isset($result[$attribute['attribute_code']])) {
                        $result[$attribute['attribute_code']] = array();
                    }

                    if(!in_array($attributeValue, $result[$attribute['attribute_code']])) {
                        $result[$attribute['attribute_code']][] = $attributeValue;
                        $result_idx[$idx][] = $attributeValue;
                    }
                }
            }
            $idx++;
        }

        $combinations = $this->arrayCombinations($result_idx);

        $for_js = array();
        foreach($combinations as $combination) {
            $attributes = array();
            $ids = array();
            foreach($combination as $idx => $attr) {
                $attributes[$attributes_codes[$idx]] = $attr;
                array_push($ids, $attr);
            }
            $combination_id = $this->getCombinationIdByAttributes($this->getProductId(), $attributes);
            
            $extraGroups = array();
            $setsGroups = $this->Ax360->getSetsGroups($combination_id);
            if($setsGroups) foreach($setsGroups as $group) array_push($extraGroups, $group['id_360']);

            $str = $this->Ax360->images360Json($this->getProductId(), $extraGroups, $combination_id);
            $images360json = str_replace("'", '"', $str);
            $for_js[implode('-', $ids)] = urlencode($images360json);
        }
        return $for_js;


        /*
        // method (2)
        //$attributes = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);
        $_children = $product->getTypeInstance()->getUsedProducts($product);
        foreach ($_children as $child) {
            $combination_id = $child->getID();
            $attributes = $child->getAttributes();

            $extraGroups = array();
            $setsGroups = $this->Ax360->getSetsGroups($combination_id);
            if($setsGroups) foreach($setsGroups as $group) array_push($extraGroups, $group['id_360']);


            $str = $this->Ax360->images360Json($this->getProductId(), $extraGroups, $combination_id);
            $images360json = str_replace("'", '"', $str);
        }

        //foreach ($_children as $child){
            //print $child->getID();


            //$attributes = $child->getAttributes();
            //foreach ($attributes as $attribute) { 
                //echo $attribute->getAttributeCode(); echo '[' . $attribute->isInSet() ? 1 : 0 . '] ' . '<br />';
            //    print_r(get_class_methods($attribute));
            //    exit;
                  
            //}

            //print_r($child->getData());
              
            //$attributes = $child->getCustomAttributes(); 
            //print_r($attributes);

            //$attributes = $child->getConfigurableAttributes(); 
            //print_r($attributes);
            //exit;
              
            //foreach ($attributes as $attribute) {
            //    print_r(get_class_methods($attribute));
            //    exit;
            //}

            //print_r(get_class_methods($child));
              
              
            //print '<br>';
            //exit;
            //$logger->info("Here are your child Product Ids ".$child->getID());
        //}
        */
    }


    public function arrayCombinations($arrays, $i = 0)
    {
            if (!isset($arrays[$i])) {
                return array();
            }
            if ($i == count($arrays) - 1) {
                return $arrays[$i];
            }

            // get combinations from subsequent arrays
            $tmp = $this->arrayCombinations($arrays, $i + 1);

            $result = array();

            // concat each array from tmp with each element from $arrays[$i]
            foreach ($arrays[$i] as $v) {
                foreach ($tmp as $t) {
                    $result[] = is_array($t) ? 
                        array_merge(array($v), $t) :
                        array($v, $t);
                }
            }

            return $result;
        }


    public function getCombinationIdByAttributes($productId, $attributes)
    {
        $currentConfigurable = $this->productModelFactory->create()->load($productId);

        $resultAttributes = $attributes;
        $product = $this->swatchHelper->loadVariationByFallback($currentConfigurable, $resultAttributes);
        if (!$product || (!$product->getImage() || $product->getImage() == 'no_selection')) {
            $product = $this->swatchHelper->loadFirstVariationWithImage(
                $currentConfigurable,
                $resultAttributes
            );
        }        
        $data = $product->getData();
        return $data['entity_id'];
    }


    public function normalizeConfig($conf, $prefix = '')
    {
        $ret = array();
        foreach ($conf as $cat => $items) {
            foreach ($items as $k => $v) {
                $ret[strtoupper($prefix).'_'.strtoupper($k)] = $v;
            }
        }

        return $ret;
    }

    public function prepareInitParamFront($conf = array())
    {
        require_once dirname(dirname(__FILE__)).'/AzMouseoverSettings.php';
        require dirname(dirname(__FILE__)).'/AzMouseoverConfig.php';
        $mouseover_settings = new \AzMouseoverSettings($az_mouseover_config_magento);

        // !!!
        //if (empty($conf)) {
        //    $conf = Mage::getStoreConfig('axzoom_options');
        //}

        return $mouseover_settings->getInitJs(
            array(
            'cfg' => $this->normalizeConfig($conf, 'AJAXZOOM'),
            'window' => 'window.',
            'holder_object' => 'jQuery.axZm_psh',
            'exclude_opt' => array(),
            'exclude_cat' => array('video_settings'),
            'ovrprefix' => 'AJAXZOOM',
            'differ' => true,
            'min' => true
            )
        );
    }    


    public function __($str) {
        return __($str);
    }

    public function videosJson($id_product, $tojson = true)
    {
        $r = array();
        $videos = $this->Axvideo->getVideos($id_product);
        foreach ($videos as $video) {
            $r[$video['id_video']] = $video;
        }

        $ret = array();
        $i = 0;

        $lang = 'en'; // !!!
        /*
        $lang = substr(Mage::app()->getLocale()->getLocaleCode(), 0, 2);
        if ($lang) {
            $lang = strtolower($lang);
        }*/

        foreach ($videos as $k => $v) {
            $i++;
            $uid = $videos[$k]['uid'];
            $data = (array)json_decode($videos[$k]['data']);

            if ($lang && !empty($data) && is_array($data) && isset($data['uid']) && is_object($data['uid'])) {
                if (!empty($data['uid']->{$lang}) && trim($data['uid']->{$lang}) != '') {
                    $uid = trim($data['uid']->{$lang});
                }
            }

            if ($videos[$k]['type'] == 'videojs') {
                $this->product_has_video_html5 = true;
            }

            $ret[$i] = array(
                'key' => $uid,
                'settings' => (array)json_decode($videos[$k]['settings']),
                'combinations' => (!$videos[$k]['combinations'] || $videos[$k]['combinations'] == '[]') ? array() : explode(',', $videos[$k]['combinations']),
                'type' => $videos[$k]['type']
            );
        }

        if ($tojson == true) {
            $ret = json_encode($ret, true);
        }

        return $ret;
    }

    public function test()
    {
        return '888';
    }

    public function getImageHotspotsProduct($id_product)
    {


        return $this->Axhotspot->getFrontendHotspots($id_product);

        /*
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $db_prefix = (string)Mage::getConfig()->getTablePrefix();
        $return = array();

        $sql = $db->fetchAll('SELECT * FROM `'.$db_prefix.'ajaxzoomimagehotspots` WHERE `id_product` = '.(int)$pid);
        if (!empty($sql)) {
            foreach ($sql as $k => $r) {
                $mid = $r['id_media'];
                $return[$mid] = array();
                if ($r['hotspots_active'] == 1) {
                    $return[$mid]['hotspots'] = trim(preg_replace('/\s+/', ' ', $r['hotspots']));
                    $return[$mid]['image_name'] = $r['image_name'];
                }
            }
        }

        return $return;
        */
    }


    
    public function getImagesBackendHotspots($id_product, $sub = false)
    {
        return 333;
    }
        //$id_product = (int)$id_product;
        //$az_pictures_lst = array();
        //print 9;
        //$product = new Product($id_product);

        //print 1;
        //$images = $product->getMediaGalleryImages();
        //foreach ($images as $image) {
        //    print 2;
        //}
        //exit;

        /*
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

        //return $az_pictures_lst;
    //}
}