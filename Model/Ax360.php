<?php
namespace Ax\Zoom\Model;


class Ax360 extends \Magento\Framework\Model\AbstractModel
{
	static $axZmH;
	static $zoom;
	protected $_res;

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
        $this->_init('Ax\Zoom\Model\Resource\Ax360');
    }

    public function getSetsGroups($productId)
    {
    	$tblItems = $this->_res->getTableName('ajaxzoom360set');
		$collection = $this->getCollection();
		$collection->getSelect()->join(array('i' => $tblItems),
			'main_table.id_360 = i.id_360',
			array('qty' => 'COUNT(i.id_360)', 'id_360set' => 'id_360set'));

		$collection->getSelect()->group('main_table.id_360');
		return $collection->addFieldToFilter('id_product', $productId)->getData();
    }

    public function getSetsGroup($id360)
    {
    	$tblItems = $this->_res->getTableName('ajaxzoom360set');
		$collection = $this->getCollection();
		$collection->getSelect()->join(array('i' => $tblItems),
			'main_table.id_360 = i.id_360',
			array('qty' => 'COUNT(i.id_360)', 'id_360set' => 'id_360set'));

		$collection->getSelect()->group('main_table.id_360');
		return $collection->addFieldToFilter('main_table.id_360', $id360)->getData();
    }

	public function images360Json($productId, $extraGroups = array(), $combination_id = false)
	{
		$extraGroups = array_unique($extraGroups);
		
		$json = '{';
		$cnt = 1;

		if (!is_array($productId))	{
			$products = array($productId);
		} else {
			$products = $productId;
		}

		$tmp = array();
		foreach ($products as $productId) {
			$setsGroups = $this->getSetsGroups($productId);
			  
			foreach ($setsGroups as $group) {

				if($combination_id) {
					$combinations = json_decode($group['combinations'], true);
					if(count($combinations) > 0 && !in_array($combination_id, $combinations)) {
						continue;
					}
				}

				if ($group['status'] == 0)
					continue;

				$settings = $this->prepareSettings($group['settings']);
				if (!empty($settings)) $settings = ", $settings";

				if ($group['qty'] > 0) {
					if ($group['qty'] == 1) {
						$tmp[] = "'" . $group['id_360'] . "'" . ":  {'path': '" . $this->rootFolder() . "axzoom/pic/360/" . $productId . "/" . $group['id_360'] . "/" . $group['id_360set'] . "'" . $settings . ", 'combinations': [" . $group['combinations'] . "]}";
					} else {
						$tmp[] = "'" . $group['id_360'] . "'" . ":  {'path': '" . $this->rootFolder() . "axzoom/pic/360/" . $productId . "/" . $group['id_360'] . "'" . $settings . ", 'combinations': [" . $group['combinations'] . "]}";
					}
				}
			}
		}

		if ($extraGroups) foreach ($extraGroups as $id360) {
			
			$setsGroup = $this->getSetsGroup($id360);
			$group = $setsGroup[0];

			if ($group['status'] == 0)
				continue;

			$settings = $this->prepareSettings($group['settings']);
			if (!empty($settings)) $settings = ", $settings";

			if ($group['qty'] > 0) {
				if ($group['qty'] == 1) {
					$tmp[] = "'" . $group['id_360'] . "'" . ":  {'path': '" . $this->rootFolder() . "axzoom/pic/360/" . $group['id_product'] . "/" . $group['id_360'] . "/" . $group['id_360set'] . "'" . $settings . ", 'combinations': [" . $group['combinations'] . "]}";
				} else {
					$tmp[] = "'" . $group['id_360'] . "'" . ":  {'path': '" . $this->rootFolder() . "axzoom/pic/360/" . $group['id_product'] . "/" . $group['id_360'] . "'" . $settings . ", 'combinations': [" . $group['combinations'] . "]}";
				}
			}
		}
		$json .= implode(',', $tmp);
		
		$json .= '}';

		return $json;
	}

	public function prepareSettings($str)
	{
		$res = array();
		  
		$settings = (array)$this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonDecode($str);
		foreach ($settings as $key => $value) {
			if ($value == 'false' || $value == 'true' || $value == 'null' || is_numeric($value) ||  substr($value, 0, 1) == '{' ||  substr($value, 0, 1) == '[') {
				$res[] = "'$key': $value";
			} else {
				$res[] = "'$key': '$value'";
			}
		}
		return implode(', ', $res);
	}

	public function get360Images($productId, $id360set = '')
	{
		$Ax360set = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');

		$files = array();
		$tmp = $Ax360set->load($id360set)->getData();
		$id360 = $tmp['id_360'];

		$dir = $Ax360set->getBaseDir() . '/axzoom/pic/360/' . $productId . '/' . $id360 . '/' . $id360set;
		if (file_exists($dir) && $handle = opendir($dir)) {
			while (false !== ($entry = readdir($handle))) {
				if ($entry != "." && $entry != "..") {
					$files[] = $entry;
				}
			}
			closedir($handle);
		}
		sort($files);

		$res = array();
		foreach ($files as $entry) {
			$tmp = explode('.', $entry);
			$ext = end($tmp);
			$name = preg_replace('|\.' . $ext . '$|', '', $entry);
			$res[] = array(
				'thumb' => $Ax360set->getBaseUrl() . 'axzoom/axZm/zoomLoad.php?azImg=' . $Ax360set->rootFolder() . 'axzoom/pic/360/' . $productId . '/' . $id360 . '/' . $id360set . '/' . $entry . '&width=100&height=100&qual=90',
				'filename' => $entry,
				'id' => $name,
				'ext' => $ext
				); 
		}

		return $res;
	}

	public function deleteImageAZcache($file)
	{ 
		$Ax360set = $this->_objectManager->create('Ax\Zoom\Model\Ax360set');

		// Include all classes
		include_once ($Ax360set->getBaseDir() . '/axzoom/axZm/zoomInc.inc.php');
		
		if (!Ax360::$axZmH){
			Ax360::$axZmH = $axZmH;
			Ax360::$zoom = $zoom;
		}
		
		// What to delete
		$arrDel = array('In' => true, 'Th' => true, 'tC' => true, 'mO' => true, 'Ti' => true);

		// Remove all cache
		Ax360::$axZmH->removeAxZm(Ax360::$zoom, $file, $arrDel, false);
	}

	public function isProductActive($productId)
	{
		return !Mage::getModel('axzoom/axproducts')->getCollection()->addFieldToFilter('id_product', $productId)->count();
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
	
	public function isOnlyProductActive($productId)
	{
		$products = Mage::getStoreConfig('axzoom_options/products/displayOnlyForThisProductID');
		
		if (empty($products)) {
			return true;
		}
		
		$arr = $this->getCSV($products);
		if (in_array($productId, $arr)) {
			return true;
		}
		return false;
	}
	
	public function imagesJsonAll($arr)
	{
		$imagesJson = array();
		foreach ($arr as $k=>$v){
			array_push($imagesJson, '{img: "' .$v . '", title: ""}');
		}
		return '[' .implode(', ', $imagesJson). ']';
	}
	
	public function findDefaultLabelValue($arr, $key)
	{
		if (!is_array($arr)){return false;}
		foreach ($arr as $k=>$v){
			if (isset($v['value']) && $v['value'] == $key && isset($v['label'])){
				return $v['label'];
			}
		}
		return false;
	}

    public function rootFolder()
    {
        $p = parse_url($this->getBaseUrl());
        return str_replace('index.php/', '', $p['path']);
    }

    public function getBaseUrl()
    {
    	return $this->_storeManager->getStore()->getBaseUrl();
    }    

    public function getBaseDir()
    {
    	return BP;
    }

}

Ax360::$axZmH;
Ax360::$zoom;