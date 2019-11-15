<?php
/**
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/preview/hotspoteditor.php
*  Copyright: Copyright (c) 2010-2017 Vadim Jacobi
*  License Agreement: http://www.ajax-zoom.com/index.php?cid=download
*  Version: 1.4.0
*  Date: 2017-12-14
*  Review: 2017-12-14
*  URL: http://www.ajax-zoom.com
*  Documentation: http://www.ajax-zoom.com/index.php?cid=modules&module=magento
*
*  @author    AJAX-ZOOM <support@ajax-zoom.com>
*  @copyright 2010-2017 AJAX-ZOOM, Vadim Jacobi
*  @license   http://www.ajax-zoom.com/index.php?cid=download
*/

use Magento\Framework\App\Bootstrap;
require '../../../app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);
$objectManager = $bootstrap->getObjectManager();
$request = $objectManager->get('Magento\Framework\App\Request\Http');

/* !!! AZ: check if backend user is logged in
$state = $objectManager->get('Magento\Framework\App\State');
$state->setAreaCode(Magento\Framework\App\Area::AREA_FRONTEND);
$authSession = $objectManager->create('Magento\Backend\Model\Auth\Session');
$user = $authSession->getUser();

if($authSession->isLoggedIn()) {
    print 'logged';
}
*/

function printTxt($a)
{
?>
<?=$a?>
<?php
}

/* This is not full protection and is not ment to be full protection */
/* !!! AZ: check if backend user is logged in
$sess = false;

// $sess_id = isset($_COOKIE['adminhtml']) ? $_COOKIE['adminhtml'] : false;
$sess_id = Mage::getModel('core/cookie')->get('adminhtml') ? Mage::getModel('core/cookie')->get('adminhtml') : false;

if ($sess_id) {
    $sess = Mage::getSingleton('core/resource_session')->read($sess_id);
    if (!$sess) {
        Mage::getSingleton('core/session', array('name' => 'adminhtml'))->start();
        $admin_logged_in = Mage::getSingleton('admin/session', array('name' => 'adminhtml'))->isLoggedIn();
        if ($admin_logged_in) {
            $sess = '_Mage_Admin_Model_User';
        }
    }
}

if (!($sess && stristr($sess, 'Mage_Admin_Model_User'))) {
    $error_msg = 'Oops. Something went wrong. 
If you are the admin of this website and you are logged in, please contact AJAX-ZOOM support.';

    Mage::throwException($error_msg);
}
*/


$editor_version = '3.0.4';
$last_updated = '2017-08-26';

/* List of "default" images for hotspots */
$hotspot_images = json_encode(
    array(
    'hotspot64_transparent.png',
    'hotspot64_red.png',
    'hotspot64_green.png',
    'hotspot64_blue.png',
    'hotspot64_yellow.png',
    'hotspot64_ring_red.png',
    'hotspot64_ring_green.png',
    'hotspot64_ring_blue.png',
    'hotspot64_ring_yellow.png',
    'hotspot64_map_red.png',
    'hotspot64_map_green.png',
    'hotspot64_map_blue.png',
    'hotspot64_map_yellow.png',
    'hotspot64_map_orange.png',
    'hotspot64_flat_red.png',
    'hotspot64_flat_green.png',
    'hotspot64_flat_blue.png',
    'hotspot64_flat_yellow.png',
    'hotspot64_flat_orange.png',
    'hotspot64_flat1_red.png',
    'hotspot64_flat1_green.png',
    'hotspot64_flat1_blue.png',
    'hotspot64_flat1_yellow.png',
    'hotspot64_flat1_orange.png',
    'hotspot64_flat2_red.png',
    'hotspot64_flat2_green.png',
    'hotspot64_flat2_blue.png',
    'hotspot64_flat2_yellow.png',
    'hotspot64_flat2_orange.png',
    'hotspot64_flag_red.png'
    )
);

/* List of "default" CSS classes for hotspots */
$hotspot_classes = json_encode(
    array(
    'axZm_cssHotspot',
    'axZm_cssHotspot_red',
    'axZm_cssHotspot_orange',
    'axZm_cssHotspot_blue',
    'axZm_cssHotspot_green',
    'axZm_cssHotspot1',
    'axZm_cssHotspot1_red',
    'axZm_cssHotspot1_orange',
    'axZm_cssHotspot1_blue',
    'axZm_cssHotspot1_green'
    )
);

/* List with additional CSS files */
$css_file_src = array();

/* List with additional JS files */
$js_file_src = array();

/* Set to true to remove certain things not needed if included in shops / cms */
$axzm_cms_mode = true;
$axzm_tpl_mode = false;
$axzm_path = '../axZm/';

$player_responsive = $axzm_cms_mode ? true : false;
$langugaes_array = array();

/*
$store_collection = Mage::getModel('core/store')->getCollection();
foreach ($store_collection as $store) {
    $storelang = Mage::getStoreConfig('general/locale/code', $store->getId());
    array_push($langugaes_array, substr($storelang, 0, 2));
}
*/
// !!! AZ: get real lanuages
$langugaes_array = array('en');

$langugaes_array = array_unique($langugaes_array);
$langugaes_array = json_encode($langugaes_array);

$form_key = $request->getParam('form_key');

/* If used with a CMS / Shop, the dynamically generated url for controller */
if ($request->getParam('3dDir')) {
    $first_load_par = '3dDir='.$request->getParam('3dDir');
    $id_360 = $request->getParam('group');
    $id_360set = $request->getParam('id');

    $first_load_hotspot_json = $request->getParam('url_get')."?id_360set=$id_360set&id_360=$id_360";
    $save_hotspot_json = $request->getParam('url_set')."?id_360set=$id_360set&id_360=$id_360&form_key=$form_key";
} else {
    $first_load_par = 'zoomData='.$request->getParam('zoomData');
    $id_media = $request->getParam('id_media');
    $id_product = $request->getParam('id_product');
    $image_name = $request->getParam('image_name');

    $first_load_hotspot_json = $request->getParam('url_get').'?';
    $first_load_hotspot_json .= "id_media=$id_media&id_product=$id_product&image_name=$image_name&form_key=$form_key";

    $save_hotspot_json = $request->getParam('url_set').'?';
    $save_hotspot_json .= "id_media=$id_media&id_product=$id_product&image_name=$image_name&form_key=$form_key";
}

/* Stop config */
/* Stop config */
/* Stop config */
if (!$axzm_tpl_mode) {
    $descr = 'Flash free online hotspot editor for AJAX-ZOOM 3D/360/2D player, ';
    $descr .= 'useful to define positions of hotspots at each frame (360 or 3D)';
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/">
    <html>
    <head>
    <title>Online hotspot editor 3D/360 and 2D zoom player</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="imagetoolbar" content="no">
    <?php
    if (!$axzm_cms_mode) {
    ?>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="Online hotspot editor 3D/360 and 2D zoom player"/>
    <meta property="og:description" content="<?php printTxt($descr); ?>"/>
    <meta name="description" content="<?php printTxt($descr);?>" />
    <meta property="og:image" content="http://www.ajax-zoom.com/pic/layout/image-zoom_33.jpg"/>
    <?php
    }
    ?>
    <!-- jQuery core -->
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/jquery-1.8.3.min.js"></script>
    <?php
}
?>

<!-- AJAX-ZOOM core -->
<link name="az_editor_css_scripts" href="<?php printTxt($axzm_path);?>axZm.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php printTxt($axzm_path);?>jquery.axZm.js"></script>

<!-- Only needed for the online configurator -->
<link name="az_editor_css_scripts" href="<?php printTxt($axzm_path);?>extensions/jquery.axZm.hotspotEditor.css" 
    type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/JSON/jquery.json-2.3.min.js"></script>
<script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/jquery.scrollTo.min.js"></script>
<script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/js-beautify/beautify-all.min.js"></script>
<script type="text/javascript" src="<?php printTxt($axzm_path);?>extensions/jquery.axZm.hotspotEditor.js"></script>

<!-- Because of ui tabs placed here, only needed for the online configurator ! -->
<link name="az_editor_css_scripts" href="<?php printTxt($axzm_path);?>plugins/jquery.ui/themes/ajax-zoom/jquery-ui.css" 
    type="text/css" rel="stylesheet" />
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>plugins/jquery.ui/js/jquery-ui-1.8.24.custom.min.js"></script>

<!-- Spectrum colorpicker, only needed for the online configurator -->
<link name="az_editor_css_scripts" rel="stylesheet" type="text/css" 
    href="<?php printTxt($axzm_path);?>plugins/spectrum/spectrum.css">
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>plugins/spectrum/spectrum.min.js"></script>

<!-- A small function to add different type of description, not necessarily needed -->
<link name="az_editor_css_scripts" rel="stylesheet" 
    type="text/css" href="<?php printTxt($axzm_path);?>extensions/jquery.axZm.expButton.css">
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>extensions/jquery.axZm.expButton.js"></script>

<!-- Only needed for the click example with fancybox -->
<link name="az_editor_css_scripts" 
    href="<?php printTxt($axzm_path);?>plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.zIndex.css" 
    type="text/css" rel="stylesheet">
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.pack.js"></script>

<!--  AJAX-ZOOM extension to load AJAX-ZOOM into maximized fancybox as iframe from "toolTipHtml" field, 
requires jquery.fancybox-1.3.4.css and jquery.fancybox-1.3.4.js, optional -->
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>extensions/jquery.axZm.openAjaxZoomInFancyBox.js"></script>

<!-- CLEditor - WYSIWYG Editor (external jQuery plugin) -->
<link name="az_editor_css_scripts" rel="stylesheet" 
    type="text/css" href="<?php printTxt($axzm_path);?>plugins/CLEditor/jquery.cleditor.css" />
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>plugins/CLEditor/jquery.cleditor.min.js"></script>
<script type="text/javascript" 
    src="<?php printTxt($axzm_path);?>plugins/CLEditor/jquery.cleditor.table.min.js"></script>

<?php
if (is_array($css_file_src) && !empty($css_file_src)) {
    foreach ($css_file_src as $file) {
        printTxt('<link href="'.$file.'" type="text/css" rel="stylesheet">');
    }
}

if (is_array($js_file_src) && !empty($js_file_src)) {
    foreach ($js_file_src as $file) {
        printTxt('<script type="text/javascript" src="'.$file.'"></script>');
    }
}
?>

