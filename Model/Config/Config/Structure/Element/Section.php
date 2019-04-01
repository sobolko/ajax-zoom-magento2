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
    )
    {
        $this->_configInterface = $configInterface;
        $this->_scopeConfig = $scopeConfig;
        $this->_cacheTypeList = $cacheTypeList;

        //error_log("-----" . dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . "-----: \n", 3, '_log.txt');

        $module_path = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        require_once($module_path . '/AzMouseoverConfig.php');
        require_once($module_path . '/AzMouseoverSettings.php');
        
        $object = new \AzMouseoverSettings();
        $this->config = $object->magento2_config();
        $values = $object->magento2_values();
        foreach ($values as $category => $fields) {
            foreach ($fields as $key => $value) {
                $this->_configInterface->saveConfig("axzoom_options/$category/$key", $value, 'default', 0);                
            }
        }
        
        $this->_cacheTypeList->cleanType('config');

        //$this->_cacheTypeList->cleanType('config');        
        //$this->_configInterface->saveConfig('axzoom_options/main/var15', 'sos', 'default', 0);
        //$this->_cacheTypeList->cleanType('config');        
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
    public function aroundSetData(OriginalSection $subject, callable $proceed, array $data, $scope) {


        //$this->_configInterface->saveConfig('axzoom_options/main/var15', 'sos', 'default', 0);


        //error_log("\n" . $data['id'], 3, '_log.txt');

        // This method runs for every section.
        // Add a condition to check for the one to which we're
        // interested in adding groups.

        //error_log("-----SECTION-----: " . $data['id'] . "\n", 3, '_log.txt');


        if($data['id'] == 'axzoom_options') {

            $data['children'] += $this->config;

            //print_r($this->settings);
            //exit;

            /*
            $data['children'] += array(

                'plugin_settings' => array
                    (
                        'id' => 'plugin_settings',
                        'label' => 'Plugin settings for', 
                        'sortOrder' => 40,
                        'showInDefault' => 1,
                        'showInWebsite' => 1,
                        'showInStore' => 1,
                        'children' => array(
                            'var21' => array(
                                'id' => 'var21',
                                'type' => 'textarea',
                                'sortOrder' => 0,
                                'showInDefault' => 1,
                                'showInWebsite' => 0,
                                'showInStore' => 0,
                                'label' => __('Var 2'),
                                'comment' => __('Test AX comment'),
                                '_elementType' => 'field',
                                'path' => 'axzoom_options/main'
                            ))
                    )
                )
            ;
            */

            /*
            $data['children'] += array(
                'TESTGROUP2' => array(
                    'id' => 'TESTGROUP2',
                    'label' => __('Test Az Group 2'),
                    'showInDefault' => 1,
                    'showInWebsite' => 0,
                    'showInStore' => 0,
                    'sortOrder' => 0,
                    'children' => array(
                        'var2' => array(
                            'id' => 'var2',
                            'type' => 'textarea',
                            'sortOrder' => 0,
                            'showInDefault' => 1,
                            'showInWebsite' => 0,
                            'showInStore' => 0,
                            'label' => __('Var 2'),
                            'comment' => __('Test AX comment'),
                            '_elementType' => 'field',
                            'path' => 'axzoom_options/main'
                        ),
                        'var3' => array(
                            'id' => 'var3',
                            'type' => 'select',
                            'sortOrder' => 10,
                            'showInDefault' => 1,
                            'showInWebsite' => 0,
                            'showInStore' => 0,
                            'label' => __('Var 3'),
                            'options' => [
                                'option' => array(
                                    ['label' => 'Option1', 'value' => 'option1'],
                                    ['label' => 'Option2', 'value' => 'option2'],
                                    ['label' => 'Option3', 'value' => 'option3'],
                                    ['label' => 'Option4', 'value' => 'option4']
                                )
                            ],
                            'comment' => __('Test AX comment'),
                            '_elementType' => 'field',
                            'path' => 'axzoom_options/main'
                        ),        
                        'var4' => array(
                            'id' => 'var4',
                            'type' => 'text',
                            'sortOrder' => 0,
                            'showInDefault' => 1,
                            'showInWebsite' => 0,
                            'showInStore' => 0,
                            'label' => __('Var 4'),
                            'comment' => __('Test AX comment'),
                            '_elementType' => 'field',
                            'path' => 'axzoom_options/main'
                        ),

                        'var5' => array(
                            'id' => 'var5',
                            'type' => 'select',
                            'source_model' => 'Ax\Zoom\Model\Config\Source\Truefalse',
                            'sortOrder' => 0,
                            'showInDefault' => 1,
                            'showInWebsite' => 0,
                            'showInStore' => 0,
                            'label' => __('Select model'),
                            'comment' => __('Test AX comment'),
                            '_elementType' => 'field',
                            'path' => 'axzoom_options/main'
                        )
                    )
                )
            );
            */
        }

        $res = $proceed($data, $scope);

        $value = 'aaa000bbb';

        // \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        $v = $this->_scopeConfig->getValue('axzoom_options/main/var2');

        
                


        $this->_configInterface->saveConfig('axzoom_options/main/var17', 'sos 17', 'default', 0);

        return $res;
    }
}