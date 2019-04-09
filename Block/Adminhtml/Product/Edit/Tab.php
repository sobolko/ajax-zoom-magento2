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
        array $data = []
    ) {
    	$this->_objectManager = $objectManager;
        parent::__construct($context, $data);
        $this->_configurableType = $configurableType;
        $this->_coreRegistry = $coreRegistry;
        $this->_scopeConfig = $scopeConfig;

        $this->_loadMedia();
    }

    private function _loadMedia()
    {
        $this->_addCss('axzoom/axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css');
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
    	$model = $this->_objectManager->create('Ax\Zoom\Model\Axproducts');
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
		$files = array();
		
		if ($handle = opendir($baseDir . '/axzoom/zip/')) {
			
			while (false !== ($entry = readdir($handle))) {
				if ($entry != '.' && $entry != '..' && (strtolower(substr($entry, -3)) == 'zip' || is_dir($baseDir . '/axzoom/zip/' . $entry)) ) {
					array_push($files, $entry);
				}
			}
			closedir($handle);
		}
		  
		return $files;
	}

	public function getAxModel()
    {
		return $this->_objectManager->create('Ax\Zoom\Model\Ax360');
	}

    public function getFormKey()
    {
        $formKey = $this->_objectManager->get('Magento\Framework\Data\Form\FormKey');
        return $formKey->getFormKey();
    }

    public function getVideos()
    {
        $productId = $this->getProductId();

        $model = $this->_objectManager->create('Ax\Zoom\Model\Axvideo');
        
        return $model->getVideos($productId);
    }


    public function getStoreLanguages()
    {
        $langugaes_array = array('en' => 'en');

        $storeManager = $this->_objectManager->get('Magento\Store\Model\StoreManagerInterface');
        $stores = $storeManager->getStores($withDefault = false);
        foreach ($stores as $store) {
            $data = $store->getData();
            $storelang =  $this->_scopeConfig->getValue('general/locale/code', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $data['store_id']);
            $langugaes_array[substr($storelang, 0, 2)] = substr($storelang, 0, 2);
        }

        return $langugaes_array;
    }    
}