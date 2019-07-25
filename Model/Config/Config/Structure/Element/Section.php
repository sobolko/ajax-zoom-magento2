<?php

namespace Ax\Zoom\Model\Config\Config\Structure\Element;

use \Magento\Config\Model\Config\Structure\Element\Section as OriginalSection;
use \Magento\Directory\Api\CountryInformationAcquirerInterface;
use \Magento\Directory\Api\Data\CountryInformationInterface;
use \Ax\Zoom\Helper\Config as ConfigHelper;
use \Magento\Directory\Helper\Data as DirectoryHelper;
use \Ax\Zoom\Model\Config\Config\Structure\Element\AzMouseoverSettings;

/**
 * Plugin to add dynamically generated groups to
 * General -> General section.
 *
 * @package Ax\Zoom\Model\Config\Config\Structure\Element
 */
class Section
{
    protected $_configInterface;
    protected $_scopeConfig;
    protected $_cacheTypeList;
    public $config;

    /**
     * Group constructor.
     */
    public function __construct(
        \Magento\Framework\App\Config\ConfigResource\ConfigInterface $configInterface,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
    ) {
        $this->_configInterface = $configInterface;
        $this->_scopeConfig = $scopeConfig;
        $this->_cacheTypeList = $cacheTypeList;

        $module_path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        require_once($module_path . '/AzMouseoverConfig.php');
        require_once($module_path . '/AzMouseoverSettings.php');
        
        $object = new \Ax\Zoom\AzMouseoverSettings();
        $this->config = $object->magento2_config();
        
        // set default values only once
        $check_value = $this->_scopeConfig->getValue('axzoom_options/general_settings/divID');
        if (empty($check_value)) {
            $values = $object->magento2_values();
            foreach ($values as $category => $fields) {
                foreach ($fields as $key => $value) {
                    $this->_configInterface->saveConfig("axzoom_options/$category/$key", $value, 'default', 0);
                }
            }
            $this->_cacheTypeList->cleanType('config');
        }
    }




    /**
     * Add dynamic region config groups for each country configured
     *
     * @param OriginalSection $subject
     * @param callable $proceed
     * @param array $data
     * @param $scope
     * @return mixed
     */
    public function aroundSetData(OriginalSection $subject, callable $proceed, array $data, $scope)
    {

        // This method runs for every section.
        // Add a condition to check for the one to which we're
        // interested in adding groups.

        if ($data['id'] == 'axzoom_options') {
            $data['children'] += $this->config;
        }

        $res = $proceed($data, $scope);

        return $res;
    }
}
