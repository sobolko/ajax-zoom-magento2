<?php
/**
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/lic.php
*  Copyright: Copyright (c) 2010-2018 Vadim Jacobi
*  License Agreement: http://www.ajax-zoom.com/index.php?cid=download
*  Version: 1.4.1
*  Date: 2018-03-26
*  Review: 2018-03-26
*  URL: http://www.ajax-zoom.com
*  Documentation: http://www.ajax-zoom.com/index.php?cid=modules&module=magento
*
*  @author    AJAX-ZOOM <support@ajax-zoom.com>
*  @copyright 2010-2017 AJAX-ZOOM, Vadim Jacobi
*  @license   http://www.ajax-zoom.com/index.php?cid=download
*/

if (isset($zoom) && isset($zoom['config']) && function_exists('SimpleXML_Load_String')) {
    $obj = include(dirname(dirname(dirname((__FILE__)))).'/app/etc/env.php');
    $db = $obj['db']['connection']['default'];
    error_reporting(0);
    $tmp = array();

    if (function_exists('mysqli_connect')) {
        $mysqli = mysqli_connect(preg_replace('/:[0-9]{1,}$/', '', (string)$db['host']), (string)$db['username'], (string)$db['password'], (string)$db['dbname']);
        $data_query = mysqli_query($mysqli, "SELECT `value` FROM `" . (string)$obj['db']['table_prefix'] . "core_config_data` WHERE `path` = 'axzoom_options/license/lic'");
        $data = mysqli_fetch_array($data_query);
        $tmp = json_decode($data['value'], true);
        mysqli_close($mysqli);
    } elseif (function_exists('mysql_connect')) {
        $db_connect = mysql_connect(preg_replace('/:[0-9]{1,}$/', '', (string)$db['host']), (string)$db['username'], (string)$db['password']);
        $db = mysql_select_db((string)$db['dbname'], $db_connect);
        $data_query = mysql_query("SELECT `value` FROM `" . (string)$obj['db']['table_prefix'] . "core_config_data` WHERE `path` = 'axzoom_options/license/lic'");
        $data = mysql_fetch_array($data_query);
        $tmp = json_decode($data['value'], true);
        mysql_close($db_connect);
    }

    if (!empty($tmp)) {
        foreach ($tmp as $key => $l) {
            $zoom['config']['licenses'][$l['domain']] = array(
                'licenceType' => $l['type'],
                'licenceKey' => $l['license'],
                'error200' => $l['error200'],
                'error300' => $l['error300']
            );
        }
    }

    unset($obj, $tmp);
}
