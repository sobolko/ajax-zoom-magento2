<?php
namespace Ax\Zoom\Controller\Adminhtml\Ajax;

class SaveProductAzSettings extends \Magento\Backend\App\Action
{
	protected $messageManager;
	protected $_objectManager;
	protected $Ax360;
	protected $Ax360set;
	protected $Axproducts;
	protected $_resource;
	
	public function __construct(
		
		\Magento\Backend\App\Action\Context $context,
		\Magento\Framework\ObjectManagerInterface $objectManager,
		\Ax\Zoom\Model\Ax360 $Ax360,
		\Ax\Zoom\Model\Ax360set $Ax360set,
		\Ax\Zoom\Model\Axproducts $Axproducts,
		\Magento\Framework\App\ResourceConnection $resource
	)
	{
		$this->messageManager = $context->getMessageManager();
		$this->_objectManager = $objectManager;
		$this->Ax360 = $Ax360;
		$this->Ax360set = $Ax360set;
		$this->Axproducts = $Axproducts;
		$this->_resource = $resource;
		parent::__construct($context);
	}


		/*
        $db = Mage::getSingleton('core/resource')->getConnection('core_write');
        $db_prefix = (string)Mage::getConfig()->getTablePrefix();
        $request = Mage::app()->getRequest();

        $id_product = (int)$request->getParam('id_product');

        $names = explode('|', $request->getParam('names'));
        $values = explode('|', $request->getParam('values'));
        $count_names = count($names);
        $settings = array();

        for ($i = 0; $i < $count_names; $i++) {
            $key = $names[$i];
            $value = $values[$i];
            if ($key != 'name_placeholder' && !empty($key)) {
                $settings[$key] = $value;
            }
        }

        $db->query('DELETE FROM `'.$db_prefix.'ajaxzoomproductsettings` 
            WHERE id_product = '.(int)$id_product);

        if (!empty($settings)) {
            $settings = $this->prepareProductSettingsBeforeSave($settings, true);
            $n = $db->query('INSERT INTO `'.$db_prefix.'ajaxzoomproductsettings` 
                SET psettings = \''.$settings.'\', 
                id_product = '.(int)$id_product);
        }

        $this->sendJsonResponse(array(
            'moduleSettings' => Mage::getModel('axzoom/ax360')->getProductPluginOpt($id_product),
        ));
        */


	public function execute()
	{		
        $get = $this->getRequest();
        $id_product = (int)$get->getParam('id_product');
        

        $names = explode('|', $get->getParam('names'));
        $values = explode('|', $get->getParam('values'));
        $count_names = count($names);
        $settings = array();

        for ($i = 0; $i < $count_names; $i++) {
            $key = $names[$i];
            $value = $values[$i];
            if ($key != 'name_placeholder' && !empty($key)) {
                $settings[$key] = $value;
            }
        }

        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('ajaxzoomproductsettings'); //gives table name with prefix


        $connection->query('DELETE FROM '.$tableName.' 
            WHERE id_product = '.(int)$id_product);

        if (!empty($settings)) {
            $settings = $this->prepareProductSettingsBeforeSave($settings, true);
            $n = $connection->query('INSERT INTO '.$tableName.' 
                SET psettings = \''.$settings.'\', 
                id_product = '.(int)$id_product);
        }


        die($this->_objectManager->create('Magento\Framework\Json\Helper\Data')->jsonEncode(array(
            'status' => 'ok',
            'moduleSettings' => $this->getProductPluginOpt($id_product),
            'confirmations' => array('The settings has been updated.')
            )));
	}

    public function getProductPluginOpt($id_product = 0)
    {
        $resource = $this->_objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('ajaxzoomproductsettings');


        $conf = $connection->fetchAll('SELECT * FROM '.$tableName.' 
            WHERE id_product = '.(int)$id_product
        );

        if (isset($conf[0]['psettings'])) {
            return $conf[0]['psettings'];
        } else {
            return '{}';
        }
    }

    public function prepareProductSettingsBeforeSave($str, $as_obj = false)
    {
        require_once dirname(dirname(dirname(dirname(__FILE__)))).'/AzMouseoverSettings.php';
        require dirname(dirname(dirname(dirname(__FILE__)))).'/AzMouseoverConfig.php';
        $mouseover_settings = new \AzMouseoverSettings($az_mouseover_config_magento);
        $cfg = $mouseover_settings->getConfig();

        $opt_arr = array();
        $res = array();

        if (is_array($str)) {
            $settings = $str;
        } else {
            $settings = json_decode($str, true);
        }

        foreach ($settings as $key => $value) {
            if (!isset($cfg[$key])) {
                continue;
            }

            $value = trim($value);
            $isnum = is_numeric($value);
            if ($isnum && $this->isOctal($value)) {
                continue;
            }

            $value = str_replace(array("\r", "\n", "\t"), '', $value);
            $value = str_replace('\\', '', $value);
            $value = str_replace('\'', '&#39;', $value);
            $key = $cfg[$key]['category'].'/'.$key;
            $res[] = '"'.$key.'":"'.$value.'"';
            /*
            if ($value == 'false'
                || $value == 'true'
                || $value == 'null'
                || $isnum
                || substr($value, 0, 1) == '{'
                || substr($value, 0, 1) == '['
            ) {
                $res[] = '"'.$key.'":'.$value;
            } else {
                $res[] = '"'.$key.'":"'.$value.'"';
            }
            */
        }

        if ($as_obj) {
            return '{'.implode(',', $res).'}';
        } else {
            return implode(',', $res);
        }
    }


    public function isOctal($x)
    {
        return strlen($x) > 1 && decoct(octdec($x)) == $x;
    }
}