<style name="az_editor_css_scripts" type="text/css" id="hs_docu_style">
    .optionsTable {width: 100%; border-spacing: 0; border: 1px solid #AAAAAA; 
    font-family: Tahoma, Geneva, sans-serif; font-size: 10pt;}
    .optionsTable th {background-color: #D4D4D4; padding-top: 10px; padding-bottom: 10px;
    font-size:12pt; font-family: "Comic Sans MS", cursive, sans-serif;}
    .optionsTable td, .optionsTable th{padding-left: 5px; padding-right: 5px;}
    .optionsTable th{white-space: nowrap;}
    .optionsTable td,
    .optionsTable th {vertical-align: top; line-height: 1.2em; text-align: left;
    border-bottom: 1px solid #AAAAAA; padding-top: 3px; padding-bottom: 8px;}
    .optionsTable .subOpt td {border-bottom-width: 0; font-size: 12pt;}
    .optionsTable tr.important {font-weight: bolder;}
    .optionsTable td:nth-child(1) {border-right: #AAA 1px dotted;}
    .optionsTable td:nth-child(2) {border-right: #AAA 1px dotted;}
    .optionsTable ul, .optionsTable ol {padding-left: 14px; margin: 0 0 0 20px;}
    .optionsTable ul {list-style-type: square;}
    .optionsTable li {margin-bottom: 5px; padding-left: 14px; line-height: 0.85em;}
    .optionsTable tr:nth-child(odd) { background-color: #FDFDFD;}
    .optionsTable tr:nth-child(even) { background-color: #FFFFFF;}
    .optionsTable tr:last-child>td { border-bottom: none;}    
    .optionsTable.sub th {padding-top: 3px; padding-bottom: 3px; font-size: 11pt;}
    .optionsTable.sub td {line-height: 1em; padding-bottom: 2px; padding-top: 2px;}
    .optionsTable pre {padding: 0 0 0 20px; margin: 5px 0px 5px 0px; tab-size: 4; -moz-tab-size: 4; 
        line-height: 0.85em;}
    .optionsTable code {font-size: 9pt; font-family: monospace;}
    .optionsTable .mono {font-family: monospace;}
    .optionsSimilarExDescr {position: absolute; background-color: #eee; font-size: 9px; width: 100%;
    pointer-events: none; text-align: center; overflow: hidden;}
    .optionsSimilarExLI {float: left; margin: 0 10px 10px 0; position: relative; border: #aaa 1px solid;}
    .optionsHeader {color: #1a4a7a; font-size: 150%; margin: 0; padding: 20px 0px 5px 0px;}
    .optionsText {}
</style>

<?php
if (!$axzm_cms_mode) {
?>
<!-- Javascript to style the syntax, not needed! -->
<link name="az_editor_css_scripts" rel="stylesheet" 
    href="<?php printTxt($axzm_path);?>plugins/demo/prism/prism.css" type="text/css">
<script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/demo/prism/prism.min.js"></script>

<?php
}
?>

<script type="text/javascript">
jQuery.aZonClick = {};
<?php
printTxt('jQuery.aZhSpotEd.langugaesArray = '.$langugaes_array.'; ');
printTxt('jQuery.aZhSpotEd.playerResponsive = '.($player_responsive ? 'true' : 'false').'; ');
printTxt('jQuery.aZhSpotEd.langVal = {}; ');
printTxt('jQuery.aZhSpotEd.axZmPath = "'.$axzm_path.'"; ');
printTxt('jQuery.aZhSpotEd.highLight = true; ');
printTxt('jQuery.aZhSpotEd.drgbl = true; ');
printTxt('jQuery.aZhSpotEd.hotspotImages = '.$hotspot_images.'; ');
printTxt('jQuery.aZhSpotEd.hotspotClasses = '.$hotspot_classes.'; ');

if ($axzm_cms_mode) {
    printTxt('jQuery.aZhSpotEd.errors = false;');
}
?>
</script>

<?php
if (!$axzm_tpl_mode) {
?>

</head>
<body>

<?php
if (!$axzm_cms_mode) {
    if (file_exists(dirname(__FILE__).'/navi.php')) {
        define('COMMENTS_BOOTSTRAP', true);
        include dirname(__FILE__).'/navi.php';
    }
}
} else {
?>
<script type="text/javascript">
    // Move css to head if template mode
    jQuery('*[name=az_editor_css_scripts]').appendTo('head');
</script>
<?php
}
?>

<div id="outerWrap" style="width: 722px; margin: 0 auto;">
    <div id="playerWrap" style="width: 722px;">
        <?php
        if (!$axzm_cms_mode) {
        ?>
        <h2>Create interactive 2D/ 360°/ 3D product presentations / product catalogues 
        with clickable hotspots and hotspot areas using high or ultra resolution images!
        </h2>
        <?php
        } else {
        ?>
        <div style="height: 35px;"></div>
        <?php
        }
        ?>
        <div id="playerInnerWrap" style="min-height: 538px;">
            <div id="abc" style="width: 720px;<?php printTxt($player_responsive ? 'height: 720px' : ''); ?>">
                <!-- Content inside target will be removed -->
                <div style="padding: 20px">Loading, please wait...</div>
            </div>
            <div id='testCustomNavi' class="ui-widget-header" style="width: 720px;"></div>
        </div>
        <div id="marginAfter" style="display: none; margin-bottom: 100px;"></div>
    </div>

    <div id="aZhS_tabs" style="margin-top: 20px; margin-bottom: 20px;">
        <ul>
            <li><a href="#aZhS_tabs-8">About</a></li>
            <li><a href="#aZhS_tabs-1">Hotspots</a></li>
            <li><a href="#aZhS_tabs-2">Tooltips / Text</a></li>
            <li><a href="#aZhS_tabs-5">Save / Edit JSON</a></li>
            <?php
            if (!$axzm_cms_mode) {
                printTxt('<li><a href="#aZhS_tabs-6">Load content</a></li>');
            }
            ?>
        </ul>

        <div id="aZhS_tabs-8">

            <div id="aZhS_about" class="hs_subtab">
                <ul>
                    <li><a href="#aZhS_about-1">Introduction</a></li>
                    <?php
                    if (!$axzm_cms_mode) {
                        printTxt('<li><a href="#aZhS_about-2">Code example</a></li>');
                    }
                    ?>
                    <li>
                        <a href="#aZhS_about-3" onclick="jQuery('#docuTable tr').css('backgroundColor', '');">
                            Options / Docu
                        </a>
                    </li>
                </ul>

                <div id="aZhS_about-1" class="hs_subtab">
                    <?php
                    if ($axzm_cms_mode) {
                    ?>
                    <div class="legend" style="line-height: 60%;">How to use hotspot editor <br>
                        <span style="font-size: 50%">[Editor version: <?php printTxt($editor_version); ?>, 
                        date: <?php printTxt($last_updated); ?>]</span>
                    </div>
                    <ol style="margin: 0;">
                        <li style="margin-top: 10px">
                            Read this text below at least once, please!
                        </li>
                        <li style="margin-top: 10px">
                            Hit the 
                            <script type="text/javascript">
                                jQuery.aZonClick.JjTfsNUdUfkH = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-1');
                                    jQuery('#aZhS_hotspots').tabs('select','#aZhS_hotspots-1');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.JjTfsNUdUfkH()">
                                Hotspots -> New Hotspot
                            </a> tab or 
                            <img src="<?php printTxt($axzm_path);?>icons/default/button_iPad_tag.png" 
                                width="25" style="vertical-align: middle; margin: 2px;">
                            button, define a new hotspots name and push "ADD NEW HOTSPOT" button.
                        </li>
                        <li style="margin-top: 10px">
                            Adjust the hotspot position(s) by simply dragging it to the desired place. 
                            For 360 we found it more convenient to use arrow keys to spin while adjusting 
                            the positions (mouse pointer has to be over the player). 
                            For precise positioning enlarge to the max zoom level! 
                            For moving by one pixel in any direction you can 
                            also use numbers block on your keyboard. 
                            You might also find it easier to roughly set the positions in 
                            <script type="text/javascript">
                                jQuery.aZonClick.RzPMTyBnezEP = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-1'); 
                                    jQuery('#aZhS_hotspots').tabs('select','#aZhS_hotspots-3');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.RzPMTyBnezEP()">
                                Hotspots -> Positions
                            </a> tab before actually doing this precisely with zoom in the player.
                        </li>
                        <li>
                            In order to disable the hotspot at a particular frame you can right click on it. 
                            You can also hit the 
                            <img src="<?php printTxt($axzm_path);?>icons/default/button_iPad_hsp.png" 
                                width="25" style="vertical-align: middle; margin: 2px;">
                            button to toggle enabling / disabling of the hotspot at current frame. 
                            Same can be achieved by pressing "Insert" / "Delete" buttons on your keyboard, 
                            provided that the mouse pointer is over the player.
                        </li>
                        <li style="margin-top: 10px">
                            You can change the appearance of your hotspot under 
                            <script type="text/javascript">
                                jQuery.aZonClick.srHnzDRQQzAZ = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-1'); 
                                    jQuery('#aZhS_hotspots').tabs('select','#aZhS_hotspots-2');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.srHnzDRQQzAZ()">
                                Hotspots -> Hotspot Appearance</a> 
                            tab now or later. All the settings applied there e.g. 
                            defining a different hotspot image and it's size 
                            can be defined for all hotspots at once.
                        </li>
                        <li style="margin-top: 10px">
                            Define what happens when the user clicks on / 
                            goes with mouse over a particular hotspot. 
                            Select the 
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2');">
                                Tooltips / Text</a> 
                            tab and choose the hotspot you want to edit. 
                        </li>
                        <li style="margin-top: 10px">
                            Under the 
                            <script type="text/javascript">
                                jQuery.aZonClick.BJapBxZrUuqT = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2'); 
                                    jQuery('#aZhS_tooltip').tabs('select','#aZhS_tooltip-1');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.BJapBxZrUuqT()">
                                Tooltips</a> 
                            tab you can define most commonly used options to mark a hotspot: 
                            <ul style="margin-top: 10px;">
                                <li>Alt title: appears when the user hovers a hotspot with the mouse</li>
                                <li>Sticky label: is similar to "alt title" but it is always visible</li>
                                <li>Draft label: a responsive positioned label 
                                    connected with a line to the hotspot
                                </li>
                            </ul>
                        </li>
                        <li style="margin-top: 10px">
                            Under the 
                            <script type="text/javascript">
                                jQuery.aZonClick.KNavUrQXQEtN = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2'); 
                                    jQuery('#aZhS_tooltip').tabs('select','#aZhS_tooltip-4');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.KNavUrQXQEtN()">
                                Popup boxes</a> 
                            tab you can add more text which appears on click / mouseover in: 
                            <ul style="margin-top: 10px;">
                                <li>Default popup: a virtual window similar to Fancybox (it is not Fancybox)</li>
                                <li>Expandable: an alternative virtual window type</li>
                            </ul>
                            
                        </li>
                        <li style="margin-top: 10px">
                            Farther more, under the 
                            <script type="text/javascript">
                                jQuery.aZonClick.NrLFecQSNsXb = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2'); 
                                    jQuery('#aZhS_tooltip').tabs('select','#aZhS_tooltip-3');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.NrLFecQSNsXb()">
                                Link / Events</a> 
                            tab, you can assign a link (like href) or assign any JavaScript 
                            to click, mouseover etc. events to create something special. 
                            An example for click event to open a fancybox is provided. 
                        </li>
                        <li style="margin-top: 10px">
                            Under 
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-5');">
                                Save / Edit JSON</a> 
                            you will see the code which was produced by this editor.  
                            If you understand the structure you could also edit it manually in the textarea 
                            (press "Apply" button to test if it works). 
                            Finally press the "Save into database" button; you should get a confirmation.
                        </li>
                    </ol>

                    <?php
                    }
                    ?>
                    <?php
                    if (!$axzm_cms_mode) {
                    ?>
                    <div class="legend" style="line-height: 60%;">About AJAX-ZOOM hotspot editor <br>
                        <span style="font-size: 50%">[Editor version: <?php printTxt($editor_version);?>, 
                        date: <?php printTxt($last_updated);?>]</span>
                    </div>
                    <p>Flash free online hotspot editor for AJAX-ZOOM 3D/360/2D player. 
                        With the help of this editor you can create multiple hotspots 
                        or rectangle image areas and setup several click / mouseover actions for them 
                        e.g. links, tooltips, popup lightboxes or bind your own JavaScript functions 
                        including AJAX-ZOOM API for several events. 
                        The default example is a 360 degrees image set. You can however 
                        <a href="javascript: void(0)" class="linkShowTab" 
                            onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-6');">
                            load any other images</a>
                        like catalogue pages or photo albums  
                        and create hotspots on single (2D) images as well.
                    </p>
                    <p>As a result the hotspot editor returns a 
                        <a href="javascript: void(0)" class="linkShowTab" 
                            onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-5');">
                            JSON object</a> 

                        with all settings including the positions of the hotspots and / or rectangle image areas. 
                        Among other considerable possibilities this JSON object can be 
                            <script type="text/javascript">
                                jQuery.aZonClick.dTeMHWYRbRWp = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-5'); 
                                    jQuery('#aZhS_save').tabs('select','#aZhS_save-2');
                                }
                            </script>
                        <a href="javascript: void(0)" class="linkShowTab" 
                            onclick="jQuery.aZonClick.dTeMHWYRbRWp()">
                            saved to a JavaScript file</a> 
                        and loaded into any other AJAX-ZOOM example later. 
                    </p>

                    <div class="legend">How does it work?</div>
                    <ol style="margin: 0;">
                        <li>
                            Hit the <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-6');">
                            Load content</a> 
                            tab and follow the instructions in order to change the image(s) 
                            you would like to configure with hotspots. 
                            For testing just leave whatever is loaded on default.
                        </li>
                        <li style="margin-top: 10px">
                            Hit the 
                            <script type="text/javascript">
                                jQuery.aZonClick.fzhqzkfSGcJz = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-1'); 
                                    jQuery('#aZhS_hotspots').tabs('select','#aZhS_hotspots-1');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.fzhqzkfSGcJz()">
                                Hotspots -> New Hotspot</a> 
                            tab and define a new hotspots name. Push "ADD NEW HOTSPOT" button.
                        </li>
                        <li style="margin-top: 10px">
                            Adjust the hotspot position(s) by simply dragging it to the desired place. 
                            For 360 we found it more convenient to use arrow keys to spin while adjusting 
                            the positions (mouse pointer has to be over the player). 
                            For precise positioning enlarge to the max zoom level! 
                            For moving by one pixel in any direction you can also 
                            use numbers block on your keyboard. 
                            You might also find it easier to roughly set the positions in 
                            <script type="text/javascript">
                                jQuery.aZonClick.WcAqkWBctCPt = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-1'); 
                                    jQuery('#aZhS_hotspots').tabs('select','#aZhS_hotspots-3');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.WcAqkWBctCPt()">
                                Hotspots -> Positions</a> 
                            tab before actually doing this precisely with zoom in the player.
                        </li>
                        <li style="margin-top: 10px">
                            You can change the appearance of your hotspot under 
                            <script type="text/javascript">
                                jQuery.aZonClick.CWJSFfhzJLRU = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-1'); 
                                    jQuery('#aZhS_hotspots').tabs('select','#aZhS_hotspots-2');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.CWJSFfhzJLRU()">
                                Hotspots -> Hotspot Appearance</a> 
                            tab now or later. All the settings applied there e.g. 
                            defining a different hotspot image and it's size can be taken over 
                            for all hotspots at once.
                        </li>
                        <li style="margin-top: 10px">Define what happens when the user clicks on / 
                            goes with mouse over a particular hotspot. 
                            Hit the 
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2');">
                                Tooltips / Text</a> 
                            tab and select the hotspot, it turns red. 
                            <script type="text/javascript">
                                jQuery.aZonClick.ChKSRHmSAQqF = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2'); 
                                    jQuery('#aZhS_tooltip').tabs('select','#aZhS_tabs-1');
                                }
                            </script>
                            Under the <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.ChKSRHmSAQqF()">
                                Tooltips</a> 
                            tab you can define most commonly used options for default tooltip which looks similar 
                            to Fancybox (it is not Fancybox). 
                            If you do not like the appearance you can always modify its CSS 
                            (.axZmToolTipInner, .axZmToolTipOuter, .axZmToolTipTitle) or 
                            just set a different class (toolTipTitleCustomClass and toolTipCustomClass options). 
                            As an idea - the content of the tooltip can be iFrame 
                            with any other different content in it. 
                            If nothing helps - you want something completely different, 
                            you can define event actions on your own. 
                            They are saved as strings in the JSON object and will be instantly evaled when loaded.
                        </li>
                        <li style="margin-top: 10px">
                            Fine-tuning: possibly there are additional options which might not be "visualized" 
                            in this hotspot editor release. 
                            Under 
                            <script type="text/javascript">
                                jQuery.aZonClick.SADhxzScfLPE = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-5'); 
                                    jQuery('#aZhS_save').tabs('select','#aZhS_save-1');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.SADhxzScfLPE()">
                                Save / Edit JSON -> Import / Edit</a> 
                            you will see the complete JSON object. 
                            Any option (property, value) can be customized for each hotspot individually. 
                            For a complete description of the options see under 
                            <a href="javascript: void(0)" class="linkShowTab" 
                            onclick="jQuery('#aZhS_about').tabs('select','#aZhS_about-3');">
                            Option / Docu</a> 
                            tab.
                        </li>
                        <li style="margin-top: 10px">
                            After you have created, positioned and configured your hotspots the 
                            JSON should be saved somewhere to be accessed later by the player. 
                            This can be done under 
                            <script type="text/javascript">
                                jQuery.aZonClick.mpMQXxeQAVta = function() {
                                    jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-5'); 
                                    jQuery('#aZhS_save').tabs('select','#aZhS_save-2');
                                }
                            </script>
                            <a href="javascript: void(0)" class="linkShowTab" 
                                onclick="jQuery.aZonClick.mpMQXxeQAVta()">
                                Save / Edit JSON -> Save JSON to JavaScript file</a> 
                            tab. 
                            Since AJAX-ZOOM does not utilize any DB we provide only 
                            saving to JavaScript file as generic way of saving the work. 
                            The file is written into '/pic/hotspotJS/' directory 
                            (which has to be writeable by PHP); 
                            you can change the path to any other directory 
                            by editing '/axZm/saveHotspotJS.php'; 
                            The password for saving is set inside saveHotspotJS.php as well.
                        </li>
                        <li style="margin-top: 10px">
                            The saved JS file is then called over an AJAX-ZOOM callback 
                            when the player is loaded... 
                            <code>jQuery.fn.axZm.loadHotspotsFromJsFile('../pic/hotspotJS/eos_1100D.js');</code> 
                            If you will use a database you can just change the first few lines in this file 
                            to load and POST the JSON. All variables are explained inline. 
                            You can even parse this file as a template... There is no need to change 
                            anything in this file except the upper part with few variables in order to 
                            implement it in any CMS.
                        </li>
                    </ol>

                    <div class="legend">"Clean" examples</div>
                    <p>This file (example33.php) is the actual hotspots editor. 
                        With the help of this editor you can define and edit the hotspots. 
                        It is supposed to be in some restricted area of your page. 
                        For showing the results and integration of the player into your frontend 
                        please use one of the following "clean" examples as the starting point. 
                        Also please be aware of that AJAX-ZOOM is highly configurable so you can change 
                        the look and feel of nearly everything you could think of.
                    </p>

                    <div class="azExample clearfix">
                        <a href="example33_clean.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_33_clean.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        <a href="example33_clean.php">example33_clean.php</a> basically it has the same setup 
                        as this editor but without the toolbar under the player.
                    </div>

                    <div class="azExample clearfix">
                        <a href="example33_responsive.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_33_responsive.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        For responsive integrations please use 
                        <a href="example33_responsive.php">example33_responsive.php</a>
                    </div>

                    <div class="azExample clearfix">
                        <a href="example33_fullscreen.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_33_fullscreen.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        Fullscreen <a href="example33_responsive.php">example33_fullscreen.php</a>
                    </div>

                    <div class="azExample clearfix">
                        <a href="example29.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_29.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        Gallery with mixed content - still images, videos from diverse sources like 
                        Youtube/Vimeo/Dailymotion, documents and 360/3D with or without hotspots.
                    </div>

                    <div class="legend">Alternatives</div>

                    <div class="azExample clearfix">
                        <a href="example35.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_35.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        Sometimes you would want not to put visible hotspots on a spin 
                        but only spin to a certain frame and zoom to the area you want to show. 
                        In this case we suggest you to take a look at example35.php 
                        which is also the main editor for this; there you will find links to 
                        "clean" and responsive implementations. 
                        With this tool you can easily create several crops / thumbnails from 2D images / galleries, 
                        360 spins or 3D multirow which are loaded into AJAX-ZOOM player.
                    </div>

                    <div class="legend">E-Commerce</div>
                    <div class="azExample clearfix">
                        <a href="example32.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_32.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        The basis for ecommerce modules / plugins is mainly 
                        <a href="http://www.ajax-zoom.com/examples/example32.php">
                            example32.php
                        </a>. 
                        It does already support hotspots over options settings. 
                        This editor will be certainly, maybe already is (please check), 
                        integrated into our modules for 
                        <a href="http://www.ajax-zoom.com/index.php?cid=modules&module=magento">Magento</a>, 
                        <a href="http://www.ajax-zoom.com/index.php?cid=modules&module=prestashop">PrestaShop</a>, 
                        <a href="http://www.ajax-zoom.com/index.php?cid=modules&module=opencart">Opencart</a>, 
                        <a href="http://www.ajax-zoom.com/index.php?cid=modules&module=woocommerce">WooCommerce</a>,
                        <a href="http://www.ajax-zoom.com/index.php?cid=modules&module=shopware">Shopware</a> 
                        and other plugins.
                    </div>

                    <div class="legend">Build Applications</div>

                    <p>AJAX-ZOOM offers an 
                        <a href="http://www.ajax-zoom.com/index.php?cid=docs#api_createNewHotspot" 
                            target="_blank">extended API</a> 
                        to handle hotspots in various ways. In fact the entire hotspot editor is built upon this API. 
                        The result of the hotspot configurator is a static configuration saved in a file. 
                        However you can also build applications which will set hotspots dynamically depending 
                        e.g. on users choice.
                    </p>

                    <div class="azExample clearfix">
                        <a href="example12.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_12.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        Tutorial for developers of how to add "tags" to the images and let the users add 
                        title and description. 
                        This is a little different version of the hotspot editor which is kept simple 
                        and is focused only on couple AJAX-ZOOM hotspot features. 
                        The example is not completed in the sense 
                        that it is meant for developers who can complete and adjust for their own needs.
                    </div>

                    <div class="azExample clearfix">
                        <a href="example34.php">
                            <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_34.jpg" width="100" 
                                align="left" class="azExampleImg">
                        </a>
                        <a href="http://www.ajax-zoom.com/examples/example34.php">example34</a> 
                        shows an application, where hotspots are 
                        created dynamically and highlight choosen words in the text (saved as image). 
                    </div>

                    <p>If you would like AJAX-ZOOM team to build an application for you, please 
                        <a href="http://www.ajax-zoom.com/index.php?cid=contact">get in touch</a> with us. 
                    </p>

                    <div class="legend">Notes</div>

                    <ul style="margin: 0;">
                        <li style="margin-top: 10px">
                            <img style="position: relative; top: 12px; left: -4px;" 
                                src="<?php printTxt($axzm_path);?>icons/browser_ie.png" width="32" height="32"> 
                            IE8, IE9 - if you want to test hotspots working on older IE, 
                            this will <b>fail</b> on some functionality if you will be using 
                            simulation of older version in new IE developer tools. 
                            Please use real old IE version or at least real IE9 version developer tools to test!
                        </li>

                        <li style="margin-top: 10px">
                            The Options / Docu tab only refers to the possible configurations of the hotspots 
                            and not the AJAX-ZOOM player. 
                            AJAX-ZOOM player itself can be configured with hundreds of other options 
                                to suit any needs. 
                            See <a href="http://www.ajax-zoom.com/index.php?cid=docs">AJAX-ZOOM documentation</a> 
                            and other 
                            <a href="http://www.ajax-zoom.com/index.php?cid=examples">examples</a>.
                        </li>

                        <li style="margin-top: 10px">
                            The file saveHotspotJS.php is very simple and can be ported to any 
                            other language other than PHP within minutes. 
                            Due to possible XSS attacks it is not recommended to have this file publicly 
                            accessible as it is in this example.
                        </li>

                        <li style="margin-top: 10px">
                            For an alternative method of loading hotspots configurations 
                            and positions see also under 
                            <a href="javascript: void(0)" class="linkShowTab" class="linkShowTab" 
                                onclick="jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-6');">
                                Load content</a> 
                            tab.
                        </li>

                        <li style="margin-top: 10px">
                            This hotspot editor extension is fairly new and you can expect it to be 
                            extended in the nearby future. 
                            <a href="http://www.ajax-zoom.com/index.php?cid=contact" target="_blank">
                                As usual, your feedback on usability along with suggestions is much appreciated!
                            </a>
                        </li>

                        <li style="margin-top: 10px">
                            Please note that the position object of each hotspot accepts absolute 
                            positions of the original image (pixel values) or 
                            relative percentage values. So in case you already 
                            have the coordinates they can be easily imported in AJAX-ZOOM JSON object.
                        </li>
                    </ul>

                    <!-- Comments -->
                    <div style="clear: both; margin: 5px 0px 5px 0px;"></div>

                    <?php
                        /* This include is just for the demo, you can remove it */
                        if (file_exists(dirname(__FILE__).'/footer.php')) {
                            include dirname(__FILE__).'/footer.php';
                        }
                    }
                    ?>
                </div>
                <?php
                if (!$axzm_cms_mode) {
                ?>
                <div id="aZhS_about-2" class="hs_subtab">
                    <div class="legend">The final code example</div>

                    <p>The only difference between any other AJAX-ZOOM implementation / example is that 
                        the JavaScript file with JSON data produced by this editor is loaded in AJAX-ZOOM onLoad 
                        callback with jQuery.fn.axZm.loadHotspotsFromJsFile API, see below...
                        Once again, do not try to adapt this editor (example33.php) for final view. 
                        You can use other example33_x examples or basically any other example for this.
                    </p>

                    <div class="legend">JavaScript & CSS files in Head</div>
                    <p>Please note that depending on configured hotspots functionality 
                        other plugins like Fancybox might be needed!
                    </p>
                    <pre><code class="language-markup">
                    <?php
                    $playerhead = '
<!-- Include jQuery core into head section if not already present -->
<script type="text/javascript" src="../axZm/plugins/jquery-1.8.3.min.js"></script>

<!-- AJAX-ZOOM javascript and CSS, adjust the path if needed. Best set absolute path -->
<link  href="../axZm/axZm.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<!-- Only needed for the click example with fancybox -->
<link href="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.zIndex.css" type="text/css" media="screen" rel="stylesheet">
<script type="text/javascript" src="../axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.js"></script>

<!-- A small function to add different type of description, not necessarily needed -->
<link rel="stylesheet" type="text/css" href="../axZm/extensions/jquery.axZm.expButton.css">
<script type="text/javascript" src="../axZm/extensions/jquery.axZm.expButton.min.js"></script>';
                    $playerhead = htmlspecialchars($playerhead);
                    printTxt($playerhead);
                    ?>
                    </code></pre>
                    <div class="legend">HTML markup in body</div>
                    <pre><code class="language-markup">
                    <?php
                    $playerhtml = '
<div class="embed-responsive" style="padding-bottom: 70%;">
    <!-- Placeholder for AJAX-ZOOM player -->
    <div id="axZmPlayerContainer" class="embed-responsive-item" style="max-height: 94vh; max-height: calc(100vh - 50px);">
        Loading, please wait...
    </div>
</div>';
                    $playerhtml = htmlspecialchars(htmlspecialchars);
                    printTxt($playerhtml);
                    ?>
                    </code></pre>

                    <div class="legend">Javascript to init AJAX-ZOOM with hotspots</div>
                    <pre><code class="language-js">
                    <?php
                    $playerjs = '
// Create empty jQuery object
window.ajaxZoom = {}; 

// The ID of the element where ajax-zoom has to be inserted into
ajaxZoom.divID = "axZmPlayerContainer";

// Define the path to the axZm folder, adjust the path if needed!
ajaxZoom.path = "../axZm/"; 

// Define callbacks, for complete list check the docs
ajaxZoom.opt = {
    onBeforeStart: function() {
        // Set backgrounf color, can also be done in css file
        // Set mNavi buttons here if you want
        if (typeof jQuery.axZm.mNavi == "object") {
            jQuery.axZm.mNavi.order = {mPan: 5, mSpin: 10, mHotspots: 5, mSpinPlay: 0};
            jQuery.axZm.mNavi.mouseOver = true;
            /*
            jQuery.axZm.mNavi.customPos = {
                mHotspots: {
                    css: {
                        right: 5, 
                        bottom: 5, 
                        position: "absolute", 
                        zIndex: 123
                    }, 
                    mouseOver: true
                }
            };
            */
        }

        jQuery.axZm.gallerySlideNavi = false;
        jQuery.axZm.spinDemoTime = 2500;
        jQuery.axZm.spinDemoRounds = 999;

        // Define hotspots!
        // jQuery.axZm.hotspots = hotspotsDev;
        // jQuery.fn.initHotspots()
    },
    onLoad: function(){ // onSpinPreloadEnd
        jQuery.axZm.spinReverse = false;

        // Load hotspots over this function... or just define 
        // jQuery.axZm.hotspots here and trigger jQuery.fn.axZm.initHotspots(); after this.
        jQuery.fn.axZm.loadHotspotsFromJsFile("../pic/hotspotJS/bike.js", false);
    }
};

// Define your custom parameter query string
// example=hotSpotEdit has many presets for 360 images
// 3dDir - best of all absolute path to the folder with 360/3D images
ajaxZoom.parameter = "example=spinIpad&3dDir=../pic/hotspots/bike360"; 

jQuery(document).ready(function() {
    // open ajax-zoom not responsive
    // the size depends then on $zoom[\'config\'][\'picDim\']
    /*
    jQuery.fn.axZm.load({
        opt: ajaxZoom.opt,
        path: ajaxZoom.path,
        parameter: ajaxZoom.parameter,
        divID: ajaxZoom.divID
    });
    */

    // Open AJAX-ZOOM responsive
    $.fn.axZm.openResponsive(
        ajaxZoom.path, // Absolute path to AJAX-ZOOM directory, e.g. \'/axZm/\'
        ajaxZoom.parameter, // Defines which images and which options set to load
        ajaxZoom.opt, // callbacks
        ajaxZoom.divID, // target - container ID (default \'window\' - fullscreen)
        false, // apiFullscreen- use browser fullscreen mode if available
        true, // disableEsc - prevent closing with Esc key
        false // postMode - use POST instead of GET
    );
});';
                    $playerjs = htmlspecialchars($playerjs);
                    printTxt($playerjs);
                    ?>
                    </code></pre>
                </div>
                <?php
                }
                ?>
                <div id="aZhS_about-3" class="hs_subtab">
                    <div class="legend">Documentation of the hotspot configuration object options</div>

                    <p>These options below are only about possible hotspot settings 
                        and not the AJAX-ZOOM player itself. 
                        AJAX-ZOOM player can be configured with hundreds of other options to suit anybody's needs. 
                        See <a href="http://www.ajax-zoom.com/index.php?cid=docs" target="_blank">
                            AJAX-ZOOM documentation</a> 
                        and other 
                        <a href="http://www.ajax-zoom.com/index.php?cid=examples" target="_blank">examples</a>. 
                    </p>

                    <div id="hs_docu_parent" class="hs_5-0-5-0"></div>
                </div>
            </div>
        </div>

        <div id="aZhS_tabs-1">
            <div class="ui-widget-header ui-corner-all hotspotselectparent">
                <div class="hs_5-0-10-0">
                    <label>Select hotspot to edit:</label> 
                    <input type="button" id="hotspotSelectorFocus" style="margin-right: 5px" 
                        autocomplete="off" value="Focus+" 
                        title="Zoom & focus to this hotspot in current frame if possible"> 
                    <select id="hotspotSelector" autocomplete="off"></select> &nbsp;&nbsp;
                    <input type="checkbox" id="hotspotSelectorShow" value="1" autocomplete="off" checked> - focus 
                    <div id="hs_mainLang_select_div"></div>
                </div>

                <div class="hs_5-0-10-0">
                    <label>Editor options:</label>
                    <input type="checkbox" id="hotspotSelectorHL" value="1" autocomplete="off" checked>
                         - highlight selected 
                    <input type="checkbox" id="hotspotSelectorDrag" value="1" 
                        autocomplete="off" style="margin-left: 10px;" checked> 
                         - draggable 
                    <input type="checkbox" id="hotspotSelectorHI" value="1" 
                        autocomplete="off" style="margin-left: 10px;"> 
                        - hide inactive 
                </div>
            </div>

            <div id="aZhS_hotspots" class="hs_subtab">
                <ul>
                    <li><a href="#aZhS_hotspots-1">New Hotspot</a></li>
                    <li><a href="#aZhS_hotspots-2">Hotspot Appearance</a></li>
                    <li><a href="#aZhS_hotspots-3">Positions</a></li>
                </ul>

                <div id="aZhS_hotspots-3">
                    <div class="legend">Positions overview (beta)</div>
                    <div class="azMsg clearfix">
                        In this overview you can roughly set the position of the hotspots in each frame 
                        and then set it precisely in the player. Click anywhere on the image to toggle 
                        the hotspot in the particular frame. Click on the frame number to spin to this 
                        frame in the player.
                    </div>
                    <div id="hs_positions_overview"></div>
                </div>

                <div id="aZhS_hotspots-1">
                    <div id="newHotspotParent">
                        <div id="newHotspotMain">
                            <div class="legend">Create new hotspot</div>

                            <div class="azMsg clearfix">
                                Below are only couple settings you can set right away. 
                                For 360/3D after you have created a new hotspot it is recommended 
                                to switch to the "Positions" tab to the right and roughly  
                                set the positions of your newly created hotspot in each frame.
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>New hotspot name:</label>
                                <input type="text" style="width: 300px" value="" id="fieldNewHotSpotName" 
                                    autocomplete="off"> 
                                <input type="checkbox" id="hs_newHotspotCopy" value="1" autocomplete="off"> 
                                    - duplicate current hotspot
                            </div>

                            <div class="hs_5-0-10-0" style="display: none;" id="hs_newHotspotCopy_offset">
                                <label>With positions offset:</label>
                                Left: <input type="text" style="width: 50px" value="10" 
                                    autocomplete="off" id="hs_newHotspotCopy_offsetX" class="txtUnit">% &nbsp;&nbsp;
                                Top: <input type="text" style="width: 50px" value="10" 
                                    autocomplete="off" id="hs_newHotspotCopy_offsetY" class="txtUnit">
                            </div>

                            <div id="hs_newHotspot_settings">
                                <div class="hs_5-0-10-0">
                                    <label>Hotspot type (<a href="javascript: void(0)" 
                                        class="optDescr">shape</a>):
                                    </label>
                                    <input id="hs_hotspotShape_point" name="hotspotShape" 
                                        type="radio" value="point" autocomplete="off" checked> 
                                        - point &nbsp;&nbsp;
                                    <input id="hs_hotspotShape_rect" name="hotspotShape" 
                                        type="radio" value="rect" autocomplete="off"> 
                                        - rectangle
                                </div>

                                <div class="hs_5-0-10-0">
                                    <label>Place on all frames:</label>
                                    <input type="checkbox" id="newHotspotAllFrames" value="1" 
                                        autocomplete="off" checked> 
                                        - makes most sense for 360 / 3D
                                </div>

                                <div class="hs_5-0-10-0">
                                    <label>Auto alt title:</label>
                                    <input type="checkbox" id="newHotspotAltTitle" value="1" 
                                        autocomplete="off" checked> - set 
                                    <a href="javascript: void(0)" class="optDescr">altTitle</a>
                                    same as hotspot name
                                </div>

                                <div class="hs_5-0-10-0">
                                    <label>Size:</label>
                                    Left: <input type="text" style="width: 50px" value="" 
                                        autocomplete="off" id="fieldRectLeft"> 
                                    Top: <input type="text" style="width: 50px" value="" 
                                        autocomplete="off" id="fieldRectTop">
                                    <span id="rectDimFields" style="display: none;">
                                        &nbsp;&nbsp;Width: <input type="text" style="width: 50px" 
                                            autocomplete="off" value="" id="fieldRectWidth">
                                        Height: <input type="text" style="width: 50px" value="" 
                                            autocomplete="off" id="fieldRectHeight"> 
                                    </span>
                                </div>

                                <div class='labelOffset hs_5-0-10-0'>
                                    <div class="azMsg clearfix">
                                        The 'left', 'top', 'width' and 'height' values can be pixel 
                                        values related to original size of the image 
                                        (e.g. left: 1600, top: 900 or left: '1600px', top: '900px') 
                                        or they can be percentage values (e.g. left: '45.75%', top: '37.3%'). 
                                        <span id="rectAddMessage" style="display: none">
                                            For rectangles, if you want to put a full covering overlay, 
                                            set left: 0, top: 0, width: '100%' and height: '100%'
                                        </span>
                                    </div>
                                </div>

                                <div id="rectSettings" style="display: none">
                                    <div class="hs_5-0-10-0">
                                        <label>Text width, height 100% 
                                            (<a href="javascript: void(0)" class="optDescr">hotspotTextFill</a>):
                                        </label>
                                        <input type="checkbox" value="1" id="fieldHotspotTextFill" 
                                        autocomplete="off"> - for more settings see 
                                        <script type="text/javascript">
                                            jQuery.aZonClick.EJuwaBCcDWFV = function() {
                                                jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-2'); 
                                                jQuery('#aZhS_tooltip').tabs('select','#aZhS_tooltip-2');
                                            }
                                        </script>
                                        <a href="javascript: void(0)" class="linkShowTab" 
                                            onclick="jQuery.aZonClick.EJuwaBCcDWFV()">
                                            Tooltips / Text -> For rectangles</a>
                                    </div>
                                    <div class="hs_5-0-10-0">
                                        <label>CSS Class 
                                            (<a href="javascript: void(0)" class="optDescr">hotspotTextClass</a>):
                                        </label>
                                        <input type="text" value="" style="width: 200px" 
                                            id="fieldHotspotTextClass" autocomplete="off">
                                        e.g. <code>axZmHotspotTextCustom</code> (try it)
                                    </div>
                                    <div class="hs_5-0-10-0">
                                        <label>Inline CSS 
                                            (<a href="javascript: void(0)" class="optDescr">hotspotTextCss</a>):
                                        </label>
                                        e.g. <code>{"color":"black","height":"100%","width":"100%"}</code>
                                        <input type="text" value="" style="width: 100%" 
                                            autocomplete="off" id="fieldHotspotTextCss"> 
                                    </div>
                                </div>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>&nbsp;</label>
                                <input type="button" id="hs_add_new_hotspot" 
                                    autocomplete="off" class="btnBig" value="ADD NEW HOTSPOT"> 
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>&nbsp;</label>
                                <!-- Todo: form for all possible options ?-->
                            </div>
                        </div>
                    </div>
                </div>

                <div id="aZhS_hotspots-2">
                    <div id="aZhS_appearance" class="hs_subtab">
                        <ul>
                            <li><a href="#aZhS_appearance-1">Icons / CSS Classes</a></li>
                            <li><a href="#aZhS_appearance-2">Advanced</a></li>
                            <li><a href="#aZhS_appearance-3">Perspective</a></li>
                        </ul>

                        <div id="aZhS_appearance-1">
                            <div class="legend clearfix" style="position: relative;">
                                Icon
                                <div id="hotspotImgPreview"></div>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Icon dimensions:</label>
                                <a href="javascript: void(0)" class="optDescr">width</a>: 
                                <input type="number" step="1" min="0" value="32" style="width: 50px;" 
                                    id="hotspot_width" class="txtUnit" autocomplete="off">px  &nbsp;&nbsp;
                                <a href="javascript: void(0)" class="optDescr">height</a>: 
                                <input type="number" step="1" min="0" value="32" style="width: 50px;" 
                                    id="hotspot_height" class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                <input type="checkbox" id="hs_width_height_same" value="1" 
                                    autocomplete="off" checked="checked"> - same width / height
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Icon image (<a href="javascript: void(0)" 
                                    class="optDescr">hotspotImage</a>):</label>
                                <input type="search" value="hotspot64_green.png" 
                                    style="width: 450px;" id="hotspot_hotspotImage" 
                                    list="hotspot_hotspotImage_list" autocomplete="off">
                            </div>

                            <div class="hs_5-0-10-0">
                                <div id="hs_hotspot_images" class="clearfix"></div>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Icon over (<a href="javascript: void(0)" 
                                    class="optDescr">hotspotImageOnHover</a>):</label>
                                <input type="text" value="" style="width: 450px;" 
                                    id="hotspot_hotspotImageOnHover" 
                                    list="hotspot_hotspotImage_list" 
                                    autocomplete="off">
                            </div>

                            <div class="azMsg azMsgWarning labelMargin clearfix">
                                Please note, that when a specific hotspot is selected in the editor, 
                                the default "hotspotImage" (the red round with plus sign on it) 
                                is applied to highlight the selection.  
                                This happens only in this hotspot configurator and does not affect your final code. 
                                Also the hotspots are only draggable in this configurator. 
                                This behaviour in configurator can be enabled and disabled right below 
                                the hotspot selector at top of this page. 
                            </div>

                            <div class="hs_20-0-10-0">
                                <label> </label>
                                <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                    value="Apply" autocomplete="off">
                            </div>

                            <div class="legend">CSS classes</div>

                            <div class="azMsg clearfix" style="margin-top: 20px">
                                Instead of using png icons for hotspots you could also use CSS class, 
                                e.g. 
                                "<span class="hs_copyText" 
                                    onclick="jQuery('#hotspot_hotspotClass').val('axZm_cssHotspot')">
                                    axZm_cssHotspot
                                </span>" 
                                as an example. You could also use two or more CSS classes, 
                                e.g. 
                                "<span class="hs_copyText" 
                                    onclick="jQuery('#hotspot_hotspotClass').val('axZm_cssHotspot axZm_pulse')">
                                    axZm_cssHotspot axZm_pulse
                                </span>" 

                                will result in a pulsing css hotspot. 
                                You can add your own css file to this editor by adding the link to 
                                $css_file_src array which can be found 
                                at very top inside this php / tpl file... 
                                Also you can add the classes to the list $hotspot_classes 
                                so they visually appear under the form field below.
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Class name (<a href="javascript: void(0)" 
                                    class="optDescr">hotspotClass</a>):</label>
                                <input type="text" value="" style="width: 300px;" 
                                    id="hotspot_hotspotClass" autocomplete="off"> 
                                e.g. 
                                <script type="text/javascript">
                                    jQuery.aZonClick.XeJTSEPaEQLW = function() {
                                        jQuery('#hotspot_hotspotClass')
                                        .val(jQuery('#hotspot_hotspotClass').val() + ' axZm_rotate');
                                    }
                                </script>
                                <span class="hs_copyText" 
                                    onclick="jQuery.aZonClick.XeJTSEPaEQLW()">
                                    axZm_rotate</span> or 
                                    
                                <script type="text/javascript">
                                    jQuery.aZonClick.XYMmKFgjbKSk = function() {
                                        jQuery('#hotspot_hotspotClass')
                                        .val(jQuery('#hotspot_hotspotClass').val() + ' axZm_pulse');
                                    }
                                </script>
                                <span class="hs_copyText" 
                                    onclick="jQuery.aZonClick.XYMmKFgjbKSk()">
                                    axZm_pulse</span>
                            </div>

                            <div class="hs_5-0-10-0">
                                <div id="hs_hotspot_classes" class="clearfix"></div>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Class name on hover (<a href="javascript: void(0)" 
                                    class="optDescr">hotspotClassOnHover</a>):</label>
                                <input type="text" value="" style="width: 300px;" 
                                    id="hotspot_hotspotClassOnHover" autocomplete="off"> 
                                e.g. 
                                <script type="text/javascript">
                                    jQuery.aZonClick.PNAJBVHCGEBA = function() {
                                        jQuery('#hotspot_hotspotClassOnHover')
                                        .val(jQuery('#hotspot_hotspotClassOnHover').val() + ' axZm_rotate_stop')
                                    }
                                </script>
                                <span class="hs_copyText" 
                                    onclick="jQuery.aZonClick.PNAJBVHCGEBA()">
                                    axZm_rotate_stop
                                </span>
                            </div>

                            <div class="hs_20-0-10-0">
                                <label> </label>
                                <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                    value="Apply" autocomplete="off"> &nbsp;&nbsp; 
                                <input type="checkbox" class="btnBig" value="1" 
                                    id="hotspotApplyAll" autocomplete="off"> - apply for all hotspots
                            </div>
                        </div>

                        <div id="aZhS_appearance-2">

                            <div class="legend">Advanced settings</div>

                            <div class="hs_5-0-10-0">
                                <label>Hotspot enabled (<a href="javascript: void(0)" 
                                    class="optDescr">enabled</a>):</label>
                                <input type="checkbox" name="hotspot_enabled" 
                                    id="hotspot_enabled" value="1" autocomplete="off" checked>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Visibility range:</label>
                                <a href="javascript: void(0)" class="optDescr">zoomRangeMin</a>: 
                                <input type="number" step="any" min="0" max="100" value="0" style="width: 50px;" 
                                    id="hotspot_zoomRangeMin" class="txtUnit" autocomplete="off">% &nbsp;&nbsp;
                                <a href="javascript: void(0)" class="optDescr">zoomRangeMax</a>: 
                                <input type="number" step="any" min="0" max="100" value="100" style="width: 50px;" 
                                    id="hotspot_zoomRangeMax" class="txtUnit" autocomplete="off">%
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Gravity (<a href="javascript: void(0)" class="optDescr">gravity</a>):</label> 
                                <select id="hotspot_gravity" autocomplete="off">
                                    <option value="center" selected>center</option>
                                    <option value="topLeft">topLeft</option>
                                    <option value="top">top</option>
                                    <option value="topRight">topRight</option>
                                    <option value="right">right</option>
                                    <option value="bottomRight">bottomRight</option>
                                    <option value="bottom">bottom</option>
                                    <option value="bottomLeft">bottomLeft</option>
                                    <option value="left">left</option>
                                </select> - for landmarks set to "top"
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Offset:</label>
                                <a href="javascript: void(0)" class="optDescr">offsetX</a>:
                                <input type="number" step="any" min="-999" value="0" style="width: 50px;" 
                                    id="hotspot_offsetX" class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                <a href="javascript: void(0)" class="optDescr">offsetY</a>:
                                <input type="number" step="any" min="-999" value="0" style="width: 50px;" 
                                    id="hotspot_offsetY" class="txtUnit" autocomplete="off">px
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Opacity:</label>
                                <a href="javascript: void(0)" class="optDescr">opacity</a>: 
                                <input type="number" min="0" max="1" step="0.01" value="1" style="width: 50px;" 
                                    id="hotspot_opacity" autocomplete="off"> &nbsp;&nbsp;
                                <a href="javascript: void(0)" class="optDescr">opacityOnHover</a>: 
                                <input type="number" min="0" max="1" step="0.01" value="1" style="width: 50px;" 
                                    id="hotspot_opacityOnHover" autocomplete="off">
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Padding (<a href="javascript: void(0)" 
                                    class="optDescr">padding</a>):</label>
                                <input type="number" step="any" value="0" style="width: 50px;" 
                                    id="hotspot_padding" class="txtUnit" autocomplete="off">px
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Layer level (<a href="javascript: void(0)" 
                                    class="optDescr">zIndex</a>):</label>
                                <input type="number" step="1" value="1" style="width: 50px;" 
                                    id="hotspot_zIndex" autocomplete="off"> &nbsp;&nbsp;
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Background color (<a href="javascript: void(0)" 
                                    class="optDescr">backColor</a>):</label>
                                <input type="text" value="none" style="width: 150px;" 
                                    id="hotspot_backColor" autocomplete="off"> &nbsp;&nbsp;
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Border:</label>
                                <a href="javascript: void(0)" class="optDescr">borderWidth</a>: 
                                <input type="number" step="any" min="0" value="0" style="width: 50px;" 
                                    id="hotspot_borderWidth" class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                <a href="javascript: void(0)" class="optDescr">borderColor</a>: 
                                <input type="text" value="red" style="width: 100px;" 
                                    id="hotspot_borderColor" autocomplete="off"> &nbsp;&nbsp;
                                <a href="javascript: void(0)" class="optDescr">borderStyle</a>: 
                                <input type="text" value="solid" style="width: 70px;" 
                                    id="hotspot_borderStyle" autocomplete="off">
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>borderRadius (<a href="javascript: void(0)" 
                                    class="optDescr">borderRadius</a>):</label>
                                <input type="text" value="none" style="width: 150px;" 
                                    id="hotspot_borderRadius" autocomplete="off"> &nbsp;&nbsp;
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Cursor (<a href="javascript: void(0)" 
                                    class="optDescr">cursor</a>):</label>
                                <input type="text" value="pointer" style="width: 450px;" 
                                    id="hotspot_cursor" autocomplete="off">
                            </div>

                            <div class="hs_20-0-10-0">
                                <label> </label>
                                <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                    value="Apply" autocomplete="off"> 
                            </div>
                        </div>

                        <div id="aZhS_appearance-3">

                            <div class="legend">
                                Perspective (experimental)
                            </div>

                            <div class="azMsg clearfix">
                                The experimental "perspective" feature is only available for 360 (not 3D) 
                                and hotspots of type "pointer".
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Key frame (<a href="javascript: void(0)" 
                                    class="optDescr">keyFrame</a>):</label>
                                <input type="text" value="" style="width: 150px;" 
                                    id="hotspot_perspective_keyFrame" autocomplete="off"> 
                                <input type="button" value="Set current" autocomplete="off" 
                                    onclick="jQuery('#hotspot_perspective_keyFrame').val(jQuery.axZm.zoomID)" /> 
                                    &nbsp;&nbsp;
                                <input type="button" value="Disable" autocomplete="off" 
                                    onclick="jQuery('#hotspot_perspective_keyFrame').val('')" />
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Perspective (<a href="javascript: void(0)" 
                                    class="optDescr">perspective</a>):</label>
                                <input type="number" step="any" type="text" 
                                    value="32" style="width: 50px;" 
                                    id="hotspot_perspective_perspective" class="txtUnit" autocomplete="off">px 
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Tilt (<a href="javascript: void(0)" class="optDescr">tilt</a>):</label>
                                <input type="number" step="any" value="0" style="width: 50px;" 
                                    id="hotspot_perspective_tilt" class="txtUnit" autocomplete="off">deg 
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Reverse (<a href="javascript: void(0)" 
                                    class="optDescr">reverse</a>):</label>
                                <input type="checkbox" class="btnBig" value="1" 
                                    id="hotspot_perspective_reverse" autocomplete="off"> 
                            </div>

                            <div class="hs_20-0-10-0">
                                <label> </label>
                                <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                    value="Apply" autocomplete="off"> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="aZhS_tabs-2">
            <div class="ui-widget-header ui-corner-all hotspotselectparent">
                <div class="hs_5-0-10-0">
                    <label>Select hotspot to edit:</label>
                    <input type="button" id="hotspotSelectorFocus2" style="margin-right: 5px" 
                        autocomplete="off" value="Focus+" 
                        title="Zoom & focus to this hotspot in current frame if possible"> 
                    <select id="hotspotSelector2" autocomplete="off"></select> &nbsp;&nbsp;
                    <input type="checkbox" id="hotspotSelectorShow2" value="1" autocomplete="off" checked> - focus
                    <div id="hs_mainLang_select2_div" style="float: right; max-width: 100px;"></div>
                </div>

                <div class="hs_5-0-10-0">
                    <label>Editor options:</label> 
                    <input type="checkbox" id="hotspotSelectorHL2" value="1" 
                        autocomplete="off" checked> - highlight selected
                    <input type="checkbox" id="hotspotSelectorDrag2" value="1" 
                        style="margin-left: 10px;" autocomplete="off" checked> - draggable
                    <input type="checkbox" id="hotspotSelectorHI2" value="1" 
                        autocomplete="off" style="margin-left: 10px;"> - hide inactive 
                </div>
            </div>

            <div id="aZhS_tooltip" class="hs_subtab">
                <ul>
                    <li><a href="#aZhS_tooltip-1">Tooltips</a></li>
                    <li><a href="#aZhS_tooltip-4">Popup Boxes</a></li>
                    <li><a href="#aZhS_tooltip-2">For Rectangles</a></li>
                    <li><a href="#aZhS_tooltip-3">Link / Events</a></li>
                </ul>

                <div id="aZhS_tooltip-4">
                    <div id="aZhS_popup" class="hs_subtab">
                        <ul>
                            <li><a href="#aZhS_tooltips-3">Default "Popup"</a></li>
                            <li><a href="#aZhS_tooltips-4">Expandable</a></li>
                        </ul>
                        <!-- Default popup contents  -->
                        <div id="aZhS_tooltips-3">
                            <div id="aZhS_default_popup" class="hs_subtab">
                                <ul>
                                    <li><a href="#aZhS_default_popup-1">Contents</a></li>
                                    <li><a href="#aZhS_default_popup-2">Size & Look</a></li>
                                    <li><a href="#aZhS_default_popup-3">Close Icon & Overlay</a></li>
                                </ul>
                                <div id="aZhS_default_popup-1">
                                    <div class="legend">Default popup - contents</div>
                                    <div class="azMsg clearfix">
                                            <div class="editor_screenshot_default_popup"></div>
                                            If you want to show some sort of a virtual window with contents in it 
                                            you could use and configure something similar to "Fancybox" here. 
                                            If you do not like the appearance you can always modify its CSS 
                                            (.axZmToolTipInner, .axZmToolTipOuter, .axZmToolTipTitle) or 
                                            just set a different class 
                                            (toolTipTitleCustomClass and toolTipCustomClass options).
                                            An alternative can be found in the next tab -> "Expandable". 
                                            If you do not like these both built in ways, you could always 
                                            use your own method by defining your custom click event 
                                            under Link / Event tab above!
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Title (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipTitle</a>):</label> 
                                        <input type="text" value="" 
                                            id="hotspot_toolTipTitle" class="inputWithLang" autocomplete="off">
                                        <div id="hotspot_toolTipTitle_divLang" class="divLang"></div>
                                    </div>

                                    <div style="clear: both; margin: 5px 0px 20px 0px;">
                                        <label>Description (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipHtml</a>):</label>
                                        <div id="hotspot_toolTipHtml_parent">
                                            <textarea id="hotspot_toolTipHtml" 
                                                style="height: 250px; width: 100%;" autocomplete="off">
                                            </textarea>
                                        </div>
                                        <div style="text-align: right">
                                            <input type="button" value="WYSIWYG" style="margin-right: 10px;" 
                                                onclick="jQuery.aZhSpotEd.toggleWYSIWYG('toolTipHtml')" 
                                                autocomplete="off"> 
                                            <div id="hotspot_toolTipHtml_divLang" class="divLang"></div>
                                        </div>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Dynamic content (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipAjaxUrl</a>):</label> 
                                        <input type="text" value="" id="hotspot_toolTipAjaxUrl" 
                                            class="inputWithLang" autocomplete="off">
                                        <div id="hotspot_toolTipAjaxUrl_divLang" class="divLang"></div>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>

                                <div id="aZhS_default_popup-2">
                                    <div class="legend">Default popup - size and look</div>
                                    <div class="hs_5-0-10-0">
                                        <label>Dimensions:</label>
                                        Width (<a href="javascript: void(0)" class="optDescr">toolTipWidth</a>): 
                                        <input type="number" step="any" value="250" style="width: 50px;" 
                                            id="hotspot_toolTipWidth" class="txtUnit" autocomplete="off">px 
                                            &nbsp;&nbsp;
                                        Height (<a href="javascript: void(0)" class="optDescr">toolTipHeight</a>): 
                                        <input type="number" step="any" value="120" style="width: 50px;" 
                                            id="hotspot_toolTipHeight" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Gravity (<a href="javascript: void(0)" class="optDescr">
                                            toolTipGravity</a>):
                                        </label> 
                                        <select id="hotspot_toolTipGravity" autocomplete="off">
                                            <option value="hover" selected>hover</option>
                                            <option value="fullsize">fullsize</option>
                                            <option value="fullscreen">fullscreen</option>
                                            <option value="topLeft">topLeft</option>
                                            <option value="top">top</option>
                                            <option value="topRight">topRight</option>
                                            <option value="right">right</option>
                                            <option value="bottomRight">bottomRight</option>
                                            <option value="bottom">bottom</option>
                                            <option value="bottomLeft">bottomLeft</option>
                                            <option value="left">left</option>
                                        </select> 
                                        &nbsp;<input type="checkbox" value="1" id="hotspot_toolTipGravFixed" 
                                            autocomplete="off"> fixed position 
                                        (<a href="javascript: void(0)" class="optDescr">toolTipGravFixed</a>)
                                        &nbsp;<input type="checkbox" value="1" id="hotspot_toolTipAutoFlip" 
                                            autocomplete="off"> autoflip 
                                        (<a href="javascript: void(0)" class="optDescr">toolTipAutoFlip</a>)
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Tooltip hotspot offset:</label>
                                        Left (<a href="javascript: void(0)" class="optDescr">toolTipAdjustX</a>): 
                                        <input type="number" step="any" value="10" style="width: 50px;" 
                                            id="hotspot_toolTipAdjustX" class="txtUnit" autocomplete="off">px 
                                            &nbsp;&nbsp;
                                        Top (<a href="javascript: void(0)" class="optDescr">toolTipAdjustY</a>): 
                                        <input type="number" step="any" value="10" style="width: 50px;" 
                                            id="hotspot_toolTipAdjustY" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Tooltip edge offset (<a href="javascript: void(0)" class="optDescr">
                                            toolTipFullSizeOffset</a>):
                                        </label>
                                        <input type="number" step="any" value="40" style="width: 50px;" 
                                            id="hotspot_toolTipFullSizeOffset" class="txtUnit" autocomplete="off">
                                            px - from all edges
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Event (<a href="javascript: void(0)" class="optDescr">
                                            toolTipEvent</a>):
                                        </label>
                                        <input name="hotspot_toolTipEvent" id="hotspot_toolTipEvent" 
                                            type="radio" value="click" autocomplete="off" checked> 
                                             - click &nbsp;&nbsp;
                                        <input name="hotspot_toolTipEvent" id="hotspot_toolTipEvent" 
                                            type="radio" value="mouseover" autocomplete="off">
                                             - mouseover &nbsp;&nbsp;
                                        <input type="text" value="1000" style="width: 50px;" 
                                            id="hotspot_toolTipHideTimout" autocomplete="off">
                                             - hide time if mouseover 
                                        (<a href="javascript: void(0)" class="optDescr">toolTipHideTimout</a>)
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Title Class (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipTitleCustomClass</a>):
                                        </label>
                                        <input type="text" value="" style="width: 200px" 
                                            id="hotspot_toolTipTitleCustomClass" autocomplete="off"> 
                                        e.g. <code>axZmToolTipTitleCustom</code> (try it)
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Inner Class (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipCustomClass</a>):
                                        </label>
                                        <input type="text" value="" style="width: 200px" 
                                            id="hotspot_toolTipCustomClass" autocomplete="off"> 
                                        e.g. <code>axZmToolTipInnerCustom</code> (try it)
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Opacity (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipOpacity</a>):
                                        </label>
                                        <input type="text" value="1.0" style="width:50px" 
                                            id="hotspot_toolTipOpacity" autocomplete="off"> 
                                        (use transparent PNG in toolTipCustomClass for only backgound opacity)
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Draggable (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipDraggable</a>):
                                        </label>
                                        <input type="checkbox" value="1" 
                                            id="hotspot_toolTipDraggable" 
                                            name="hotspot_toolTipDraggable"
                                            autocomplete="off"> 
                                        - title needs to be defined too (title div is handle)
                                    </div>

                                    <div class="hs_20-0-10-0">
                                        <label> </label>
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>

                                <div id="aZhS_default_popup-3">
                                    <div class="legend">Default popup - close icon and overlay</div>
                                    <div class="hs_5-0-10-0">
                                        <label>Close icon image (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipCloseIcon</a>):
                                        </label>
                                        <input type="text" value="fancy_closebox.png" 
                                            style="width: 450px" id="hotspot_toolTipCloseIcon" 
                                            name="hotspot_toolTipCloseIcon" autocomplete="off">
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Close icon position (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipCloseIconPosition</a>):
                                        </label>  
                                        <select id="hotspot_toolTipCloseIconPosition" autocomplete="off">
                                            <option value="topRight" selected>topRight</option>
                                            <option value="topLeft">topLeft</option>
                                            <option value="bottomRight">bottomRight</option>
                                            <option value="bottomLeft">bottomLeft</option>
                                        </select>&nbsp;&nbsp;
                                        offset (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipCloseIconOffset</a>): 
                                        <input type="text" value="" style="width: 160px" 
                                            id="hotspot_toolTipCloseIconOffset" autocomplete="off"> 
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Overlay</label>
                                        Show: (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipOverlayShow</a>):
                                        <input type="checkbox" value="1" id="hotspot_toolTipOverlayShow" 
                                            name="hotspot_toolTipOverlayShow" autocomplete="off"> &nbsp;&nbsp;
                                        Close on click (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipOverlayClickClose</a>): 
                                        <input type="checkbox" value="1" id="hotspot_toolTipOverlayClickClose" 
                                            name="hotspot_toolTipOverlayClickClose" autocomplete="off">
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Overlay settings:</label>
                                        Opacity (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipOverlayOpacity</a>): 
                                        <input type="text" value="" style="width: 50px" 
                                            id="hotspot_toolTipOverlayOpacity" autocomplete="off"> 
                                            &nbsp;&nbsp;
                                        Color (<a href="javascript: void(0)" 
                                            class="optDescr">toolTipOverlayColor</a>): 
                                        <input type="text" value="" style="width: 80px" 
                                            id="hotspot_toolTipOverlayColor" autocomplete="off">
                                    </div>

                                    <div class="hs_20-0-10-0">
                                        <label> </label>
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Expandable contents  -->
                        <div id="aZhS_tooltips-4">
                            <div class="legend">Expandable contents</div>

                            <div class="azMsg clearfix">
                                    <div class="editor_screenshot_exp" style="margin-bottom: 2px;"></div>
                                    Here you can enter title and description to show them in a different 
                                    way than Default "Popup" does. 
                                    Besides HTML or your text you could also load external content in iframe! 
                                    The prefix for the source is "iframe:"<br><br>
                                    e.g. to load an external page simply put something like this in the descripion:
                                    <br> 
                                    <span class="hs_copyText">iframe://www.some-domain.com/123.html</span>
                                    <br><br>
                                    To load a YouTube video you could put this 
                                    (replace eLvvPr6WPdg with your video code): <br>
                                    <span class="hs_copyText">
                                        iframe://www.youtube.com/embed/eLvvPr6WPdg?feature=player_detailpage
                                    </span>
                                    <br><br>
                                    To load some dynamic content over AJAX use "ajax:" as prefix, e.g.<br>
                                    <span class="hs_copyText">ajax:/test/some_content_data.php?req=123</span>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Title (<a href="javascript: void(0)" class="optDescr">expTitle</a>):
                                </label> 
                                <input type="text" value="" id="hotspot_expTitle" class="inputWithLang" 
                                    autocomplete="off">
                                <div id="hotspot_expTitle_divLang" class="divLang"></div>
                            </div>

                            <div class="hs_5-0-5-0">
                                <label>Description (<a href="javascript: void(0)" class="optDescr">expHtml</a>):
                                </label>

                                <div id="hotspot_expHtml_parent">
                                    <textarea id="hotspot_expHtml" style="height: 250px; width: 100%;" 
                                    autocomplete="off"></textarea>
                                </div>

                                <div style="text-align: right">
                                    <input type="button" value="WYSIWYG" style="margin-right: 10px;" 
                                        onclick="jQuery.aZhSpotEd.toggleWYSIWYG('expHtml')" autocomplete="off"> 

                                    <div id="hotspot_expHtml_divLang" class="divLang"></div>
                                </div>
                            </div>

                            <div style="clear: both; margin: 5px 0px 20px 0px;">
                                <label>Open fullscreen (window) (<a href="javascript: void(0)" 
                                    class="optDescr">expFullscreen</a>):
                                </label>
                                <input type="checkbox" value="_blank" id="hotspot_expFullscreen" 
                                    autocomplete="off">
                            </div>

                            <div class="hs_5-0-10-0">
                                <input type="button" class="btnBig hs_saveHotspotTooltip" value="Apply" 
                                    autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="aZhS_tooltip-1">
                    <div id="aZhS_tooltips" class="hs_subtab">
                        <ul>
                            <li><a href="#aZhS_tooltips-1">Alt Title</a></li>
                            <li><a href="#aZhS_tooltips-2">Sticky Label</a></li>
                            <li><a href="#aZhS_tooltips-5">Draft Label</a></li>
                        </ul>

                        <!-- Alt title -->
                        <div id="aZhS_tooltips-1">
                            <div class="legend">Alt title</div>

                            <div class="azMsg clearfix">
                                <div class="editor_screenshot_alt"></div> 
                                This is a virtual "alt" title which appears when the user hovers a hotspot 
                                with the mouse pointer. You can style it as according to your needs. 
                                Best assign a custom class to it.
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Alt title 
                                    (<a href="javascript: void(0)" class="optDescr">altTitle</a>):
                                </label> 
                                <input type="text" value="" id="hotspot_altTitle" 
                                    class="inputWithLang" autocomplete="off">
                                <div id="hotspot_altTitle_divLang" class="divLang"></div>
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>CSS Class 
                                    (<a href="javascript: void(0)" class="optDescr">altTitleClass</a>):
                                </label> 
                                <input type="text" value="" id="hotspot_altTitleClass" 
                                    style="width: 350px;" autocomplete="off">
                            </div>

                            <div class="hs_5-0-10-0">
                                <label>Alt title hotspot offset:</label>
                                Left (<a href="javascript: void(0)" class="optDescr">altTitleAdjustX</a>): 
                                <input type="number" step="any" value="20" style="width: 50px;" 
                                    id="hotspot_altTitleAdjustX" class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                Top (<a href="javascript: void(0)" class="optDescr">altTitleAdjustY</a>): 
                                <input type="number" step="any" value="20" style="width: 50px;" 
                                    id="hotspot_altTitleAdjustY" class="txtUnit" autocomplete="off">px
                            </div>

                            <div class="hs_20-0-10-0">
                                <label> </label>
                                <input type="button" class="btnBig hs_saveHotspotTooltip" value="Apply" 
                                    autocomplete="off">
                            </div>
                        </div>

                        <!-- Sticky label -->
                        <div id="aZhS_tooltips-2">
                            <div id="aZhS_sticky_label" class="hs_subtab">
                                <ul>
                                    <li><a href="#aZhS_sticky_label-1">Contents</a></li>
                                    <li><a href="#aZhS_sticky_label-2">Position & Look</a></li>
                                    <li><a href="#aZhS_sticky_label-4">Connecting Line</a></li>
                                    <li><a href="#aZhS_sticky_label-3">Individual Positions</a></li>
                                </ul>

                                <div id="aZhS_sticky_label-1">
                                    <div class="legend">Sticky label - contents and default position</div>
                                    
                                    <div class="azMsg clearfix">
                                            <div class="editor_screenshot_label"></div> 
                                            Sticky label is similar to "Alt title" but it is always present. 
                                            If you set labelGravity option below to "center" it will cover 
                                            the hotspot. In this case you could remove the hotspot image in the 
                                            "Hotspot appearance" tab and use only this sticky label as your hotspot.
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Label title 
                                            (<a href="javascript: void(0)" class="optDescr">labelTitle</a>):
                                        </label> 
                                        <textarea id="hotspot_labelTitle" style="height: 100px; width: 100%;" 
                                            autocomplete="off"></textarea>
                                        <div id="hotspot_labelTitle_divLang" class="divLang"></div>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>

                                <div id="aZhS_sticky_label-2">
                                    <div class="legend">Sticky label - position & look</div>

                                    <div class="hs_5-0-10-0">
                                        <label>Label gravity (<a href="javascript: void(0)" 
                                            class="optDescr">labelGravity</a>):</label> 
                                        <select id="hotspot_labelGravity" autocomplete="off">
                                            <option value="topLeft">topLeft</option>
                                            <option value="topLeftFlag1">topLeftFlag 1</option>
                                            <option value="topLeftFlag2">topLeftFlag 2</option>
                                            <option value="top">top</option>
                                            <option value="topRight">topRight</option>
                                            <option value="topRightFlag1">topRightFlag 1</option>
                                            <option value="topRightFlag2">topRightFlag 2</option>
                                            <option value="right">right</option>
                                            <option value="rightTopFlag1">rightTopFlag 1</option>
                                            <option value="rightTopFlag2">rightTopFlag 2</option>
                                            <option value="rightBottomFlag1">rightBottomFlag 1</option>
                                            <option value="rightBottomFlag2">rightBottomFlag 2</option>
                                            <option value="bottomRight">bottomRight</option>
                                            <option value="bottomRightFlag1">bottomRightFlag 1</option>
                                            <option value="bottomRightFlag2">bottomRightFlag 2</option>
                                            <option value="bottom">bottom</option>
                                            <option value="bottomLeft">bottomLeft</option>
                                            <option value="bottomLeftFlag1">bottomLeftFlag 1</option>
                                            <option value="bottomLeftFlag2">bottomLeftFlag 2</option>
                                            <option value="left">left</option>
                                            <option value="leftTopFlag1">leftTopFlag 1</option>
                                            <option value="leftTopFlag2">leftTopFlag 2</option>
                                            <option value="leftBottomFlag1">leftBottomFlag 1</option>
                                            <option value="leftBottomFlag2">leftBottomFlag 2</option>
                                            <option value="center">center</option>
                                            <option value="direct">direct</option>
                                        </select>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Instant offset 
                                            (<a href="javascript: void(0)" class="optDescr">labelBaseOffset</a>):
                                        </label> 
                                        <input type="number" step="any" value="5" id="hotspot_labelBaseOffset" 
                                            style="width: 50px;" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Offsets: </label> 
                                        Left (<a href="javascript: void(0)" class="optDescr">labelOffsetX</a>): 
                                        <input type="number" step="any" value="0" id="hotspot_labelOffsetX" 
                                            style="width: 50px;" class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                        Top (<a href="javascript: void(0)" class="optDescr">labelOffsetY</a>): 
                                        <input type="number" step="any" value="0" id="hotspot_labelOffsetY" 
                                            style="width: 50px;" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label> </label>
                                        <input type="button" value="Drag & Drop label position" 
                                            id="hs_dragDropLabel"/>
                                    </div>

                                    <div class="hs_5-0-10-0" style="margin-top: -5px">
                                        <label> </label>
                                        <span id="dragDropLabelMsg"></span>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>CSS class 
                                            (<a href="javascript: void(0)" class="optDescr">labelClass</a>):
                                        </label> 
                                        <input type="text" value="" id="hotspot_labelClass" 
                                            style="width: 450px;" autocomplete="off"><br>
                                    </div>

                                    <div style="margin: -9px 0 14px 0">
                                        <label> </label> 
                                        e.g. <code><a href="javascript: void(0)" 
                                        onclick="jQuery('#hotspot_labelClass').val('axZmHotspotLabelFlat')">
                                            axZmHotspotLabelFlat
                                        </a></code>, 
                                        default is <code>axZmHotspotLabel</code>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Opacity 
                                            (<a href="javascript: void(0)" class="optDescr">labelOpacity</a>):
                                        </label> 
                                        <input type="number" min="0" max="1" step="0.01" value="1.0" 
                                            id="hotspot_labelOpacity" style="width: 50px;" autocomplete="off">
                                    </div>

                                    <div class="hs_20-0-10-0">
                                        <label> </label>
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" value="Apply">
                                    </div>
                                </div>

                                <div id="aZhS_sticky_label-4">
                                    <div class="legend">Sticky label - connecting line </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Connecting line 
                                            (<a href="javascript: void(0)" class="optDescr">labelLine</a>):
                                        </label> 
                                        <input type="number" step="1" min="0" value="0" 
                                            id="hotspot_labelLine" style="width: 50px;" class="txtUnit" 
                                            autocomplete="off">px 
                                        (set at least to 1 to enable)
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>
                                            Color (<a href="javascript: void(0)" 
                                                class="optDescr">labelLineColor</a>):
                                        </label> 
                                        <input type="text" value="rgb(255, 0, 0)" 
                                            id="hotspot_labelLineColor" style="width: 50px;" autocomplete="off">
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Connecting point 
                                            (<a href="javascript: void(0)" class="optDescr">labelLinePoint</a>):
                                        </label> 
                                        <select id="hotspot_labelLinePoint" autocomplete="off">
                                            <option value="1">1. Top - Left</option>
                                            <option value="2">2. Top - Center</option>
                                            <option value="3">3. Top - Right</option>
                                            <option value="4">4. Center - Right</option>
                                            <option value="5">5. Bottom - Right</option>
                                            <option value="6">6. Bottom - Center</option>
                                            <option value="7">7. Bottom - Left</option>
                                            <option value="8">8. Center - Left</option>
                                            <option value="9">9. Middle</option>
                                            <option value="10">10. Auto (1 - 8)</option>
                                            <option value="11">11. Auto - center (2, 4, 6, 8)</option>
                                            <option value="12">12. Auto - bottom corners (5, 7)</option>
                                            <option value="13">13. Auto - top corners (1, 3)</option>
                                        </select>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Length adjust 
                                            (<a href="javascript: void(0)" class="optDescr">labelLineAdjust</a>):
                                        </label> 
                                        <input type="number" step="any" value="0" 
                                            id="hotspot_labelLineAdjust" style="width: 50px;" class="txtUnit" 
                                            autocomplete="off">px
                                    </div>

                                    <div class="hs_20-0-10-0">
                                        <label> </label>
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                    
                                </div>

                                <div id="aZhS_sticky_label-3">
                                    <div class="legend">Sticky label - individual positions for 360/3d</div>
                                    
                                    <div class="azMsg clearfix">
                                        Since this "sticky" label is always visible you might want 
                                        to place it differently depending on frame series. 
                                        If so, select<sup>*</sup> frames in question, define the position 
                                        and click "Apply" button. 
                                        For frames where you do not define any custom positions, 
                                        the default one from left tab will be applied. 
                                        If you click on the frame number the player will spin to that frame.
                                        <br><br>
                                        <sup>*</sup> Selecting can be also done as you would do it on desktop 
                                        with Shift, Strl keys and selection...
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Label gravity 
                                            (<a href="javascript: void(0)" class="optDescr">labelGravity</a>):
                                        </label> 
                                        <select id="hotspot_labelGravity_individual" autocomplete="off">
                                            <option value="topLeft">topLeft</option>
                                            <option value="topLeftFlag1">topLeftFlag 1</option>
                                            <option value="topLeftFlag2">topLeftFlag 2</option>
                                            <option value="top">top</option>
                                            <option value="topRight">topRight</option>
                                            <option value="topRightFlag1">topRightFlag 1</option>
                                            <option value="topRightFlag2">topRightFlag 2</option>
                                            <option value="right">right</option>
                                            <option value="rightTopFlag1">rightTopFlag 1</option>
                                            <option value="rightTopFlag2">rightTopFlag 2</option>
                                            <option value="rightBottomFlag1">rightBottomFlag 1</option>
                                            <option value="rightBottomFlag2">rightBottomFlag 2</option>
                                            <option value="bottomRight">bottomRight</option>
                                            <option value="bottomRightFlag1">bottomRightFlag 1</option>
                                            <option value="bottomRightFlag2">bottomRightFlag 2</option>
                                            <option value="bottom">bottom</option>
                                            <option value="bottomLeft">bottomLeft</option>
                                            <option value="bottomLeftFlag1">bottomLeftFlag 1</option>
                                            <option value="bottomLeftFlag2">bottomLeftFlag 2</option>
                                            <option value="left">left</option>
                                            <option value="leftTopFlag1">leftTopFlag 1</option>
                                            <option value="leftTopFlag2">leftTopFlag 2</option>
                                            <option value="leftBottomFlag1">leftBottomFlag 1</option>
                                            <option value="leftBottomFlag2">leftBottomFlag 2</option>
                                            <option value="center">center</option>
                                            <option value="direct">direct</option>
                                        </select>
                                        <input type="button" value="Set same as default" 
                                            id="hs_labelIndSelAsDefault" style="margin-left: 10px" 
                                            class="hs_small_button" autocomplete="off">
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Instant offset 
                                            (<a href="javascript: void(0)" class="optDescr">labelBaseOffset</a>):
                                        </label> 
                                        <input type="number" step="any" value="5" 
                                            id="hotspot_labelBaseOffset_individual" style="width: 50px;" 
                                            class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Offsets: </label> 
                                        Left (<a href="javascript: void(0)" class="optDescr">labelOffsetX</a>): 
                                        <input type="number" step="any" value="0" 
                                            id="hotspot_labelOffsetX_individual" 
                                            style="width: 50px;" class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                        Top (<a href="javascript: void(0)" class="optDescr">labelOffsetY</a>): 
                                        <input type="number" step="any" value="0" 
                                            id="hotspot_labelOffsetY_individual" 
                                            style="width: 50px;" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label> </label>
                                        <input type="button" value="Drag & Drop label position" 
                                            id="hs_dragDropLabel_ind"/>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Connecting point 
                                            (<a href="javascript: void(0)" class="optDescr">labelLinePoint</a>):
                                        </label> 
                                        <select id="hotspot_labelLinePoint_individual" autocomplete="off">
                                            <option value="1">1. Top - Left</option>
                                            <option value="2">2. Top - Center</option>
                                            <option value="3">3. Top - Right</option>
                                            <option value="4">4. Center - Right</option>
                                            <option value="5">5. Bottom - Right</option>
                                            <option value="6">6. Bottom - Center</option>
                                            <option value="7">7. Bottom - Left</option>
                                            <option value="8">8. Center - Left</option>
                                            <option value="9">9. Middle</option>
                                            <option value="10">10. Auto (1 - 8)</option>
                                            <option value="11">11. Auto - center (2, 4, 6, 8)</option>
                                            <option value="12">12. Auto - bottom corners (5, 7)</option>
                                            <option value="13">13. Auto - top corners (1, 3)</option>
                                        </select>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Length adjust 
                                            (<a href="javascript: void(0)" class="optDescr">labelLineAdjust</a>):
                                        </label> 
                                        <input type="number" step="any" value="0" 
                                            id="hotspot_labelLineAdjust_individual" 
                                            style="width: 50px;" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>&nbsp;&nbsp;</label> 
                                        <input type="button" class="btnBig" id="labelIndividualSave" 
                                            value="Apply" autocomplete="off">&nbsp;
                                        <input type="button" class="btnBig" id="labelIndividualDelete" 
                                            value="Delete" autocomplete="off"> 
                                        &nbsp;for selected frames below
                                    </div>

                                    <div id="hs_labelFrames"></div>
                                </div>

                            </div>
                        </div>

                        <!-- Draft label -->
                        <div id="aZhS_tooltips-5">
                            <div id="aZhS_draft_label" class="hs_subtab">
                                <ul>
                                    <li><a href="#aZhS_draft_label-1">Contents & Position</a></li>
                                    <li><a href="#aZhS_draft_label-4">Connecting Line</a></li>
                                    <li><a href="#aZhS_draft_label-2">Label Settings</a></li>
                                    <li><a href="#aZhS_draft_label-3">Individual Positions</a></li>
                                </ul>

                                <div id="aZhS_draft_label-1">
                                    <div class="legend">Draft label - contents and default position</div>

                                    <div class="azMsg clearfix">
                                            <div class="editor_screenshot_draft"></div> 
                                            Draft label is similar to "Sticky label". 
                                            It is connected to the hotspot with a line. 
                                            Unlike "Sticky label", this label is 
                                            positioned not relative to hotspots position but 
                                            absolute to entire canvas. Basically it is positioned 
                                            same way as regular "point" hotspot so it 
                                            stays on same position where it is placed also 
                                            in responsive environments. You can choose 
                                            one position for all frames, 
                                            set a position for a series of frames 
                                            or edit each position in each frame individually. 
                                            The connection line instantly connects the hotpot with the label. 
                                            Once everything is set up you could remove the icon and css 
                                            for the actual hotspot as the connection line will point to it. 
                                            Connection line is compatible with IE < 9. 
                                            <!--
                                            Todo: Make an option to have it 
                                            always visible also on zoom (in viewport).
                                            -->
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Draft label title 
                                            (<a href="javascript: void(0)" class="optDescr">draftTitle</a>):
                                        </label> 
                                        <textarea id="hotspot_draftTitle" style="height: 100px; width: 100%;" 
                                            autocomplete="off"></textarea>
                                        <div id="hotspot_draftTitle_divLang" class="divLang"></div>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Default position: </label> 
                                        Left (<a href="javascript: void(0)" class="optDescr">draftPosLeft</a>): 
                                        <input type="text" id="hotspot_draftPosLeft" 
                                            style="width: 100px;" class="txtUnit" autocomplete="off">% &nbsp;&nbsp;
                                        Top (<a href="javascript: void(0)" class="optDescr">draftPosTop</a>): 
                                        <input type="text" id="hotspot_draftPosTop" 
                                            style="width: 100px;" class="txtUnit" autocomplete="off">%
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label> </label>
                                        <input type="button" value="Drag & Drop Draft label position" 
                                            id="hs_dragDropDraftLabel" autocomplete="off"> &nbsp;&nbsp;
                                    </div>

                                    <div class="hs_5-0-10-0" style="margin-top: -5px">
                                        <label> </label>
                                        <span id="dragDropDraftLabelMsg"></span>
                                    </div>

                                    <div class="hs_20-0-10-0">
                                        <label> </label>
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>

                                <div id="aZhS_draft_label-4">
                                    <div class="legend">Connecting line settings</div>

                                    <div class="hs_5-0-10-0">
                                        <label>Line type 
                                            (<a href="javascript: void(0)" class="optDescr">draftLineType</a>):
                                        </label>
                                        <input name="hotspot_draftLineType" id="hotspot_draftLineType" 
                                            type="radio" value="1" autocomplete="off" 
                                                onclick="jQuery('#hs_draftLineT2_div').css('display', 'none')" 
                                                checked> - straight &nbsp;&nbsp;
                                        <input name="hotspot_draftLineType" id="hotspot_draftLineType" 
                                            type="radio" value="2" autocomplete="off" 
                                                onclick="jQuery('#hs_draftLineT2_div').css('display', 'block')">
                                                - corner &nbsp;&nbsp;
                                    </div>

                                    <div style="display: none;" id="hs_draftLineT2_div">
                                        <div class="hs_5-0-10-0">
                                            <label>Connection type 
                                                (<a href="javascript: void(0)" class="optDescr">draftLineT2c</a>):
                                            </label>
                                            <select id="hotspot_draftLineT2c" autocomplete="off">
                                                <option value="1">1. Longest first</option>
                                                <option value="2">2. Shortest first</option>
                                                <option value="3">3. Horizontal first</option>
                                                <option value="4">4. Vertical first</option>
                                            </select>
                                        </div>

                                        <div class="hs_5-0-10-0">
                                            <label>Line style 
                                                (<a href="javascript: void(0)" class="optDescr">draftLineT2s</a>):
                                            </label>
                                            <select id="hotspot_draftLineT2s" autocomplete="off">
                                                <option value="solid">Solid</option>
                                                <option value="dashed">Dashed</option>
                                                <option value="dotted">Dotted</option>
                                            </select> 
                                        </div>

                                        <div class="hs_5-0-10-0">
                                            <label>Line skew 
                                                (<a href="javascript: void(0)" class="optDescr">draftLineT2skew</a>):
                                            </label>
                                            <input type="number" step="1" min="0" value="0" 
                                                id="hotspot_draftLineT2skew" style="width: 50px;" 
                                                    class="txtUnit" autocomplete="off">deg
                                        </div>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label> </label>
                                        Width (<a href="javascript: void(0)" class="optDescr">draftLine</a>):
                                        <input type="number" step="1" min="1" value="1" 
                                            id="hotspot_draftLine" style="width: 50px;" 
                                            class="txtUnit" autocomplete="off">px &nbsp;&nbsp;
                                        Color (<a href="javascript: void(0)" class="optDescr">draftLineColor</a>): 
                                        <input type="text" value="rgb(255, 0, 0)" 
                                            id="hotspot_draftLineColor" style="width: 50px;" autocomplete="off">
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Connecting line additional CSS class 
                                            (<a href="javascript: void(0)" class="optDescr">draftLineClass</a>):
                                        </label> 
                                        <input type="text" value="" id="hotspot_draftLineClass" 
                                            style="width: 450px;" autocomplete="off"><br>
                                    </div>

                                    <div class="hs_20-0-10-0">
                                        <label> </label>
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>

                                <div id="aZhS_draft_label-2">
                                    <div class="legend">Draft label settings</div>

                                    <div class="hs_5-0-10-0">
                                        <label>Draft label gravity 
                                            (<a href="javascript: void(0)" class="optDescr">draftGravity</a>):
                                        </label> 
                                        <select id="hotspot_draftGravity" autocomplete="off">
                                            <option value="center" selected>center</option>
                                            <option value="topLeft">topLeft</option>
                                            <option value="top">top</option>
                                            <option value="topRight">topRight</option>
                                            <option value="right">right</option>
                                            <option value="bottomRight">bottomRight</option>
                                            <option value="bottom">bottom</option>
                                            <option value="bottomLeft">bottomLeft</option>
                                            <option value="left">left</option>
                                        </select>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Offset:</label>
                                        <a href="javascript: void(0)" class="optDescr">draftOffsetX</a>:
                                        <input type="number" step="any" min="-999" value="0" style="width: 50px;" 
                                            id="hotspot_draftOffsetX" class="txtUnit" autocomplete="off">px 
                                            &nbsp;&nbsp;
                                        <a href="javascript: void(0)" class="optDescr">draftOffsetY</a>:
                                        <input type="number" step="any" min="-999" value="0" style="width: 50px;" 
                                            id="hotspot_draftOffsetY" class="txtUnit" autocomplete="off">px
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Color:</label>
                                        <a href="javascript: void(0)" class="optDescr">draftBorderColor</a>:
                                        <input type="text" value="" id="hotspot_draftBorderColor" 
                                            style="width: 50px;" autocomplete="off"> &nbsp;&nbsp;
                                        <a href="javascript: void(0)" class="optDescr">draftBackColor</a>:
                                        <input type="text" value="" id="hotspot_draftBackColor" 
                                            style="width: 50px;" autocomplete="off"> &nbsp;&nbsp;
                                        <a href="javascript: void(0)" class="optDescr">draftFontColor</a>:
                                        <input type="text" value="" id="hotspot_draftFontColor" 
                                            style="width: 50px;" autocomplete="off"> &nbsp;&nbsp;
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>CSS class 
                                            (<a href="javascript: void(0)" class="optDescr">draftClass</a>):
                                        </label> 
                                        <input type="text" value="" id="hotspot_draftClass" 
                                            style="width: 450px;" autocomplete="off"><br>
                                    </div>
                                    
                                    <div style="margin: -9px 0 14px 0">
                                        <label> </label>  
                                        default is <code>axZmHotspotLabelR</code>
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Trigger click event of the hotspot 
                                            (<a href="javascript: void(0)" class="optDescr">draftTriggerClick</a>):
                                        </label> 
                                        <input type="checkbox" value="" id="hotspot_draftTriggerClick" 
                                            autocomplete="off">
                                    </div>
                                    
                                    <div class="hs_5-0-10-0">
                                        <label>Click event 
                                            (<a href="javascript: void(0)" class="optDescr">draftClick</a>):
                                        </label> 
                                        <textarea id="hotspot_draftClick" value="" 
                                            style="width: 100%; height: 150px" autocomplete="off"></textarea>
                                    </div>
                                    
                                    <div class="hs_20-0-10-0">
                                        <input type="button" class="btnBig hs_saveHotspotTooltip" 
                                            value="Apply" autocomplete="off">
                                    </div>
                                </div>

                                <div id="aZhS_draft_label-3">
                                    <div class="legend">Draft label - individual positions for 360/3d</div>

                                    <div class="azMsg clearfix">
                                        "Draft label" behaves similar to a hotspot of type point. 
                                        The default position can be set in the most left tab. 
                                        If you want to vary the position across different frames, you can do it here. 
                                        Select<sup>*</sup> frames in question - 
                                        thumbs below, define the position and press "Apply" button. 
                                        If you want to have label position relative to hotspot position, 
                                        then use the "batch positioning" functionality below. 
                                        Note, that you have to select frames in question too. 
                                        This relative position will not adjust instantly 
                                        if you move the actual hotspot somewhere else, so make sure that 
                                        the hotspot positions are final or repeat batch positioning 
                                        after you move the hotspot.
                                        <br><br>
                                        <sup>*</sup> Selecting can be also done as you would do it on desktop with 
                                        Shift, Strl keys and selection...
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>Position: <br />
                                            <input type="button" value="Set same as default" 
                                                id="hs_labelDraftIndSelAsDefault" class="hs_small_button" 
                                                autocomplete="off">
                                        </label> 
                                        Left (<a href="javascript: void(0)" class="optDescr">draftPosLeft</a>): 
                                        <input type="text" value="20" id="hotspot_draftPosLeft_individual" 
                                            style="width: 100px;" class="txtUnit" autocomplete="off">% &nbsp;&nbsp;
                                        Top (<a href="javascript: void(0)" class="optDescr">draftPosTop</a>): 
                                        <input type="text" value="10" id="hotspot_draftPosTop_individual" 
                                            style="width: 100px;" class="txtUnit" autocomplete="off">%
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label> </label>
                                        <input type="button" value="Drag & Drop 'Draft label' position" 
                                            id="hs_dragDropDraftLabel_ind" autocomplete="off"> &nbsp;&nbsp;
                                    </div>

                                    <div class="hs_5-0-10-0">
                                        <label>&nbsp;&nbsp;</label> 
                                        <input type="button" class="btnBig" id="labelDraftIndividualSave" 
                                            value="Apply" autocomplete="off">&nbsp;
                                        <input type="button" class="btnBig" id="labelDraftIndividualDelete" 
                                            value="Delete" autocomplete="off"> 
                                        &nbsp;for selected frames below
                                    </div>

                                    <div class="hs_5-0-10-0 labelOffset">
                                        <div class="legend">Batch positioning</div>
                                        <div class="hs_5-0-10-0">
                                            <div class="azMsg clearfix">
                                                Set positions of the below selected frames relative 
                                                to hotspot positions. 
                                                Negative values accepted. Also no value for either 
                                                Left or Top is accepted too! 
                                                This way you can set the positions relative only to 
                                                e.g. Top and leave Left 
                                                as it was defined previously here or by default value 
                                                (e.g. if you set the default position to somewhere at 
                                                right and define only Top offset, 
                                                then the label will "slide" only vertically because 
                                                Left offset does not change). 
                                                Please note that the hotspot positions have to 
                                                be already set precisely!
                                            </div>
                                            <label class="labelSmall">Offset:</label>
                                            Left: 
                                            <input type="text" value="5" id="hs_label_relative_position_left" 
                                                style="width: 80px;" class="txtUnit" autocomplete="off">% &nbsp;&nbsp;
                                            Top: 
                                            <input type="text" value="5" id="hs_label_relative_position_top" 
                                                style="width: 80px;" class="txtUnit" autocomplete="off">%
                                            <div class="hs_5-0-10-0">
                                                <label class="labelSmall">&nbsp;</label> 
                                                <input type="button" id="labelDraftSubmitOffset" 
                                                    value="Batch position">&nbsp;
                                            </div>
                                        </div>
                                    </div>

                                    <div id="hs_labelDraftFrames"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mainly for rectangles -->
                <div id="aZhS_tooltip-2">
                    <div class="legend">Mainly for rectangles</div>

                    <div class="hs_5-0-10-0">
                        <label>Text inside hotspot area 
                            (<a href="javascript: void(0)" class="optDescr">hotspotText</a>):
                        </label> 
                        Do not use " (double quotation marks) in html tags. Use ' instead! 
                        <a href="javascript: void(0)" 
                            onclick="jQuery.aZhSpotEd.setLorem('hotspot_hotspotText')">set Lorem</a>
                        <textarea id="hotspot_hotspotText" 
                            style="height: 250px; width: 100%;" autocomplete="off"></textarea>
                        <div id="hotspot_hotspotText_divLang" class="divLang"></div>
                    </div>

                    <div class="hs_5-0-10-0">
                        <label>Text width, height 100% 
                            (<a href="javascript: void(0)" class="optDescr">hotspotTextFill</a>):
                        </label>
                        <input type="checkbox" value="1" id="hotspot_hotspotTextFill" autocomplete="off">
                    </div>

                    <div class="hs_5-0-10-0">
                        <label>CSS Class 
                            (<a href="javascript: void(0)" class="optDescr">hotspotTextClass</a>):
                        </label>
                        <input type="text" value="" style="width: 200px" id="hotspot_hotspotTextClass" 
                                autocomplete="off">
                        e.g. <code>axZmHotspotTextCustom</code> (try it)
                    </div>

                    <div class="hs_5-0-10-0">
                        <label>Inline CSS 
                            (<a href="javascript: void(0)" class="optDescr">hotspotTextCss</a>):
                        </label>
                        e.g. <code>{"color":"black","height":"100%","width":"100%"}</code>
                        <input type="text" value="" style="width: 100%" id="hotspot_hotspotTextCss" 
                            autocomplete="off"> 
                    </div>

                    <div class="hs_5-0-10-0">
                        <input type="button" class="btnBig hs_saveHotspotTooltip" value="Apply" autocomplete="off">
                    </div>
                </div>

                <!-- Link and other events -->
                <div id="aZhS_tooltip-3">
                    <div class="legend">Link and other events (JavaScript)</div>

                    <div class="azMsg azMsgWarning clearfix">
                        For click, mouseover etc. events - if you are using jQuery functions, do not write 
                        e.g. <span style="color: red;">$</span>.fancybox(...); 
                        write <span style="color: red;">jQuery</span>.fancybox(...); instead!!!
                    </div>

                    <div id="aZhS_events" class="hs_subtab">
                        <ul>
                            <li><a href="#aZhS_events-1" class="hs_subtab_a">Link</a></li>
                            <li><a href="#aZhS_events-2" class="hs_subtab_a">Click</a></li>
                            <li><a href="#aZhS_events-3" class="hs_subtab_a">Mouseover</a></li>
                            <li><a href="#aZhS_events-4" class="hs_subtab_a">Mouseout</a></li>
                            <li><a href="#aZhS_events-5" class="hs_subtab_a">Mouseenter</a></li>
                            <li><a href="#aZhS_events-6" class="hs_subtab_a">Mouseleave</a></li>
                            <li><a href="#aZhS_events-7" class="hs_subtab_a">Mousedown</a></li>
                            <li><a href="#aZhS_events-8" class="hs_subtab_a">Mouseup</a></li>
                            <li><a href="#aZhS_events-9" class="hs_subtab_a">onRender</a></li>
                        </ul>

                        <div id="aZhS_events-9" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>onRender 
                                    (<a href="javascript: void(0)" class="optDescr">onRender</a>):
                                </label>
                                <textarea id="hotspot_onRender" class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-1" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Link 
                                    (<a href="javascript: void(0)" class="optDescr">href</a>):
                                </label>
                                <input type="text" value="" class="inputWithLang" id="hotspot_href" 
                                    autocomplete="off">
                                <div id="hotspot_href_divLang" class="divLang"></div>
                            </div>
                            <div class="hs_5-0-10-0">
                                <label>Link in new window 
                                    (<a href="javascript: void(0)" class="optDescr">hrefTarget</a>):
                                </label>
                                <input type="checkbox" value="_blank" id="hotspot_hrefTarget" autocomplete="off">
                            </div>
                        </div>

                        <div id="aZhS_events-2" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Click event 
                                    (<a href="javascript: void(0)" class="optDescr">click</a>):
                                </label>
                                <div class="azMsg clearfix">
                                    e.g.
                                    <pre style="tab-size: 4; -moz-tab-size: 4; -o-tab-size: 4;">
jQuery.fancybox(
    [{
        'href': '/path/some/image1.jpg',
        'title': 'Description 1 image'
    },{
        'href': '/path/other/image2.jpg',
        'title': 'Description 2 image'
    }], {
    'padding': 0,
    'transitionIn': 'none',
    'transitionOut': 'none',
    'type': 'image',
    'titlePosition': 'over',
    'changeFade': 0
});
                                    </pre>
                                </div>
                                <textarea id="hotspot_click" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-3" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Mouseover event 
                                    (<a href="javascript: void(0)" class="optDescr">mouseover</a>):
                                </label>
                                <textarea id="hotspot_mouseover" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-4" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Mouseout event 
                                    (<a href="javascript: void(0)" class="optDescr">mouseout</a>):
                                </label>
                                <textarea id="hotspot_mouseout" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-5" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Mouseenter event 
                                    (<a href="javascript: void(0)" class="optDescr">mouseenter</a>):
                                </label>
                                <textarea id="hotspot_mouseenter" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-6" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Mouseleave event 
                                    (<a href="javascript: void(0)" class="optDescr">mouseleave</a>):
                                </label>
                                <textarea id="hotspot_mouseleave" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-7" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Mousedown event 
                                    (<a href="javascript: void(0)" class="optDescr">mousedown</a>):
                                </label>
                                <textarea id="hotspot_mousedown" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div id="aZhS_events-8" style="min-height: 300px;">
                            <div class="hs_5-0-10-0">
                                <label>Mouseup event 
                                    (<a href="javascript: void(0)" class="optDescr">mouseup</a>):
                                </label>
                                <textarea id="hotspot_mouseup" 
                                    class="hs_events_txt" autocomplete="off"></textarea>
                            </div>
                        </div>

                        <div class="hs_5-0-10-0">
                            <input type="button" style="margin-left: 5px" 
                                class="btnBig hs_saveHotspotTooltip" value="Apply" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        if (!$axzm_cms_mode) {
        ?>
        <div id="aZhS_tabs-6">
            <div class="legend">Load a different 2D / 360 or 3D content</div>

            <div class="azMsg azMsgWarning clearfix">You do not need to edit html / source code of this file 
                in order to load a different 2D / 360 or 3D content into the editor. 
                Just enter a local path into one of the fields below and press "LOAD" button. 
                Press "GET" button to see what is currently loaded.
            </div>

            <div class="hs_5-0-10-0">
                <label>1. Path for 2D:</label> 
                <input type="text" value="" id="pathToLoad2D" autocomplete="off">  or
            </div>

            <div class="hs_5-0-10-0">
                <label>2. Path for 360 or 3D:</label> 
                <input type="text" value="" id="pathToLoad360" autocomplete="off"> 
            </div>

            <div style="clear: both; margin: 15px 0px 5px 0px;">
                <label>3. Hotspot file path:</label> 
                <input type="text" value="" id="hotspotFileToLoad" autocomplete="off"> (optional)
            </div>

            <div class="hs_5-0-10-0">
                <label> </label>
                <input type="button" value="LOAD" 
                    onclick="jQuery.aZhSpotEd.changeAxZmContentPHP();" autocomplete="off">&nbsp;&nbsp;
                <input type="button" value="GET" 
                    onclick="jQuery.aZhSpotEd.getLoadedParameters();" autocomplete="off">
            </div>

            <div id="pathToParameter"></div>

            <div class="legend">How does it work:</div>
            <div class="hs_5-0-10-0">
                <ol>
                    <li><strong>For 2D</strong> (single image or gallery with more images) 
                        please enter local path(s) to the image(s), e.g. <br>
                        "<code>/pic/zoom/animals/test_animals1.png</code>"<br>
                        or image set with image paths separated with vertical dash e.g.<br>
                        "<code>/pic/zoom/animals/test_animals1.png|/pic/zoom/animals/test_animals2.png</code>"<br> 
                        If you want to load all images from a folder please just enter the path to this folder 
                        e.g. <br>
                        "<code>/pic/zoom/animals</code>"
                    </li>

                    <li style="margin-top: 10px;">
                        <ul>
                            <li style="margin-top: 5px;"><strong>For 360</strong> 
                                (single row 360 object) please enter only the path to the folder 
                                where your 360 images are located e.g. <br>
                                "<code>/pic/zoom3d/Uvex_Occhiali</code>";
                            </li>
                            <li style="margin-top: 5px;"><strong>For 3D</strong> 
                                (multi row turnable object) please enter the path to the folder 
                                where subfolders with each row of 3D are located.<br> 
                                On <a href="http://www.ajax-zoom.com/examples/example33.php" 
                                    target="_blank">http://www.ajax-zoom.com/examples/example33.php</a> 
                                this could be <br>
                                "<code>/pic/zoomVR/nike</code>" <br>
                                (not provided with the download package)
                            </li>
                        </ul>
                    </li>
                    <li style="margin-top: 10px;">
                        <strong>Hotspot file path</strong> 
                        is the path to the file with hotspot configurations and positions, e.g.<br>
                        "<code>/pic/hotspotJS/eos_1100D.js</code>"<br> 
                        You can create such a file in the previous tab "Save / Edit JSON"
                    </li>
                </ol>
            </div>

            <div class="legend">Notes:</div>
            <div class="hs_5-0-10-0">
                <p>When you hit the "LOAD" button the parameters entered are passed to the function 
                    "changeAxZmContentPHP" located in "/axZm/extensions/jquery.axZm.hotspotEditor.js", 
                    which basically just 
                    reloads the player same way it is loaded when the page is opened.
                </p>
                <p>There are also different ways of loading and saving hotspot 
                    configuration parameters besides loading a static JavaScript file. 
                    For example you could point to /some/dyn_language/file.do?hotspotID=123 
                    which would return the JSON object 
                    stored in a database. If you use the API function 
                    <a href="http://www.ajax-zoom.com/index.php?cid=docs#api_loadHotspotsFromJsFile">
                        jQuery.fn.axZm.loadHotspotsFromJsFile
                    </a> 
                    then you have to reference jQuery.axZm.hotspots variable 
                    to the returned JSON object from your /some/dyn_language/file.do 
                    (the return of this file is expected to be JavaScript - without script tags).
                </p> 
                <p>Alternatively define jQuery.axZm.hotspots wherever after AJAX-ZOOM is initialized, e.g. 
                    <a href="http://www.ajax-zoom.com/index.php?cid=docs#onBeforeStart" target="_blank">
                        onBeforeStart
                    </a> 
                    callback (or later at any time) and "manually" init the hotspots with 
                    <a href="http://www.ajax-zoom.com/index.php?cid=docs#api_initHotspots" target="_blank">
                        jQuery.fn.initHotspots()
                    </a>
                </p>
                <p>Taking the above one step farther you can also build applications 
                    which will set hotspots dynamically depending e.g. on users choice or action.  
                    <a href="http://www.ajax-zoom.com/examples/example34.php">example34</a> 
                    shows such an application, where hotspots are 
                    created dynamically and highlight choosen words in the text (saved as image). 
                </p>
            </div>
        </div>
        <?php
        }
        ?>

        <div id="aZhS_tabs-5">
            <?php
            if (!$axzm_cms_mode) {
            ?>
            <div id="aZhS_save" class="hs_subtab">
            <?php
            }
            ?>
                <?php
                if (!$axzm_cms_mode) {
                ?>
                <ul>
                    <li><a href="#aZhS_save-1">Import / Edit</a></li>
                    <li><a href="#aZhS_save-2">Save JSON to JavaScript File</a></li>
                    <!--<li><a href="#aZhS_save-3">Positions only</a></li>-->
                </ul>
                <?php
                }
                ?>
                
                <?php
                if (!$axzm_cms_mode) {
                ?>
                <div id="aZhS_save-1">
                <?php
                }

                if ($axzm_cms_mode) {
                ?>
                    <div class="legend">Edit, apply, save</div>
                <?php
                } else {
                ?>
                    <div class="legend">Import, edit, apply entire JSON for all hotspots manually</div>
                <?php
                }
                ?>
                    <div class="azMsg">
                        The code in the textarea below is what has been produced by this hotspot editor. <br>
                        Here you can adjust it manually if needed. 
                        Do not forget to press "Apply" button after your changes! <br>
                        <input type="button" id="hs_display_docu_button" style="margin-top: 5px;" 
                            value="DISPLAY HOTSPOTS JSON DOCUMENTATION" 
                            onclick="jQuery.aZhSpotEd.displayDocu('left')" autocomplete="off">
                    </div>

                    <div class="hs_5-0-10-0">
                        <label>Import current loaded object:</label> <input type="button" value="Import" 
                            id="hs_jsonImportBtn" autocomplete="off"> &nbsp;
                        <input type="checkbox" id="allHotspotsCodeDefaults" value="1" 
                            autocomplete="off" <?php printTxt($axzm_cms_mode ? '' : 'checked');?>> 
                        - with defaults &nbsp;
                        <input type="checkbox" id="allHotspotsCodeImgNames" value="1" autocomplete="off" checked> 
                        - positions as image names &nbsp;
                        <input type="checkbox" id="allHotspotsCodeFormat" value="1" autocomplete="off"> 
                        - do not format <br /> &nbsp;
                    </div>

                    <div class="hs_5-0-10-0">
                        <label>Search for a word:</label> 
                        <input type="text" id="jsonSearchField" value="" style="width: 300px" autocomplete="off"> 
                        &nbsp;
                        <input type="button" id="jsonSearchFieldSubmit" value="Search" autocomplete="off"> 
                    </div>

                    <div class="hs_5-0-10-0">
                        <label>&nbsp;&nbsp;</label><span id="jsonSearchResults"></span>
                    </div>

                    <div class="hs_5-0-10-0">
                        <div id="scrollToHotspotJSON"></div>
                    </div>

                    <div class="hs_5-0-10-0">
                        <!-- Adjust the path of saveHotspotJS.php if needed -->
                        <form action="<?php printTxt($axzm_path);?>saveHotspotJS.php" id="saveHotspotJS">
                            <div style="height: auto;">
                                <textarea id="allHotspotsCode" 
                                    style="width: 100%; font-size:12px; line-height: 14px; height: 400px;" 
                                    autocomplete="off"></textarea>
                            </div>
                        </form>

                        <div style="margin-top: 5px;"><label>Apply above (changes):</label><br>
                            <div class="buttonWrap" id="applyJSON">
                                <input style="width: 100px;" type="button" value="Apply" 
                                    onclick="jQuery.aZhSpotEd.applyJSON();" autocomplete="off">
                                <?php
                                if ($axzm_cms_mode) {
                                ?>
                                <input type="button" value="Save into database" 
                                    id="btnSaveJSON" autocomplete="off"> 

                                <div id="dialog-saveJSON" title="" style="display: none;"></div>

                                <script type="text/javascript">
console.log(window.FORM_KEY);

                                    var az_jsonSubmitUrl = '<?php printTxt($save_hotspot_json);?>';
                                    var az_jsonSubmitObj = function(){
                                        return {
                                            method: 'POST',
                                            type: 'POST',
                                            url: az_jsonSubmitUrl,
                                            data: {json: jQuery('#allHotspotsCode').val()},
                                            dataType: 'json',
                                            success: function( ret ) {
                                                if ( jQuery.isPlainObject(ret) && ret.status == 1 ) { 
                                                    var retText = '<p>Hotspots have been saved';
                                                    retText += 'and should be visible in Front-End now!';
                                                    if ( ret.statusText ) { 
                                                        retText += ' Status: '+ret.statusText;
                                                    }
                                                    retText += '</p>';
                                                    
                                                    jQuery('#dialog-saveJSON')
                                                    .attr('title', 'JSON saved or updated' )
                                                    .html(retText)
                                                    .dialog({ 
                                                        modal: true,
                                                        buttons: {
                                                            Ok: function() {
                                                                jQuery(this).dialog('close');
                                                            }
                                                        }
                                                    });
                                                }
                                            },
                                            error: function(jqXHR, textStatus, errorThrown) { 
                                                var Msg = '<p>The request to <br>'+az_jsonSubmitUrl+'<br><br>';
                                                Msg += 'returned <b>error '+jqXHR.status+'</b>';
                                                Msg += '<br>('+jqXHR.statusText+')<br><br>';
                                                Msg += 'If you are not sure why';
                                                Msg += ', please contact AJAX-ZOOM support.</p>';

                                                jQuery('#dialog-saveJSON')
                                                .attr('title', 
                                                    '<span style="color: red">Error saving hotspots</span>')
                                                .html(Msg)
                                                .dialog( { 
                                                    modal: true,
                                                    buttons: { 
                                                        Ok: function() { 
                                                            jQuery( this ).dialog( "close" );
                                                        }
                                                    }
                                                });
                                            }
                                        }
                                    };

                                    jQuery('#btnSaveJSON')
                                    .click(function(e) { 
                                        e.preventDefault();
                                        jQuery('#saveWarningImg').remove();
                                        jQuery.ajax(az_jsonSubmitObj());
                                    } );
                                    </script>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="buttonWrapNext">
                                <input type="checkbox" value="1" id="keepDraggable" autocomplete="off"> 
                                    - keep draggable (will not affect final JSON)
                            </div>
                        </div>
                        <div style="height: 30px;"></div>
                    </div>
                <?php
                if (!$axzm_cms_mode) {
                ?>
                </div>
                <?php
                }
                ?>
                <?php
                if (!$axzm_cms_mode) {
                ?>
                <div id="aZhS_save-2">
                    <div class="legend">Save to JS file</div>

                    <div style="margin-top: 10px">
                        <label>Password for saving:</label>
                        <input type="password" id="jsFilePass" value="" autocomplete="off"> 
                        (can be set inside '/axZm/saveHotspotJS.php')
                    </div>

                    <div style="margin-top: 10px">
                        <label>Keep formated:</label>
                        <input type="checkbox" id="jsKeepFormated" value="1" autocomplete="off"> 
                        - keep linebreaks, tab stops etc.
                    </div>

                    <div style="margin-top: 10px">
                        <label>Create backup:</label>
                        <input type="checkbox" id="jsBackUp" value="1" autocomplete="off" checked> 
                        - creates backup of the current js file if present with 
                        a timestamp in file name (recommended)
                    </div>

                    <div style="margin-top: 10px">
                        <label>Import settings:</label>
                        <input type="checkbox" id="jsAutoImport" autocomplete="off" value="1" checked> 
                        - instantly update / import JSON settings before saving
                    </div>

                    <div style="margin-top: 10px">
                        <label>Save hotspot settings to:</label>
                        <input type="text" value="" id="jsFileName" style="width: 400px;" autocomplete="off">.js
                    </div>

                    <div style="margin-top: 10px">
                        <label> </label>
                        <input style="width: 100px;" type="button" value="Save" 
                            onclick="jQuery.aZhSpotEd.saveJSONtoFile();" autocomplete="off"> 
                    </div>

                    <div style="margin: 10px 0px;">
                        <div id="hotspotSaveToJSresults"></div> 
                    </div>

                    <div class="azMsg clearfix">
                        On default all files will be saved into "/pic/hotspotJS" folder. 
                        This saving path is adjustable directly in "/axZm/saveHotspotJS.php" file. 
                        Saving JSON data to a JavaScript file is one of the possibilities 
                        to initialize created hotspots with any other implementation / example 
                        over AJAX-ZOOM "onLoad" callback. See also under 
                        <script type="text/javascript">
                            jQuery.aZonClick.BLbkgKGYbCdL = function() {
                                jQuery('#aZhS_tabs').tabs('select','#aZhS_tabs-8'); 
                                jQuery('#aZhS_about').tabs('select','#aZhS_about-2');
                            }
                        </script>
                        <a href="javascript: void(0)" class="linkShowTab" 
                            onclick="jQuery.aZonClick.BLbkgKGYbCdL()">
                        About -> Code example</a> tab.
                    </div>
                </div>
                <?php
                }
                ?>
            <?php
            if (!$axzm_cms_mode) {
            ?>
            </div>
            <?php
            }
            ?>
        </div>

    </div>

    <div style="clear:both; margin: 5px 0px 5px 0px;"></div>

</div>

<!--  Init AJAX-ZOOM player -->
<script type="text/javascript">    
    // Create empty jQuery object
    window.ajaxZoom = {}; 

    // The ID of the element where ajax-zoom has to be inserted into
    ajaxZoom.divID = "abc";

    // Define the path to the axZm folder, adjust the path if needed!
    ajaxZoom.path = "<?php printTxt($axzm_path);?>"; 

    // Define callbacks, for complete list check the docs
    // These callbacks are for this editor, see e.g. axample33_clean.php for code without hotspot editor code
    ajaxZoom.opt = {
        onLoad: function(){
            jQuery.axZm.spinReverse = false;
            // Load hotspots over this function... 
            // or just define jQuery.axZm.hotspots here and trigger jQuery.fn.axZm.initHotspots(); after this.
            jQuery.fn.axZm.loadHotspotsFromJsFile('<?php printTxt($first_load_hotspot_json);?>', false, function(){
                // This is just for hotspot editor
                if (typeof jQuery.aZhSpotEd !== 'undefined' ){
                    setTimeout(jQuery.aZhSpotEd.updateHotspotSelector, 200);
                    var HotspotJsFile = jQuery.fn.axZm.getHotspotJsFile();

                    if (HotspotJsFile){
                        HotspotJsFile = jQuery.aZhSpotEd.getf('.', jQuery.aZhSpotEd.getl('/', HotspotJsFile));
                        jQuery('#jsFileName').val(HotspotJsFile);
                    }
                }
            });
        }
    };
    
    // Other callbacks for the editor
    jQuery.extend(true, ajaxZoom.opt, jQuery.aZhSpotEd.ajaxZoomHotspotEditorCallbacks);

    // Define your custom parameter query string
    // example=hotSpotEdit has many presets for 360 images
    // 3dDir - best of all absolute path to the folder with 360/3D images
    // By defining the images to load with relative paths (zoomDir=, zoomData= or 3dDir=) 
    // may lead to not showing them under certain conditions. 
    // A simple workaround is to always use absolute paths. 
    // Please avoid using relative paths on productive environments. 
    ajaxZoom.parameter = "example=hotSpotEdit&<?php printTxt($first_load_par);?>";
    ajaxZoom.parameter += "&cmsMode=<?php printTxt($axzm_cms_mode);?>";

    // this is only for responsive editor layout
    window.thisLayoutAdjusted = false;

    var adjustThisLayout = function(){
        var winW = jQuery(window).innerWidth();
        var winH = jQuery(window).innerHeight();

        if (jQuery.aZhSpotEd.playerResponsive){
            if (jQuery('#'+ajaxZoom.divID).height() + 150 > winH){
                jQuery('#playerInnerWrap').css('minHeight', winH - 150);
                jQuery('#'+ajaxZoom.divID).css('height', winH - 150)
            } else if (jQuery('#'+ajaxZoom.divID).height() < 720 && winH < 720 + 150){
                jQuery('#playerInnerWrap').css('minHeight', winH - 150);
                jQuery('#'+ajaxZoom.divID).height(winH - 150)
            } else if (720 + 150 <= winH){
                jQuery('#playerInnerWrap').css('minHeight', 720);
                jQuery('#'+ajaxZoom.divID).css('height', 720)
            }
        }

        if (winW >= 1490){
            jQuery('#playerWrap').css({'float': 'left'});
            jQuery('#aZhS_tabs').css({'float': 'right', marginTop: 35, width: winW - 722 - 50});
            jQuery('#outerWrap').css({margin: '', width: '', paddingLeft: 10, paddingRight: 10});
            jQuery('#marginAfter').css({display: 'block'});
            window.thisLayoutAdjusted = true;
        }else{
            if (window.thisLayoutAdjusted){
                jQuery('#outerWrap').css({margin: '0 auto', width: 722, paddingLeft: '', paddingRight: ''});
                jQuery('#aZhS_tabs').css({'float': '', width: '', marginTop: 20});
                jQuery('#playerWrap').css({'float': ''});
                jQuery('#marginAfter').css({display: 'none'});
                //jQuery('#aZcomments').css({'float': 'left', width: 722})
                window.thisLayoutAdjusted = false;
            }
        }
    };

    if (jQuery.aZhSpotEd.playerResponsive){
        window.fullScreenStartSplash = {'enable': false, 'className': false, 'opacity': 0.75};
        jQuery.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.parameter, ajaxZoom.opt, ajaxZoom.divID, false, true);
    } else {
        // Fire AJAX-ZOOM
        jQuery.fn.axZm.load({
            opt: ajaxZoom.opt,
            path: ajaxZoom.path,
            postMode: false,
            apiFullscreen: false,
            parameter: ajaxZoom.parameter,
            divID: ajaxZoom.divID
        });
    }

    // Adjust layout
    adjustThisLayout();

    jQuery(document).ready(function(){
        adjustThisLayout();
        setTimeout(adjustThisLayout, 1); // repeat once
        jQuery(window).bind('resize', adjustThisLayout);
        // Tabs can change document height
        jQuery('a[href^="#aZhS_"]').bind('click', adjustThisLayout);
    });

</script>

<!-- Hotspots - documentation -->
<table id="docuTable" class="optionsTable" width="100%" style="display: none;">
    <tbody>
        <tr><th width="150" class='opth3'>Value</th>
        <th class='opth3'>Default</th>
        <th class='opth3'>Description</th>
        </tr>

        <tr><td class='optdl' id='hsOpt_shape'>shape</td>
        <td class='optdm'>'point'</td>
        <td class='optdr'>
            Shape of the hotspot. 
            Possible values "point" or "rect". 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_enabled'>enabled</td>
        <td class='optdm'>true</td>
        <td class='optdr'>
            State of defined hotspot. 
            Possible values true and false. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_width'>width</td>
        <td class='optdm'>32</td>
        <td class='optdr'>
            Width of the hotspot image, only applied if shape value is "point". 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_height'>height</td>
        <td class='optdm'>32</td>
        <td class='optdr'>
            Height of the hotspot image, only applied if shape value is "point". 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_gravity'>gravity</td>
        <td class='optdm'>'center'</td>
        <td class='optdr'>
            Hotspot gravity relative to its position. 
            Possible values: 'center', 'topLeft', 'top', 'topRight', 'right', 
            'bottomRight', 'bottom', 'bottomLeft', 'left'. 
            Only applied if shape value is "point". For landmarks set to "top"! 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_offsetX'>offsetX</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Adjustment of hotspots horizontal visual position. 
            Only applied if shape value is "point". 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_offsetY'>offsetY</td>
        <td class='optdm'>0</td><td class='optdr'>
            Adjustment of hotspots vertical visual position. 
            Only applied if shape value is "point". 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_padding'>padding</td>
        <td class='optdm'>0</td><td class='optdr'>
            Padding of the box with hotspot image and/or text. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_opacity'>opacity</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Default opacity, disabled in IE < 9; 
            Type: float &lt;= 1.0
        </td></tr>

        <tr><td class='optdl' id='hsOpt_opacityOnHover'>opacityOnHover</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Opacity on mouse hover, disabled in IE < 9; 
            Type: float &lt;= 1.0
        </td></tr>

        <tr><td class='optdl' id='hsOpt_backColor'>backColor</td>
        <td class='optdm'>'none'</td>
        <td class='optdr'>
            Background color. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_zIndex'>zIndex</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            zIndex of the hotspot. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_borderWidth'>borderWidth</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            CSS border width. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_borderColor'>borderColor</td>
        <td class='optdm'>'red'</td>
        <td class='optdr'>
            CSS border color. Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_borderStyle'>borderStyle</td>
        <td class='optdm'>'solid'</td>
        <td class='optdr'>
            CSS border style, e.g. 'none', 'hidden', 'dotted', 'dashed', 'solid', 
            'double', 'groove', 'ridge', 'inset', 'outset' or combinations of them. 
            Type: string
        </td></tr>
        
        <tr><td class='optdl' id='hsOpt_borderRadius'>borderRadius</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            CSS border radius
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_cursor'>cursor</td>
        <td class='optdm'>'pointer'</td>
        <td class='optdr'>
            Mouse cursor. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_zoomRangeMin'>zoomRangeMin</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Min zoom level for hotspot to be shown. 
            Type: 0 &lt;= integer &lt;= 100
        </td></tr>

        <tr><td class='optdl' id='hsOpt_zoomRangeMax'>zoomRangeMax</td>
        <td class='optdm'>100</td>
        <td class='optdr'>
            Max zoom level for hotspot to be shown. 
            Type: 0 &lt;= integer &lt;= 100
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotImage'>hotspotImage</td>
        <td class='optdm'>'hotspot64_green.png'</td>
        <td class='optdr'>
            PNG image for the hotspot located in /axZm/icons directory, 
            only applied if shape value is "point". 
            Image can be any absolute path with and without http. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotImageOnHover'>hotspotImageOnHover</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            PNG image for the hotspot on mouse hover, only applied if shape value is "point". 
            Image can be any absolute path with and without http. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotClass'>hotspotClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Instead of using png icons for hotspots you could also use CSS class, 
            e.g. "<code>axZm_cssHotspot</code>" as an example. You could also use two CSS classes, 
            e.g. "<code>axZm_cssHotspot axZm_pulse</code>" will result in a pulsing css hotspot. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotClassOnHover'>hotspotClassOnHover</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Additional CSS class(es) added onmouseover. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotText'>hotspotText</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Text puten direct over the hotspot image, 
            can be for example a number if shape value is point; 
            can be also HTML. 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotTextFill'>hotspotTextFill</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            If shape value is "rect" the rectange does not capture mouse events like click. 
            Setting this value to true will set the inner box to 100% height 
            capturing all events except mousescroll for zooming. 
            Any CSS can be overriden with hotspotTextCss option, see below. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotTextClass'>hotspotTextClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Additionally to CSS class ".axZmHotspotText" add on ther CSS class to hotspotText layer. 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotTextCss'>hotspotTextCss</td>
        <td class='optdm'>{}</td>
        <td class='optdr'>
            Additionally to CSS class ".axZmHotspotText" CSS which is added to hotspotText layer. 
            Type: object
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hotspotObjects'>hotspotObjects</td>
        <td class='optdm'>{}</td>
        <td class='optdr'>
            Any number of absolutely positioned layers directly inside the hotspot if shape value is "rect". 
            Type: object
        </td></tr>

        <tr><td class='optdl' id='hsOpt_altTitle'>altTitle</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Show system like tootip by mousehover if main tooltip is triggered on click (toolTipEvent); 
            CSS class: axZmHoverTooltip; 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_altTitleClass'>altTitleClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            CSS class for altTitle instead of axZmHoverTooltip class. 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_altTitleAdjustX'>altTitleAdjustX</td>
        <td class='optdm'>20</td>
        <td class='optdr'>
            Horizontal offset of the altTitle. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_altTitleAdjustY'>altTitleAdjustY</td>
        <td class='optdm'>20</td>
        <td class='optdr'>
            Vertical offset of the altTitle. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelTitle'>labelTitle</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Sticky label (or tooltip) at any position near a hotspot, accepts HTML. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelGravity'>labelGravity</td>
        <td class='optdm'>'left'</td>
        <td class='optdr'>
            Label gravity, possible values: 
            'topLeft', 'topLeftFlag1', 'topLeftFlag2', 'top', 'topRight', 'topRightFlag1', 
            'topRightFlag2', 'right', 'rightTopFlag1', 'rightTopFlag2', 'rightBottomFlag1', 
            'rightBottomFlag2', 'bottomRight', 'bottomRightFlag1', 'bottomRightFlag2', 'bottom', 
            'bottomLeft', 'bottomLeftFlag1', 'bottomLeftFlag2', 'left', 'leftTopFlag1', 
            'leftTopFlag2', 'leftBottomFlag1', 'leftBottomFlag2', 'center'. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelBaseOffset'>labelBaseOffset</td>
        <td class='optdm'>5</td>
        <td class='optdr'>
            Auto offset in all directions. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelOffsetX'>labelOffsetX</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Horizontal offset. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelOffsetY'>labelOffsetY</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Vertical offset. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelClass'>labelClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            CSS class instead of axZmHotspotLabel. 
            Type: false or integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelOpacity'>labelOpacity</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Opacity level. 
            Type: float &lt;= 1.0
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelIndPar'>labelIndPar</td>
        <td class='optdm'>{}</td>
        <td class='optdr'>
            Optinal parameters for label depending on frame number. 
            Type: object
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelLine'>labelLine</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Thickness of the connecting line between the hotspot and sticky label. 
            0 disables the line. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelLineColor'>labelLineColor</td>
        <td class='optdm'>'rgb(255, 0, 0)'</td>
        <td class='optdr'>
            Connecting line color. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelLinePoint'>labelLinePoint</td>
        <td class='optdm'>11</td>
        <td class='optdr'>
            Connecting point at the label. 
            Possible values: 1 - 12;<br>
            1-8: starting from top - left clockwise. <br>
            9: middle. <br>
            10: auto (1 - 8)<br>
            11: auto - center (2, 4, 6, 8)<br>
            12: auto - bottom corners (5, 7)<br>
            13: auto - top corners (1, 3)<br>
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_labelLineAdjust'>labelLineAdjust</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Adjust length of connecting line manually. Negative values accepted. 
            Type: float
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftTitle'>draftTitle</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            "Draft label" content. 
            Type: string, object
        </td></tr>        
        
        <tr><td class='optdl' id='hsOpt_draftPosLeft'>draftPosLeft</td>
        <td class='optdm'>20</td>
        <td class='optdr'>
            Left position of the "draft label" as % value. 
            Type: float
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftPosTop'>draftPosTop</td>
        <td class='optdm'>10</td>
        <td class='optdr'>
            Top position of the "draft label" as % value. 
            Type: float
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftGravity'>draftGravity</td>
        <td class='optdm'>'center'</td>
        <td class='optdr'>
            Label gravity, possible values: 
            'topLeft', 'topLeftFlag1', 'topLeftFlag2', 'top', 'topRight', 'topRightFlag1', 
            'topRightFlag2', 'right', 'rightTopFlag1', 'rightTopFlag2', 'rightBottomFlag1', 
            'rightBottomFlag2', 'bottomRight', 'bottomRightFlag1', 'bottomRightFlag2', 'bottom', 
            'bottomLeft', 'bottomLeftFlag1', 'bottomLeftFlag2', 'left', 'leftTopFlag1', 
            'leftTopFlag2', 'leftBottomFlag1', 'leftBottomFlag2', 'center'. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftOffsetX'>draftOffsetX</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Horizontal label offset. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftOffsetY'>draftOffsetY</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Vertical label offset. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftBorderColor'>draftBorderColor</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Border color of the label. Overwrites css class color if defined. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftBackColor'>draftBackColor</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Background color of the label. Overwrites css class color if defined. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftFontColor'>draftFontColor</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Font color of the label. Overwrites css class color if defined. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftClass'>draftClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Alternative CSS class for "draft label".  
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftTriggerClick'>draftTriggerClick</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Trigger click event defined for the actual hotspot. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftClick'>draftClick</td>
        <td class='optdm'>null</td>
        <td class='optdr'>
            Custom function for click event.  
            Type: function
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftIndPos'>draftIndPos</td>
        <td class='optdm'>{}</td>
        <td class='optdr'>
            Individual positions of the "draft label". 
            Calculated and set by the editor but can be adapted for API if needed. 
            Type: object
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLine'>draftLine</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Width of the connection line between hotspot and the "draft label". 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLineType'>draftLineType</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Draft line type. Possible values: <br>
            1. direct connection<br>
            2. cornered connection out of two lines
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLineT2c'>draftLineT2c</td>
        <td class='optdm'>1</td>
        <td class='optdr'>
            Connection type for the line if "draftLineType" is 2 (cornered connection). 
            Possible values: <br>
            1. Longest first <br>
            2. Shortest first <br>
            3. Horizontal first <br>
            4. Vertical first <br>
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLineT2s'>draftLineT2s</td>
        <td class='optdm'>'solid'</td>
        <td class='optdr'>
            Line style if "draftLineType" is 2 (cornered connection). Possible values: <br>
            'solid', 'dashed' or 'dotted' <br>
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLineT2skew'>draftLineT2skew</td>
        <td class='optdm'>0</td>
        <td class='optdr'>
            Experimental. Line skew as degree value. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLineColor'>draftLineColor</td>
        <td class='optdm'>'rgb(255, 0, 0)'</td>
        <td class='optdr'>
            Color of the "draft line". 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_draftLineClass'>draftLineClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Connecting line additional CSS class. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipTitle'>toolTipTitle</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Title shown in the tooltip; value can be also a function which returns a string or HTML; 
            in case the value is a function the first parameter passed to it 
            is an object with all configs of this hotspot including name. 
            Type: false, string or function
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipHtml'>toolTipHtml</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Text or html inside tooltip, as idea it can be also iframe, 
            e.g. <code>&lt;iframe src="http://www.ebay.de" scrolling="no" width="100%" 
            height="100%" frameborder="0">&lt;/iframe></code>
            value can be also a function which returns a string or HTML; 
            in case the value is a function the first parameter passed to it is an object 
            with all configs of this hotspot including name. 
            Type: false, string or function
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipAjaxUrl'>toolTipAjaxUrl</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Url for toolTipHtml get from AJAX request (not cross site, 
            for cross site use an iframe inside toolTipHtml); 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipWidth'>toolTipWidth</td>
        <td class='optdm'>250</td>
        <td class='optdr'>
            Width of the tooltip, ignored when toolTipGravity is set to fullsize or fullscreen! 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipHeight'>toolTipHeight</td>
        <td class='optdm'>120</td>
        <td class='optdr'>
            Min height of the tooltip, ignored when toolTipGravity is set to fullsize or fullscreen! 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipGravity'>toolTipGravity</td>
        <td class='optdm'>'hover'</td>
        <td class='optdr'>
            Tooltip gravity, possible values: 
            'hover', 'fullsize', 'fullscreen', 'topLeft', 'top', 'topRight', 'right', 
            'bottomRight', 'bottom', 'bottomLeft', 'left'. 
            The difference between 'fullsize' and 'fullscreen' is that 'fullsize' 
            refers to players dimensions, whereas 'fullscreen' to window size. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipGravFixed'>toolTipGravFixed</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Applies fixed position to toolTipGravity except 'fullsize', 'hover' turns into centered position. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipFullSizeOffset'>toolTipFullSizeOffset</td>
        <td class='optdm'>40</td>
        <td class='optdr'>
            toolTipGravity fullsize uses maximal available player / window width and height. 
            This is the margin to the edges if e.g. toolTipGravity is 'fullscreen', 'fullsize' 
            or toolTipGravFixed option is set to true, so the fixed position is relative to the player size. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipTitleCustomClass'>toolTipTitleCustomClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Use specific classname instead of axZmToolTipTitle. 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipCustomClass'>toolTipCustomClass</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Use specific classname instead of axZmToolTipInner. 
            Type: false or string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipAdjustX'>toolTipAdjustX</td>
        <td class='optdm'>10</td>
        <td class='optdr'>
            Horizontal offset. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipAdjustY'>toolTipAdjustY</td>
        <td class='optdm'>10</td>
        <td class='optdr'>
            Vertical offset; 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipAutoFlip'>toolTipAutoFlip</td>
        <td class='optdm'>true</td>
        <td class='optdr'>
            Flip tooltip horizontaly / vertically depending on best fit. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipOpacity'>toolTipOpacity</td>
        <td class='optdm'>1.0</td>
        <td class='optdr'>
            Opacity of the tooltip. 
            Type: float &lt;= 1.0
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipFade'>toolTipFade</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Fade tooltip time in ms. 
            Type: false or integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipEvent'>toolTipEvent</td>
        <td class='optdm'>'click'</td>
        <td class='optdr'>
            'mouseover' or 'click', defaults to 'click' on touch devices. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipEvent'>toolTipClickClose</td>
        <td class='optdm'>true</td>
        <td class='optdr'>
            When clicked on the hotspot with already opened toolTip from the same hotspot, 
            the toolTip will be closed. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipHideTimout'>toolTipHideTimout</td>
        <td class='optdm'>1000</td>
        <td class='optdr'>
            If toolTipEvent is 'mouseover' this setting allows to move 
            the cursor to the tooltip within this time. 
            Type: integer
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipDraggable'>toolTipDraggable</td>
        <td class='optdm'>true</td>
        <td class='optdr'>
            Set tooltip to be draggable. 
            toolTipTitle has to be defined because this is the handle, can be an empty div. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipCloseIcon'>toolTipCloseIcon</td>
        <td class='optdm'>'fancy_closebox.png'</td>
        <td class='optdr'>
            PNG image for close button located in /axZm/icons directory. 
            Shown if toolTipEvent is 'click' and touch devices. Can be absolute image path, also with http; 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipCloseIconPosition'>toolTipCloseIconPosition</td>
        <td class='optdm'>'topRight'</td>
        <td class='optdr'>
            Position of the close icon, possible values are: 'topLeft', 'topRight', 
            'bottomRight' and 'bottomLeft'. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipCloseIconOffset'>toolTipCloseIconOffset</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Offset / position of the close button icon. 
            If false the offset is set instantly. 
            An integer sets depending on toolTipCloseIconPosition - top, bottom 
            or left, right position to this number. 
            If object, e.g. <code>{"right": 20, "top": 0}</code> toolTipCloseIconPosition is ignored. 
            Type: false, integer or object
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipCloseIconMouseOver'>toolTipCloseIconMouseOver</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Show close button also if toolTipEvent is 'mouseover'. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipOverlayShow'>toolTipOverlayShow</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Show overlay when tooltip window pops up. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipOverlayOpacity'>toolTipOverlayOpacity</td>
        <td class='optdm'>0.75</td>
        <td class='optdr'>
            Overlay opacity. 
            Type: float &lt;= 1.0
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipOverlayColor'>toolTipOverlayColor</td>
        <td class='optdm'>'#000000'</td>
        <td class='optdr'>
            Overlay color. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_toolTipOverlayClickClose'>toolTipOverlayClickClose</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Close tooltip by clicking on the overlay. 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_expTitle'>expTitle</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Title for the expandable overlay. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_expHtml'>expHtml</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Besides HTML or your text you could also load external content in iframe! 
            The prefix for the source is "iframe:" 
            e.g. to load an external page simply put something like this in the descripion: 
            "iframe://www.some-domain.com/123.html" 
            To load a YouTube video you could put this (replace eLvvPr6WPdg with your video code): 
            "iframe://www.youtube.com/embed/eLvvPr6WPdg?feature=player_detailpage" 
            To load some dynamic content over AJAX use "ajax:" as prefix, e.g. 
            "<code>ajax:/test/some_content_data.php?req=123</code>". 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_expFullscreen'>expFullscreen</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            If true, the overlay will open at fullscreen (window). 
            Type: bool
        </td></tr>

        <tr><td class='optdl' id='hsOpt_href'>href</td>
        <td class='optdm'>false</td>
        <td class='optdr'>
            Simple link for the hotspot. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_hrefTarget'>hrefTarget</td>
        <td class='optdm'>'_blank'</td>
        <td class='optdr'>
            Target for href (simple link), possible values: 
            _blank (new window), anything else is same window. 
            Type: string
        </td></tr>

        <tr><td class='optdl' id='hsOpt_click'>click</td>
        <td class='optdm'>null</td>
        <td class='optdr'>
            Your custom click event function, e.g. simple fancybox gallery:

<pre style="tab-size: 4; -moz-tab-size: 4; -o-tab-size: 4;">
jQuery.fancybox(
[{
    'href': '/path/some/image1.jpg',
    'title': 'Description 1 image'
},{
    'href': '/path/other/image2.jpg',
    'title': 'Description 2 image'
}], {
    'padding': 0,
    'transitionIn': 'none',
    'transitionOut': 'none',
    'type': 'image',
    'titlePosition': 'over',
    'changeFade': 0
});
</pre>
        AJAX-ZOOM does not require fancybox, 
        so fancybox JavaScripts and CSS files need to be included in the document. 
        The above code is just an example of a lightbox usage; 
        it can be any other lightbox where you could define images to be displayed in a similar way. 
        Please note that toolTip* options e.g. 
        toolTipHtml would produce a popup which looks similar to fancybox, 
        but they do not require fancybox JavaScript and CSS files. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_onRender'>onRender</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom function when a particlar hotspot is added to the DOM; 
        receives name of the hotspot as argument. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_mouseover'>mouseover</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom mouseover event function. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_mouseout'>mouseout</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom mouseout event function. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_mouseenter'>mouseenter</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom mouseenter event function. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_mouseleave'>mouseleave</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom mouseleave event function. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_mousedown'>mousedown</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom mousedown event function. 
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_mouseup'>mouseup</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Your custom mouseup event function. 
        Type: function
    </td></tr>
    
    <tr><td class='optdl' id='hsOpt_onRender'>onRender</td>
    <td class='optdm'>null</td>
    <td class='optdr'>
        Function executed when hotspot is rendered to the screen.
        Type: function
    </td></tr>

    <tr><td class='optdl' id='hsOpt_position'>position</td>
    <td class='optdm'>{}</td>
    <td class='optdr'>
        position is a JS object with the positions of a particular hotspot, e.g.<br>
<pre style="tab-size: 4; -moz-tab-size: 4; -o-tab-size: 4;">
position: {
    1: {left: 1500, 
        top: 720
    },
    3: {left: 660, 
        top: 710
    },
    4: {left: 760, 
        top: 510
    }
}
</pre>
        The keys (1,2,3 ...) can be numbers (starting from 1, not 0) or filenames of particular frames. 
        In case a key is omited the hotspot will not be shown in that particular frame. 
        <br><br>
        If shape value is 'rect' each value of position object needs to have 'width' and 'height', e.g.
<pre style="tab-size: 4; -moz-tab-size: 4; -o-tab-size: 4;">
position: {
    1: {left: 300, 
        top: 720, 
        width: 300, 
        height: 300
    },
    3: {left: 660, 
        top: 710, 
        width: 200, 
        height: 350
    },
    4: {left: 760, 
        top: 510, 
        width: 700, 
        height: 220
    }
}
</pre>
            The 'left', 'top', 'width' and 'height' values can be pixel values 
            <b>related to original size of the image</b> 
            or percentage values 
            (e.g. left: '45.75%', top: '37.3%').
        </td></tr>
        
    </tbody>
</table>

<script type="text/javascript" id="hs_docu_js">
if (window.jQuery){
    jQuery('.optionsTable:not(.methods) td:nth-child(2)')
    .each(function(){
        var txt = jQuery.trim(jQuery(this).html());
        if (txt == 'false' || txt == 'true'){
            jQuery(this).html('<span style="color: green">'+txt+'</span>');
        } else if (txt == 'null') {
            jQuery(this).html('<span style="color: blue">'+txt+'</span>');
        }
        else if (txt == 'function'){
            jQuery(this).html('<span style="color: #003c00">'+txt+'</span>');
        }
        else if (txt.charAt(0) != '\'' && txt.charAt(0) != '{' && txt.charAt(0) != '['){
            jQuery(this).html('<span style="color: red">'+txt+'</span>');
        }
    });

    jQuery('.optionsTable:not(.sub) td:nth-child(1)').each(function(){
        var txt = jQuery.trim(jQuery(this).html());
        if (txt){jQuery(this).parent().attr('id', 'pOpt_'+txt);}
    });

    if (window.optionsHeader){
        jQuery('<div class="optionsHeader">'+window.optionsHeader+'</div>')
        .insertBefore('.optionsTable:eq(0)');
    }
    if (window.optionsText){
        jQuery('<p class="optionsText">'+window.optionsText+'</p>')
        .insertBefore('.optionsTable:eq(0)');
    }
}
</script>
<?php
if (!$axzm_tpl_mode) {
?>
</body>
</html>
<?php
}
