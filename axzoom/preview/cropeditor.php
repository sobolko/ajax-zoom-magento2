<?php
/**
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/preview/cropeditor.php
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
$scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
$conf_array = $scopeConfig->getValue('axzoom_options', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);

function printTxt($a)
{
?>
<?=$a?>
<?php
}

/* This is not full protection and is not ment to be full protection */
/* !!!
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

/* Set to true to remove certain things not needed if included in shops / cms */
$editor_version = '2.0.5';
$last_updated = '2017-08-26';
$axzm_cms_mode = true;
$axzm_tpl_mode = false;
$axzm_path = '../axZm/';
$first_load360_dir = $request->getParam('3dDir');
$default_thumb_size = $axzm_cms_mode ? 140 : 180;
$player_responsive = $axzm_cms_mode ? true : false;
$langugaes_array = array();

// Stores languages
/*
$store_collection = Mage::getModel('core/store')->getCollection();
foreach ($store_collection as $store) {
    $storelang = Mage::getStoreConfig('general/locale/code', $store->getId());
    array_push($langugaes_array, substr($storelang, 0, 2));
}

$langugaes_array = array_unique($langugaes_array);
$langugaes_array = json_encode($langugaes_array);
*/
// !!!
$langugaes_array = ['en'];
$langugaes_array = json_encode($langugaes_array);

/* If used with a CMS / Shop, the dynamically generated url for controller */
$id_360 = $request->getParam('group');
$id_360set = $request->getParam('id');
$first_load_crop_json = $request->getParam('url_get')."?id_360set=$id_360set&id_360=$id_360";
$save_crop_json = $request->getParam('url_set')."?id_360set=$id_360set&id_360=$id_360&form_key=".$request->getParam('form_key');

// hotspots
$first_load_hotspot_json = $request->getParam('hs_get')."?id_360set=$id_360set&id_360=$id_360";

/* Stop config */
/* Stop config */
/* Stop config */

if (!$axzm_tpl_mode) {
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/">
    <head>
        <title>jCrop AJAX-ZOOM zoomTo and crop on large images</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="imagetoolbar" content="no">
    <?php
    if (!$axzm_cms_mode) {
    ?>
        <meta property="og:type" content="article"/>
        <meta property="og:title" content="jCrop AJAX-ZOOM"/>
        <meta property="og:description" content="Crop high resolution images with jCrop and AJAX-ZOOM."/>
        <meta name="description" content="Crop high resolution images with jCrop and AJAX-ZOOM." />
        <meta property="og:image" content="http://www.ajax-zoom.com/pic/layout/image-zoom_35.jpg"/>
    <?php
    }
    ?>
    <!-- jQuery core -->
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/jquery-1.8.3.min.js"></script>
    <?php
}
?>

    <!-- AJAX-ZOOM -->
    <link name="az_editor_css_scripts" rel="stylesheet" 
        href="<?php printTxt($axzm_path);?>axZm.css" type="text/css" media="screen">
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>jquery.axZm.js"></script>

    <!-- jCrop (external jQuery plugin utilized for this crop editor) -->
    <link name="az_editor_css_scripts" type="text/css" 
        href="<?php printTxt($axzm_path);?>plugins/jCrop/css/jquery.Jcrop.css" rel="stylesheet" />
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/jCrop/js/jquery.Jcrop.js"></script>

    <!-- Include mousewheel script -->
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>extensions/axZmThumbSlider/lib/jquery.mousewheel.min.js"></script>

    <!-- Only needed for the 360 hotspot example where fancybox is applied for one hotspot click event -->
    <link name="az_editor_css_scripts" 
        href="<?php printTxt($axzm_path);?>plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.zIndex.css" 
        type="text/css" media="screen" rel="stylesheet">
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.js"></script>

    <!-- Include thumbSlider CSS && JS. -->
    <link name="az_editor_css_scripts" rel="stylesheet" 
        type="text/css" 
        href="<?php printTxt($axzm_path);?>extensions/axZmThumbSlider/skins/default/jquery.axZm.thumbSlider.css" />
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>extensions/axZmThumbSlider/lib/jquery.axZm.thumbSlider.js"></script>

    <!-- Set of functions for this image cropping tool -->
    <link name="az_editor_css_scripts" rel="stylesheet" 
        type="text/css" href="<?php printTxt($axzm_path);?>extensions/jquery.axZm.imageCropEditor.css">
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>extensions/jquery.axZm.imageCropEditor.js"></script>

    <!-- A small function to add title button which will expend to full description -->
    <link name="az_editor_css_scripts" 
        rel="stylesheet" type="text/css" 
        href="<?php printTxt($axzm_path);?>extensions/jquery.axZm.expButton.css">
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>extensions/jquery.axZm.expButton.js"></script>

    <!-- Some other JavaScripts for the editor -->
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/JSON/jquery.json-2.3.min.js"></script>
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/js-beautify/beautify-all.min.js"></script>
    <script type="text/javascript" src="<?php printTxt($axzm_path);?>plugins/jquery.scrollTo.min.js"></script>

    <!-- Because of "sortable" used here, only needed for the editor! -->
    <link name="az_editor_css_scripts" 
        href="<?php printTxt($axzm_path);?>plugins/jquery.ui/themes/ajax-zoom/jquery-ui.css" 
        type="text/css" rel="stylesheet" />
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>plugins/jquery.ui/js/jquery-ui-1.8.24.custom.min.js"></script>

    <!-- CLEditor - WYSIWYG Editor (external jQuery plugin) -->
    <link name="az_editor_css_scripts" rel="stylesheet" 
        type="text/css" href="<?php printTxt($axzm_path);?>plugins/CLEditor/jquery.cleditor.css" />
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>plugins/CLEditor/jquery.cleditor.min.js"></script>
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>plugins/CLEditor/jquery.cleditor.table.min.js"></script>

    <?php
    if (!$axzm_cms_mode) {
    ?>
    <!-- Javascript to style the syntax, not needed! -->
    <link name="az_editor_css_scripts" 
        rel="stylesheet" 
        href="<?php printTxt($axzm_path);?>plugins/demo/prism/prism.css" type="text/css" media="screen">
    <script type="text/javascript" 
        src="<?php printTxt($axzm_path);?>plugins/demo/prism/prism.min.js"></script>

    <?php
    }
    ?>

    <script type="text/javascript">
    jQuery.aZonClick = {};
    <?php
    printTxt('jQuery.aZcropEd.langugaesArray = '.$langugaes_array.'; ');
    printTxt('jQuery.aZcropEd.playerResponsive = '.($player_responsive ? 'true' : 'false').'; ');

    if ($axzm_cms_mode) {
        'jQuery.aZcropEd.errors = false;';
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

<div id="outerWrap" style="width: 821px; margin: 0 auto;">
    <div id="playerWrap" style="width: 821px;">
        <?php
        if (!$axzm_cms_mode) {
        ?>
        <h2>Crop large images or make thumbnails with AJAX-ZOOM and "jCrop". 
        Generate zoomTo gallery with preview thumbs for 2D/ 360°/ 3D.
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

            <!-- Thumb slider with croped images -->
            <div id="cropSliderWrap">
                <div id="cropSlider">
                    <ul></ul>
                </div>
            </div>    
        </div>
        <div id="marginAfter" style="display: none; margin-bottom: 300px;"></div>
    </div>

    <!-- Tabs wrapper -->
    <div id="aZcR_tabs">
        <!-- Tab titles -->
        <ul>
            <li><a href="#aZcR_tabs-about">About</a></li>
            <li><a href="#aZcR_tabs-sel">Crop settings</a></li>
            <li><a href="#aZcR_tabs-crops">Crops</a></li>
            <li><a href="#aZcR_tabs-descr">Descriptions</a></li>
            <li><a href="#aZcR_tabs-import"><?php printTxt(!$axzm_cms_mode ? 'Import / Save' : 'Save'); ?></a></li>
            <?php
            if (!$axzm_cms_mode) {
            ?>
            <li><a href="#aZcR_tabs-load">Load content</a></li>

            <?php
            }
            ?>
        </ul>

        <!-- About -->
        <div id="aZcR_tabs-about">
            <?php
            if (!$axzm_cms_mode) {
            ?>
                <div class="legend" style="line-height: 60%;">About AJAX-ZOOM crop editor <br>
                    <span style="font-size: 50%">[Editor version: 
                        <?php printTxt($editor_version); ?>, date: <?php printTxt($last_updated); ?>]</span>
                </div>
                
                <p>With this tool you can easily create several crops from 2D images / galleries, 
                    360 spins or 3D multirow which are loaded into AJAX-ZOOM player. 
                    Besides other thinkable purposes the goal here is to make a "crop gallery" 
                    placed outside of the AJAX-ZOOM player. 
                    Clicking on the cropped thumb will zoom and for 360/3D spin & zoom 
                    to specified area of the image. 
                    This is a really nice looking effect where the user is guided to the highlights of the product 
                    by simply clicking on the thumbs in familiar manner.
                </p>
                <p>In the frontend where you will show this product tour to your customers AJAX-ZOOM can be  
                    integrated into responsive layout, shown in a lightbox, fullscreen etc. 
                    Also the cropped thumbs do not necessarily need to be loaded into the slider used here. 
                    The simple JSON produced by this configurator gives enough information 
                    to place these cropped thumbs wherever on the page.
                    The basic functionality for the onclick / click events are AJAX-ZOOM API functions 
                    <a href="http://www.ajax-zoom.com/index.php?cid=docs#api_zoomTo">jQuery.fn.axZm.zoomTo()</a> 
                        for plain 2D and  
                    <a href="http://www.ajax-zoom.com/index.php?cid=docs#api_spinTo">jQuery.fn.axZm.spinTo()</a> 
                    for 360 turn able objects. 
                    You can also test and see the very basics of this editor including zoomTo in 
                    <a href="example10.php">example10.php</a>;
                </p>

            <?php
            if (!$axzm_cms_mode && strstr($_SERVER['HTTP_HOST'], 'www.ajax-zoom.com')) {
            ?>
                <div id="demoOtherContent">
                    <script type="text/javascript">
                    var loadDemo = {
                        1: {
                            pathToLoad360: "<?php printTxt($first_load360_dir); ?>",
                            cropFileToLoad: "<?php printTxt($first_load_crop_json); ?>"
                        },
                        2: {
                            pathToLoad2D: '/pic/zoom/furniture/furniture_005.jpg', 
                            cropFileToLoad: '../pic/cropJSON/furniture_test.json'
                        },
                        3: {
                            pathToLoad2D: '/pic/zoom/animals', 
                            cropFileToLoad: '../pic/cropJSON/animals.json'
                        },
                        4: {
                            pathToLoad360: '/pic/zoomVR/nike', 
                            cropFileToLoad: '../pic/cropJSON/nike_3d.json'
                        },
                        5: {
                            pathToLoad360: '/pic/hotspots/bike360',
                            hotspotFileToLoad: '../pic/hotspotJS/bike.js',
                            cropFileToLoad: '../pic/cropJSON/bike360.json'
                        }
                    };
                    </script>
                    <div class="legend">Demo other content</div>
                    <p>Press on the buttons below to load a different content into the player.</p>
                    <input type="button" value="360 example" autocomplete="off" 
                        onclick="jQuery.aZcropEd.changeAxZmContentPHP(loadDemo[1])">
                    <input type="button" value="2d example (single image)" autocomplete="off"
                        onclick="jQuery.aZcropEd.changeAxZmContentPHP(loadDemo[2])">
                    <input type="button" value="2d gallery (many images)" autocomplete="off" 
                        onclick="jQuery.aZcropEd.changeAxZmContentPHP(loadDemo[3])">
                    <input type="button" value="3d example (multirow)" autocomplete="off" 
                        onclick="jQuery.aZcropEd.changeAxZmContentPHP(loadDemo[4])">
                    <input type="button" value="360 with hotspots" autocomplete="off" 
                        onclick="jQuery.aZcropEd.changeAxZmContentPHP(loadDemo[5])">
                </div>
            <?php
            }
            ?>
            <?php
            }
            ?>

            <div class="legend">How to</div>
            <ol>
                <li style="margin-bottom: 10px">
                    <img src="<?php printTxt($axzm_path);?>icons/default/button_iPad_settings.png" 
                        width="25" style="vertical-align: middle; margin: 2px 5px 2px 0px"> 
                    Optionally hit crop settings button or 
                    <a class="linkShowTab" href="javascript: void(0)" 
                        onclick="jQuery('#aZcR_tabs').tabs('select','#aZcR_tabs-sel');">
                    Crop settings</a>tab to adjust crop selector 
                    e.g. set aspect ratio and output parameters for the thumbnail.
                </li>
                <li style="margin-bottom: 10px">
                    <img src="<?php printTxt($axzm_path);?>icons/default/button_iPad_jcrop.png" 
                        width="25" style="vertical-align: middle; margin: 2px 5px 2px 0px"> 
                    Hit the crop button to toggle crop and select region to crop. 
                </li>
                <li style="margin-bottom: 10px">
                    <img src="<?php printTxt($axzm_path);?>icons/default/button_iPad_fire.png" 
                        width="25" style="vertical-align: middle; margin: 2px 5px 2px 0px"> 
                    When ready hit the red "fire crop" button. 
                </li>
                <li style="margin-bottom: 10px">
                    At the <a class="linkShowTab" href="javascript: void(0)" 
                        onclick="jQuery('#aZcR_tabs').tabs('select','#aZcR_tabs-crops');">Cropped images</a> tab 
                    you can view the crops in real size, delete and reorder the crops.
                </li>
                <li style="margin-bottom: 10px">
                    Optionally add thumb title, title and description to the crop regions in 
                    <a class="linkShowTab" href="javascript: void(0)" 
                        onclick="jQuery('#aZcR_tabs').tabs('select','#aZcR_tabs-descr');">Descriptions</a> tab.
                </li>
                <li>
                    <a class="linkShowTab" href="javascript: void(0)" 
                        onclick="jQuery('#aZcR_tabs').tabs('select','#aZcR_tabs-import');">Save</a> 
            <?php
            if ($axzm_cms_mode) {
            ?>
            the created setup. 
            <?php
            } else {
            ?>
            the created setup e.g. in a JSON file to reproduce the work at some other frontend.
            <?php
            }
            ?>
                </li>
            </ol>

            <?php
            if (!$axzm_cms_mode) {
            ?>
                
                <div class="legend">"Clean" and derived examples</div>
                <p>This file (example35.php) is the actual crop editor to define the copped thumbs 
                    and optionally descriptions. 
                    It is supposed to be in some restricted area of your page. For showing the results and 
                    integration of the player into your frontend please use one of the following "clean" examples 
                    as the starting point. Also please be aware of that AJAX-ZOOM is highly configurable 
                    so you can change the look and feel of nearly everything you could think of. 
                </p>

                <div class="azMsg clearfix">
                    <a href="example35_clean.php">
                        <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_35_clean.jpg" 
                            width="100" align="left" style="margin: -5px 10px -5px -5px; vertical-align: top;">
                    </a>
                    <a href="example35_clean.php">example35_clean.php</a> basically has the same setup 
                    as this editor but without the toolbar under the player.
                </div>
                <div class="azMsg clearfix">
                    <a href="example35_clean_horizontal.php">
                        <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_35_clean_horizontal.jpg" 
                            width="100" align="left" style="margin: -5px 10px -5px -5px; vertical-align: top;">
                    </a>
                    If you want to have a horizontal gallery please check out 
                    <a href="example35_clean_horizontal.php">example35_clean_horizontal.php</a>
                </div>
                <div class="azMsg clearfix">
                    <a href="example35_adaptive.php">
                        <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_35_adaptive.jpg" 
                            width="100" align="left" style="margin: -5px 10px -5px -5px; vertical-align: top;">
                    </a>
                    Adaptive vertical or horuzontal gallery depending on screen size
                    <a href="example35_adaptive.php">example35_adaptive.php</a>
                </div>
                <div class="azMsg clearfix">
                    <a href="example35_gallery.php">
                        <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_35_gallery.jpg" 
                            width="100" align="left" style="margin: -5px 10px -5px -5px; vertical-align: top;">
                    </a>
                    Of course the cropped thumbnails do not need to be loaded into the thumb slider. 
                    In <a href="example35_gallery.php">example35_gallery.php</a> they are appended to some div.
                </div>
                <div class="azMsg clearfix">
                    <a href="example32.php">
                        <img src="http://www.ajax-zoom.com/pic/layout/image-zoom_32.jpg" 
                            width="100" align="left" style="margin: -5px 10px -5px -5px; vertical-align: top;">
                    </a>
                    <a href="example32.php">example32_responsive.php</a> 
                        optionally loads the results of this crop editor into mouseover zoom / 360 combination 
                        and instantly attaches the crop gallery to the player. Very convenient for e-commerce.
                </div>

                <a href="http://www.ajax-zoom.com/index.php?cid=contact">On request</a> 
                    AJAX-ZOOM team will deliver to you exactly what you need 
                    as a different example or integrated into your page layout / html.

                <div class="legend">The final code example</div>
                The only difference between basic AJAX-ZOOM implementation / example 
                is that the JSON data produced by this editor is loaded in AJAX-ZOOM onLoad callback 
                with jQuery.axZmImageCropLoad, see below at line 044; 
                Also in all derivative examples except 
                <a href="example35_gallery.php">example35_gallery.php</a> we use jQuery.axZmThumbSlider to display 
                the thumbsnails; all codes for implementing can be also found in the example35_*.php files.
                <div class="legend">JavaScript & CSS files in Head</div>
                <pre><code class="language-markup">
                <?php
                $playerhead = '
<!-- jQuery core -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>

<!-- AJAX-ZOOM -->
<link rel="stylesheet" href="../axZm/axZm.css" type="text/css">
<script type="text/javascript" src="../axZm/jquery.axZm.js"></script>

<!-- Include axZm.thumbSlider plugin -->
<link rel="stylesheet" 
    href="../axZm/extensions/axZmThumbSlider/skins/default/jquery.axZm.thumbSlider.css" type="text/css" />
<script type="text/javascript" src="../axZm/extensions/axZmThumbSlider/lib/jquery.mousewheel.min.js"></script>
<script type="text/javascript" src="../axZm/extensions/axZmThumbSlider/lib/jquery.axZm.thumbSlider.js"></script>

<!-- Include jquery.axZm.imageCropLoad.js -->
<script type="text/javascript" src="../axZm/extensions/jquery.axZm.imageCropLoad.js"></script>

<!-- A small function to add title button which will expend to full description -->
<link rel="stylesheet" href="../axZm/extensions/jquery.axZm.expButton.css" type="text/css" />
<script type="text/javascript" src="../axZm/extensions/jquery.axZm.expButton.js"></script>
';
                $playerhead = htmlspecialchars($playerhead);
                printTxt($playerhead);
                ?>
                </code></pre>

                <div class="legend">CSS</div>
                <pre><code class="language-css">
                <?php
                $playercss = '
#playerInnerWrap {
    position: relative;
    border-left: 1px solid #AAAAAA;
    border-top: 1px solid #AAAAAA;
    border-bottom: 1px solid #AAAAAA;
}

#cropSliderWrap {
    position: absolute;
    background-color: #FFFFFF;
    z-index: 11;
    width: 100px;
    height: 100%;
    right: 0px;
    top: 0px;
}

#cropSlider {
    position: absolute;
    width: 100px;
    height: 100%;
}

#axZmFsSpaceRight div {
    box-sizing: border-box;
}';
                $playercss = htmlspecialchars($playercss);
                printTxt($playercss);
                ?>
                </code></pre>

                <div class="legend">HTML markup (taken from 
                    <a href="http://www.ajax-zoom.com/examples/example35_clean.php">example35_clean.php</a>)
                </div>
                <pre><code class="language-markup">
                <?php
                $playerhtml = '
<div id="playerInnerWrap" style="min-height: 480px;">
    <div id="azPlayer" style="width: 721px;">
        <!-- Content inside target will be removed -->
        <div style="padding: 20px">Loading, please wait...</div>
    </div>

    <!-- Thumb slider with croped images -->
    <div id="cropSliderWrap">
        <div id="cropSlider">
            <ul></ul>
        </div>
    </div>
</div>
';
                $playerhtml = htmlspecialchars($playerhtml);
                printTxt($playerhtml);
                ?>
                </code></pre>

                <div class="legend">Javascript</div>
                <pre><code class="language-js">
                <?php
                $playerjs = '
// Init the slider
// Thumbs will be appended instantly
jQuery("#cropSlider").axZmThumbSlider({
    orientation: "vertical",
    btnOver: true,
    btnHidden: true,
    btnFwdStyle: {borderRadius: 0, height: 20, bottom: -1, lineHeight: "20px"},
    btnBwdStyle: {borderRadius: 0, height: 20, top: -1, lineHeight: "20px"},

    thumbLiStyle: {
        height: 90,
        width: 90,
        lineHeight: 90,
        borderRadius: 0,
        margin: 3
    }
});

// AJAX-ZOOM
// Create empty jQuery object (no not rename here)
var ajaxZoom = {}; 

// Define the path to the axZm folder, adjust the path if needed!
ajaxZoom.path = "../axZm/"; 

// Define your custom parameter query string
// example=spinIpad has many presets for 360 images
// 3dDir - best of all absolute path to the folder with 360/3D images
// if it is a 2D image just pass 
// zoomData=/path/to/your/image/image1.jpg|/path/to/other/image/image2.jpg instead of 3dDir
// ajaxZoom.parameter = "example=spinIpad&3dDir=/pic/zoom3d/Uvex_Occhiali"; 
// Define your custom parameter query string
ajaxZoom.parameter = "example=spinIpad&3dDir=../pic/zoom3d/Canon_1100D";

// Id of element where AJAX-ZOOM will be loaded into
ajaxZoom.divID = "azPlayer";

// Define callbacks, for complete list check the docs
ajaxZoom.opt = {
    onLoad: function(){ 
        // Load crop thumbs
        // You can also pass the path over query string, e.g.
        // example35.php?cropJsonURL=../pic/cropJSON/eos_1100d.json 
        // and skip cropJsonURL key in jQuery.axZmImageCropLoad
        jQuery.axZmImageCropLoad({
            cropJsonURL: "../pic/cropJSON/eos_1100d.json",
            sliderID: "cropSlider",
            spinToSpeed: "2500", // as string to override spinDemoTime when clicked on the thumbs
            // optionally pass spinToMotion to override spinToMotion set in config file, def. easeOutQuad
            spinToMotion: "easeOutQuint", 
            handleTexts: "default" // would do about the same as commented out below...
            /*
            handleTexts: function(title, description){
                // One of the possible things to do with title and description
                // e.g. display texts with jquery.axZm.expButton.js (AJAX-ZOOM additional plugin)
                jQuery.axZmEb({
                    title: title,
                    descr: description,
                    // possible values: topLeft, top, topRight, bottomLeft, bottom, bottomRight, center
                    gravity: "top", 
                    marginY: 5,  // vertical margin depending on gravity
                    // removes button / layer when there is some action inside AJAX-ZOOM
                    zoomSpinPanRemove: "cropSlider", 
                    // button opens instantly; if no tilte but descr is defined, autoOpen executes instantly
                    autoOpen: false, 
                    removeOnClose: false // removes button when extended state is closed
                });
            }
            */
        });
        
        // This would be the code for additionally loading hotspots made e.g. with example33.php
        //jQuery.aZcropEd.getJSONdataFromFile("../pic/cropJSON/eos_1100d.json");
    },
    onBeforeStart: function(){
         if (jQuery.axZm.spinMod){
             jQuery.axZm.restoreSpeed = 300;
        }else{
            jQuery.axZm.restoreSpeed = 0;
        }

        //jQuery.axZm.fullScreenCornerButton = false;
        jQuery.axZm.fullScreenExitText = false;

        // Chnage position of the map
        //jQuery.axZm.mapPos = "bottomLeft";

        // Set extra space to the right at fullscreen mode for the crop gallery
        jQuery.axZm.fullScreenSpace = {
            right: jQuery("#cropSlider").outerWidth(),
            top: 0, bottom: 0, left: 0, layout: 1
        };
    },
    onFullScreenSpaceAdded: function(){
        jQuery("#cropSlider")
        .css({bottom: 0,right: 0, height: "100%", zIndex: 555})
        .appendTo("#axZmFsSpaceRight");
    },
    onFullScreenClose: function(){
        jQuery.fn.axZm.tapShow();

        jQuery("#cropSlider")
        .css({bottom: "", right: "", zIndex: ""})
        .appendTo("#cropSliderWrap");
    },
    onFullScreenCloseEndFromRel: function(){
        // Restore position of the slider
        jQuery("#cropSlider")
        .css({bottom: "", right: "", zIndex: ""})
        .appendTo("#cropSliderWrap");
    }
};

// Load AJAX-ZOOM not responsive
jQuery.fn.axZm.load({
    opt: ajaxZoom.opt,
    path: ajaxZoom.path,
    postMode: false,
    apiFullscreen: false,
    parameter: ajaxZoom.parameter,
    divID: ajaxZoom.divID
});

// Load responsive
//window.fullScreenStartSplash = {enable: false, className: false, opacity: 0.75};
//jQuery.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.parameter, ajaxZoom.opt, ajaxZoom.divID, false, false);
';
                $playerjs = htmlspecialchars($playerjs);
                printTxt($playerjs);
                ?>
                </code></pre>

                <?php
                /* This include is just for the demo, you can remove it */
                if (file_exists(dirname(__FILE__).'/footer.php')) {
                    include dirname(__FILE__).'/footer.php';
                }
                ?>
            <?php
            }
            ?>
        </div>

        <!-- Crop settings -->
        <div id="aZcR_tabs-sel">
            <!-- Crop options for Jcrop selector and AJAX-ZOOM thumbnail generator-->
            <div id="cropOptionsParent">
                <div id="cropOptions">
                    <div class="legend">Jcrop (selector) settings</div>
                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                        <label>Selection:</label>
                        <select id="cropOpt_selection" onchange="jQuery.aZcropEd.jCropHandleSelection()">
                            <option value="">normal</option>
                            <option value="aspectRatio" selected="selected">Aspect ratio</option>
                            <option value="fixedSize">Fixed size</option>
                        </select>
                    </div>
                    <div id="cropOpt_ratioBox" style="clear: both; margin: 5px 0px 5px 0px;">
                        <label>Aspect ratio:</label>
                        W: <input id="cropOpt_ratio1" type="text" value="1" autocomplete="off" 
                            style="width: 50px" onchange="jQuery.aZcropEd.jCropAspectRatio()"> 
                        <input type="button" style="width: 30px;" value="&#8660;" autocomplete="off" 
                            onclick="jQuery.aZcropEd.jCropAspectFlipValues()">
                        H: <input id="cropOpt_ratio2" type="text" value="1" autocomplete="off" 
                            style="width: 50px" onchange="jQuery.aZcropEd.jCropAspectRatio()"> 
                        <div>
                            <label></label>
                            <input type="button" value="as thumb" autocomplete="off" 
                                style="margin-top: 3px; width: 80px;" onclick="jQuery.aZcropEd.jCropAspectAsThumb()">
                            <input type="button" value="as image" autocomplete="off" 
                                style="margin-top: 3px; width: 80px;" onclick="jQuery.aZcropEd.jCropAspectAsImage()">
                        </div>
                    </div>
                    <div id="cropOpt_sizeBox" style="clear: both; margin: 5px 0px 5px 0px; display: none;">
                        <label>Fixed size:</label>
                        W: <input id="cropOpt_sizeW" type="text" value="" autocomplete="off" 
                            style="width: 50px" onchange="jQuery.aZcropEd.jCropFixedSize()"> 
                        H: <input id="cropOpt_sizeH" type="text" value="" autocomplete="off" 
                            style="width: 50px" onchange="jQuery.aZcropEd.jCropFixedSize()"> px
                    </div>

                    <div class="legend">Thumbnail settings</div>

                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                        <label>Thumbnail size:</label>
                        W: <input id="cropOpt_thumbSizeW" type="text" 
                            value="<?php printTxt($default_thumb_size); ?>" autocomplete="off"
                            style="width: 50px" onchange="jQuery.aZcropEd.jCropInitSettings()">  
                        H: <input id="cropOpt_thumbSizeH" type="text" 
                            value="<?php printTxt($default_thumb_size); ?>" autocomplete="off"
                            style="width: 50px" onchange="jQuery.aZcropEd.jCropInitSettings()"> px
                    </div>

                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                        <label>Thumbnail mode:</label>
                        <select id="cropOpt_thumbMode" onchange="jQuery.aZcropEd.jCropInitSettings()">
                            <option value="">-</option>
                            <option value="contain">contain</option>
                            <option value="cover">cover</option>
                        </select>
                    </div>

                    <div id="cropOpt_colorBox" style="clear: both; margin: 5px 0px 5px 0px; display: none;">
                        <label>Background color (hex):</label>
                        #<input id="cropOpt_backColor" type="text" value="FFFFFF" autocomplete="off" 
                            style="width: 100px" onchange="jQuery.aZcropEd.jCropInitSettings()">
                    </div>
                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                        <label>Jpeg quality:</label>
                        <input id="cropOpt_jpgQual" type="text" value="90" style="width: 40px" autocomplete="off" 
                            onchange="jQuery.aZcropEd.jCropInitSettings()"> 
                        (10 - 100)
                    </div>    
                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                        <label>Cache (can be set later):</label>
                        <input id="cropOpt_cache" type="checkbox" value="1" autocomplete="off" 
                            onchange="jQuery.aZcropEd.jCropInitSettings()">
                    </div>
                </div>
            </div>
        </div>

        <!-- Cropped images -->
        <div id="aZcR_tabs-crops">
            <?php
            if ($axzm_cms_mode) {
            ?>
                <div class="legend">Crops, Drag & drop to reorder</div>
            <?php
            } else {
            ?>
                <div class="legend">Crop results (real size)</div>
            <?php
            }
            ?>

            <?php
            if (!$axzm_cms_mode) {
            ?>
                <div class="azMsg">Drag & drop to reorder the thumbs, 
                click to get the paths and other information (see below), 
                double click to zoom.
                </div>
            <?php
            }
            ?>

            <!-- Crop results real size -->
            <div id="aZcR_cropResults"></div>
            <input type="button" value="Reamove all crops" autocomplete="off" 
                style="margin-top: 5px" onclick="jQuery.aZcropEd.clearAll()" /> 
            <?php
            if (!$axzm_cms_mode) {
            ?>
                - crops will be not deleted physically here!

                <div class="legend">Paths</div>

                <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <label>Query string:</label>
                    <input id="aZcR_qString" type="text" onClick="this.select();" autocomplete="off" 
                        style="margin-bottom: 5px; width: 100%" value="">
                </div>

                <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <label>Url:</label>
                    (please note that full Url might differ if this editor is implemented in a backend of some CMS)
                    <input id="aZcR_url" type="text" onClick="this.select();" autocomplete="off" 
                        style="margin-bottom: 5px; width: 100%" value="">
                </div>

                <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <label>Cached image url:</label>
                    (only available if "cache" option is chacked under "crop settings" tab)
                    <input id="aZcR_contentLocation" type="text" onClick="this.select();" autocomplete="off" 
                        style="margin-bottom: 5px; width: 100%" value="">
                </div>
            <?php
            }
            ?>
        </div>

        <!-- Description -->
        <div id="aZcR_tabs-descr">
            <div class="legend">Crop descriptions / settings</div>

            <div class="azMsg">
                <?php
                if (!$axzm_cms_mode) {
                ?>
                    Optionally add a title || description to use them later in various ways.
                    In this editor and also in the derived "clean" examples like 
                    <a href="example35_clean.php">example35_clean.php</a> 
                    we use "axZmEb" - expandable button (AJAX-ZOOM additional plugin) to display these titles 
                    || descriptions over the image respectively inside the player. 
                    You could however easily change the usage of title || description in your implementation, 
                    e.g. display them under the player or whatever. Just change the "handleTexts" 
                    property of the options object when passing it to jQuery.axZmImageCropLoad 
                    - see source code of e.g. 
                    <a href="example35_clean.php">example35_clean.php</a>;<br><br>
                    
                    Besides HTML or your text you could also load external content in iframe! 
                    The prefix for the source is "iframe:"<br><br>
                    e.g. to load an external page simply put something like this in the descripion:<br> 
                    <code>iframe://www.canon.co.uk/For_Home/Product_Finder/Cameras/Digital_SLR/EOS_1100D</code>
                    <br><br>
                    To load a YouTube video you could put this (replace eLvvPr6WPdg with your video code): <br>
                    <code>iframe://www.youtube.com/embed/eLvvPr6WPdg?feature=player_detailpage</code>
                    <br><br>
                    To load some dynamic content over AJAX use "ajax:" as prefix, e.g.<br>
                    <code>ajax:/test/some_content_data.php?req=123</code>
                    <br><br>
                    If you do not define the title, then the content will be loaded instantly as soon 
                    as the spin animation finishes.
                <?php
                } else {
                ?>
                    Optionally add a title and/or description. 
                    Besides HTML or your text you could also load external content in iframe! 
                    The prefix for the source is "iframe:"<br><br>
                    e.g. to load an external page simply put something like this in the descripion:<br> 
                    iframe://www.canon.co.uk/For_Home/Product_Finder/Cameras/Digital_SLR/EOS_1100D
                    <br><br>
                    To load a YouTube video you could put this (replace eLvvPr6WPdg with your video code): <br>
                    iframe://www.youtube.com/embed/eLvvPr6WPdg?feature=player_detailpage
                    <br><br>
                    To load some dynamic content over AJAX use "ajax:" as prefix, e.g.<br>
                    ajax:/test/some_content_data.php?req=123
                    <br><br>
                    If you do not define the title, then the content will be loaded instantly 
                    as soon as the spin animation finishes.
                <?php
                }
                ?>
            </div>

            <div id="aZcR_descrWrap">
                <!-- Tables with title and description field will be added here -->
            </div>
        </div>

        <!-- Import / Save -->
        <div id="aZcR_tabs-import">

            <?php
            if (!$axzm_cms_mode) {
            ?>
                <div class="legend">Import all thumbs</div>
            <?php
            } else {
            ?>
                <div class="legend">Save the results</div>
            <?php
            }
            ?>

            <!-- Import form, do not change order of the fields-->
            <div id="aZcR_getAllThumbsForm">
                <script type="text/javascript">
                    var handleDisplayLongLine = function(a){
                        jQuery(a).val() == 'CSV' ? jQuery(a).next().css('display', '') 
                            : jQuery(a).next().css('display', 'none');
                        jQuery.aZcropEd.getAllThumbs();
                    };
                </script>
                <?php
                if (!$axzm_cms_mode) {
                ?>
                    <input type="button" value="Get all" autocomplete="off" onclick="jQuery.aZcropEd.getAllThumbs()">

                    <select onchange="jQuery.aZcropEd.getAllThumbs()" autocomplete="off">
                        <option value="qString">Query string</option>
                        <option value="url">Url</option>
                        <option value="contentLocation">Cached image url</option>
                    </select> 

                    <select onchange="handleDisplayLongLine(this)" autocomplete="off">
                        <option value="JSON_data">JSON with data</option>
                        <option value="JSON">JSON</option>
                        <option value="CSV">CSV</option>
                    </select>

                    <span style="display: none;">separated with <input type="text" value="|" 
                        style="width: 20px;" autocomplete="off" 
                        onchange="jQuery.aZcropEd.getAllThumbs()"></span> 
                    <br>and convert to be cached 
                    <input type="checkbox" value="1" onclick="jQuery.aZcropEd.getAllThumbs()" 
                        checked="true" autocomplete="off">
                    and replace thumb size 
                    <input type="checkbox" value="1" 
                        onclick="jQuery(this).next().toggle(); jQuery.aZcropEd.getAllThumbs();" autocomplete="off">
                    <span style="display: none">
                        W: <input type="text" style="width: 50px" 
                            onchange="jQuery.aZcropEd.getAllThumbs();" autocomplete="off">
                        H: <input type="text" style="width: 50px" 
                            onchange="jQuery.aZcropEd.getAllThumbs();" autocomplete="off"> px
                    </span>
                    <br>and convert px coordinates to percentage 
                    <input type="checkbox" value="1" onclick="jQuery.aZcropEd.getAllThumbs();" autocomplete="off">
                <?php
                } else {
                ?>
                    <input type="button" value="Refresh" onclick="jQuery.aZcropEd.getAllThumbs()">

                    <select style="display: none;" onchange="jQuery.aZcropEd.getAllThumbs()" autocomplete="off">
                        <option value="qString">Query string</option>
                        <option value="url">Url</option>
                        <option value="contentLocation">Cached image url</option>
                    </select> 

                    <select style="display: none;" onchange="handleDisplayLongLine(this)" autocomplete="off">
                        <option value="JSON_data">JSON with data</option>
                        <option value="JSON">JSON</option>
                        <option value="CSV">CSV</option>
                    </select>

                    <span style="display: none;"> <input type="text" value="|" style="width: 20px; display: none;" 
                        onchange="jQuery.aZcropEd.getAllThumbs()" autocomplete=off></span> 
                    <input style="display: none;" type="checkbox" value="1" 
                        onclick="jQuery.aZcropEd.getAllThumbs()" checked="true" autocomplete="off">
                    and replace thumb size 
                    <input type="checkbox" value="1" 
                        onclick="jQuery(this).next().toggle(); jQuery.aZcropEd.getAllThumbs();" autocomplete="off">
                    <span style="display: none">
                        W: <input type="text" style="width: 50px"   
                            onchange="jQuery.aZcropEd.getAllThumbs();" autocomplete="off">
                        H: <input type="text" style="width: 50px" 
                            onchange="jQuery.aZcropEd.getAllThumbs();" autocomplete="off"> px
                    </span>
                    <input style="display: none;" type="checkbox" value="1" 
                        onclick="jQuery.aZcropEd.getAllThumbs();" autocomplete="off">

                <?php
                }
                ?>
            </div>

            <form <?php printTxt(!$axzm_cms_mode ? 'action="'.$axzm_path.'saveCropJSON.php"' : ''); ?> 
                id="aZcR_saveJSON">
                <textarea id="aZcR_getAllThumbs" 
                    style="width: 100%; height: 350px; font-size: 10px; margin-top: 5px;" 
                    spellcheck="false" autocomplete="off"></textarea>
            </form>
                <?php
                if ($axzm_cms_mode) {
                ?>
                <input type="button" value="Save into database" id="btnSaveJSON" autocomplete="off"> 
                <script type="text/javascript">
                    jQuery.aZonClick.KZgycfUPZPcM = function() {
                        jQuery('#aZcR_getAllThumbs')
                        .val(jQuery('#aZcR_getAllThumbs').val().replace(/(\r\n|\n|\r|\t)/gm,''));
                    }
                </script>

                <input type="button" value="Remove line breaks" style="margin-top: 5px;" autocomplete="off" 
                    onclick="jQuery.aZonClick.KZgycfUPZPcM()">

                <div id="dialog-saveJSON" title="JSON saved" style="display: none;">
                    <p>The 360 "Product Tour" has been saved and should be visible in Front-End now!</p>
                </div>

                <script type="text/javascript">
                    jQuery('#btnSaveJSON').click(function(e){
                        e.preventDefault();
                        jQuery.post('<?php printTxt($save_crop_json); ?>', {json: jQuery('#aZcR_getAllThumbs').val()}, 
                        function(ret) {
                            ret = jQuery.parseJSON(ret);
                            if (jQuery.isPlainObject(ret) && ret.status == 1){
                                jQuery("#dialog-saveJSON").dialog({
                                    modal: true,
                                    buttons: {
                                        Ok: function(){
                                            jQuery(this).dialog( "close" );
                                        }
                                    }
                                });
                            }
                        });
                    });
                </script>
                <?php
                }
                ?>

            <?php
            if (!$axzm_cms_mode) {
            ?>
                <!-- Just a button to select text in the textarea above, can be removed -->
                <input type="button" value="Select text" style="margin-top: 5px;" autocomplete="off" 
                    onclick="jQuery('#aZcR_getAllThumbs')[0].select()">

                <script type="text/javascript">
                    jQuery.aZonClick.ZftxbtKwhAhL = function() {
                        jQuery('#aZcR_getAllThumbs')
                        .val(jQuery('#aZcR_getAllThumbs').val().replace(/(\r\n|\n|\r|\t)/gm,''))
                    }
                </script>

                <input type="button" value="Remove line breaks" style="margin-top: 5px;" autocomplete="off" 
                    onclick="jQuery.aZonClick.ZftxbtKwhAhL()">

                <!-- Save -->
                <div class="legend">Save JSON to file</div>

                <div style="margin-top: 10px"><label>Password for saving:</label>
                    <input type="password" id="aZcR_jsFilePass" value=""> 
                    (can be set or disabled inside '/axZm/saveCropJSON.php')</div>
                <div style="margin-top: 10px"><label>Keep formated:</label>
                    <input type="checkbox" id="aZcR_jsKeepFormated" value="1"> - 
                    keep linebreaks, tab stops etc.</div>
                <div style="margin-top: 10px"><label>Create backup:</label>
                    <input type="checkbox" id="aZcR_jsBackUp" value="1" checked> - 
                    creates backup of the current JSON file if present with a timestamp in file name</div>
                <div style="margin-top: 10px"><label>Save JSON:</label>
                <input style="width: 100px;" type="button" value="Save" autocomplete="off" 
                    onClick="jQuery.aZcropEd.saveJSONtoFile();"> 
                to <input type="text" value="" id="aZcR_jsFileName" autocomplete="off">.json (a-zA-Z0-9-_)
                </div>

                <div style="margin-top: 10px"><label></label>
                    e.g. "eos_1100d" 
                    (on default the file is saved into "pic/cropJSON" folder)
                </div>

                <div style="margin: 10px 0px;">
                    <div id="aZcR_saveToJSONresults"></div> 
                </div>

                <div class="legend">Notes</div>
                In your final frontend presentation you can compose url out of query string with js 
                <code>jQuery.fn.axZm.installPath()+'zoomLoad.php?'+queryString</code>
            <?php
            }
            ?>
        </div>

        <?php
        if (!$axzm_cms_mode) {
        ?>
            <!-- Load content -->
            <div id="aZcR_tabs-load">
                <div class="legend">Load a different 2D / 360 or 3D content</div>

                    <div class="azMsg">You do not need to edit html of 
                        this file in order to load a different 2D / 360 or 3D content into the editor. 
                        Just enter a path into one of the fields below and press "LOAD" button. 
                        Press "GET" button to see what is currently loaded.
                    </div>

                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <label>1. Path for 2D:</label> <input type="text" value="" 
                        id="aZcR_pathToLoad2D" style="width: 400px;" autocomplete="off">  or
                    </div>

                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <label>2. Path for 360 or 3D:</label> <input type="text" value="" 
                        id="aZcR_pathToLoad360" style="width: 400px;" autocomplete="off"> 
                    </div>

                    <div style="clear: both; margin: 15px 0px 5px 0px;">
                    <label>3. Hotspot file path:</label> <input type="text" value="" 
                        id="aZcR_hotspotFileToLoad" style="width: 350px;" autocomplete="off"> (optional)
                    </div>

                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <label>4. Crop file path:</label> <input type="text" value="" 
                        id="aZcR_cropFileToLoad" style="width: 350px;" autocomplete="off"> (optional)
                    </div>

                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                    <input type="button" value="LOAD" onClick="jQuery.aZcropEd.changeAxZmContentPHP();" 
                        autocomplete="off">&nbsp;&nbsp;
                    <input type="button" value="GET" onClick="jQuery.aZcropEd.getLoadedParameters();" 
                        autocomplete="off">
                    </div>

                    <div id="aZcR_pathToParameter"></div>

                <div class="legend">How does it work:</div>
                    <div style="clear: both; margin: 5px 0px 5px 0px;">
                        <ol> 
                            <li>
                                <strong>For 2D</strong> (single image or gallery with more images) 
                                please enter local path(s) to the image(s), e.g. <br>
                                "<code>/pic/zoom/animals/test_animals1.png</code>"<br>
                                or image set with image paths separated with vertical dash e.g.<br>
                                "<code>/pic/zoom/animals/test_animals1.png|/pic/zoom/animals/test_animals2.png</code>"
                                <br> 
                                If you want to load all images from a folder please just enter 
                                the path to this folder e.g. <br>
                                "<code>/pic/zoom/animals</code>"
                            </li>
                            <li style="margin-top: 10px;">
                                <ul>
                                    <li style="margin-top: 5px;"><strong>For 360</strong> 
                                        (single row 360 object) please enter only the path to the folder 
                                        where your 360 images are located e.g. <br>
                                        "<code>/pic/zoom3d/Uvex_Occhiali</code>";
                                    </li>
                                    <li style="margin-top: 5px;">
                                        <strong>For 3D</strong> (multi row turnable object) please enter 
                                        the path to the folder 
                                        where subfolders with each row of 3D are located.<br> 
                                        On <a href="http://www.ajax-zoom.com/examples/example35.php" 
                                        target="_blank">http://www.ajax-zoom.com/examples/example35.php</a> 
                                        this could be <br>
                                        "<code>/pic/zoomVR/nike</code>" <br>
                                        (not provided with the download package)
                                    </li>
                                </ul>
                            </li>
                            <li style="margin-top: 10px;">
                                <strong>Hotspot file path</strong> is the path to the file 
                                with hotspot configurations and positions, e.g.<br>
                                "<code>../pic/hotspotJS/eos_1100D.js</code>"<br> 
                                You can create such a file in <a href="example33.php">example33.php</a>
                            </li>
                            <li style="margin-top: 10px;">
                                <strong>Crop file path</strong> is the path to the file with crop data 
                                which can be created with this editor, e.g.<br>
                                "<code>../pic/hotspotJS/eos_1100d.json</code>"<br> 
                            </li>
                        </ol>
                    </div>

                <div class="legend">Load only JSON data from file into editor</div>
                <div style="margin-top: 10px">
                    <label>Crop JSON file path:</label>
                    <input type="text" value="" id="aZcR_onlyJSONcropFile" style="width: 350px;" autocomplete="off">
                </div>
                <label></label>e.g.: "<code>../pic/cropJSON/eos_1100d.json</code>"
                <div style="margin-top: 10px">
                    <label></label>
                    <input type="button" value="Load" autocomplete="off" 
                        onclick="jQuery.aZcropEd.getJSONdataFromFile(jQuery('#aZcR_onlyJSONcropFile').val())">
                </div>

            </div>

        <?php
        }
        ?>
    <!-- end Tabs wrapper -->
    </div>

<!-- end outerWrap -->
</div>

<script type="text/javascript">
    // AJAX-ZOOM
    // Create empty jQuery object (no not rename here)
    var ajaxZoom = {}; 

    // Define callbacks, for complete list check the docs
    ajaxZoom.opt = {
        // First json to load
        onLoad: function(){ // onSpinPreloadEnd
            jQuery.aZcropEd.getJSONdataFromFile('<?php printTxt($first_load_crop_json);?>');
            <?php
            if (isset($first_load_hotspot_json) && $first_load_hotspot_json) {
            ?>
            setTimeout(function(){
                jQuery.fn.axZm.loadHotspotsFromJsFile('<?php printTxt($first_load_hotspot_json); ?>', false, function(){
                    
                });
            }, 1000);
            <?php
            }
            ?>
        },

        onCropEnd: function(){
            jQuery.aZcropEd.jCropOnChange();
        },

        onFullScreenResizeEnd: function(){
            // Toggle Jcrop
            if (jcrop_api){
                jQuery.aZcropEd.jCropMethod('destroy');
            }
        },

        onBeforeStart: function(){            
            // Set background color, can also be done in css file
            jQuery('.axZm_zoomContainer').css({backgroundColor: '#FFFFFF'});    

            if (jQuery.axZm.spinMod){
                jQuery.axZm.restoreSpeed = 300;
            }else{
                jQuery.axZm.restoreSpeed = 0;
            }

            // Set extra space to the right at fullscreen mode for the crop gallery
            jQuery.axZm.fullScreenSpace = {
                top: 0,
                right: 100,
                bottom: 0,
                left: 0,
                layout: 1
            };

            //jQuery.axZm.fullScreenApi = true;

            //jQuery.axZm.fullScreenCornerButton = false;
            jQuery.axZm.fullScreenExitText = false;

            // Chnage position of the map
            //jQuery.axZm.mapPos = 'bottomLeft';

            // Set mNavi buttons here if you want, can be done in the config file too
            if (typeof jQuery.axZm.mNavi == 'object'){
                jQuery.axZm.mNavi.enabled = true; // enable AJAX-ZOOM mNavi
                jQuery.axZm.mNavi.alt.enabled = true; // enable button descriptions
                jQuery.axZm.mNavi.fullScreenShow = true; // show at fullscreen too
                jQuery.axZm.mNavi.mouseOver = true; // should be alsways visible
                jQuery.axZm.mNavi.gravity = 'bottom'; // position of AJAX-ZOOM mNavi
                jQuery.axZm.mNavi.offsetVert = 5; // vertical offset
                jQuery.axZm.mNavi.offsetVertFS = 30; // vertical offset at fullscreen
                jQuery.axZm.mNavi.parentID = 'testCustomNavi';

                // Define order and space between the buttons
                if (jQuery.axZm.spinMod){ // if it is 360 or 3D
                    jQuery.axZm.mNavi.order = {
                        mSpin: 5, mPan: 20, mZoomIn: 5, mZoomOut: 20, mReset: 5, mMap: 5, mSpinPlay: 20, 
                        mCustomBtn4: 20, mCustomBtn1: 5, mCustomBtn2: 5, mCustomBtn3: 5
                    };
                }else{
                    jQuery.axZm.mNavi.order = {
                        mZoomIn: 5, mZoomOut: 5, mReset: 20, mGallery: 5, mMap: 20, 
                        mCustomBtn4: 20, mCustomBtn1: 5, mCustomBtn2: 5, mCustomBtn3: 5
                    };
                }

                // Define images for custom button to toggle Jcrop (see below)
                jQuery.axZm.icons.mCustomBtn1 = {
                    file: jQuery.axZm.buttonSet+'/button_iPad_jcrop', ext: 'png', w: 50, h: 50
                };
                jQuery.axZm.mapButTitle.customBtn1 = 'Toggle jCrop';

                // Define image for settings button
                jQuery.axZm.icons.mCustomBtn2 = {
                    file: jQuery.axZm.buttonSet+'/button_iPad_settings', ext: 'png', w: 50, h: 50
                };
                jQuery.axZm.mapButTitle.customBtn2 = 'jCrop settings';

                // Define image for 
                jQuery.axZm.icons.mCustomBtn3 = {
                    file: jQuery.axZm.buttonSet+'/button_iPad_fire', ext: 'png', w: 50, h: 50
                };        
                jQuery.axZm.mapButTitle.customBtn3 = 'Fire crop!';

                // Toggle jQuery.axZm.spinReverse
                jQuery.axZm.icons.mCustomBtn4 = {
                    file: jQuery.axZm.buttonSet+'/button_iPad_reverse', ext: 'png', w: 50, h: 50
                };
                jQuery.axZm.mapButTitle.customBtn4 = 'Toggle drag spin direction';

                // function when clicked on this custom button (mCustomBtn1)
                jQuery.axZm.mNavi.mCustomBtn1 = function(){
                    jQuery.aZcropEd.jCropMethod('toggle');
                    return false;
                };

                // Toggle Jcrop and AJAX-ZOOM thumbnail settings popup
                jQuery.axZm.mNavi.mCustomBtn2 = function(){
                    jQuery.aZcropEd.jCropSettingsPopup();
                    return false;
                };    

                // Function when clicked on the fire crop button
                jQuery.axZm.mNavi.mCustomBtn3 = function(){
                    jQuery.aZcropEd.jCropFire();
                    return false;
                };

                // Toggle jQuery.axZm.spinReverse
                jQuery.axZm.mNavi.mCustomBtn4 = function(){
                    if (jQuery.axZm.spinReverse){
                        jQuery.axZm.spinReverse = false;
                    }else{
                        jQuery.axZm.spinReverse = true;
                    }
                    return false;
                };
            }
        },

        onFullScreenSpaceAdded: function(){
                jQuery('#cropSlider')
                .css({
                    bottom: 0,
                    right: 0,
                    height: '100%',
                    zIndex: 555
                })
                .appendTo('#axZmFsSpaceRight');
        },

        onFullScreenStart: function(){
            jQuery.aZcropEd.jCropMethod('destroy');
        },

        onFullScreenClose: function(){
            jQuery.aZcropEd.jCropMethod('destroy');
            jQuery.fn.axZm.tapShow();

            jQuery('#cropSlider')
            .css({
                bottom: '',
                right: '',
                zIndex: ''
            })
            .appendTo('#cropSliderWrap');
        },
        onFullScreenCloseEndFromRel: function(){

            // Restore position of the slider
            jQuery('#cropSlider')
            .css({
                bottom: '',
                right: '',
                zIndex: ''
            })
            .appendTo('#cropSliderWrap');
        }
        
    };

    // Define the path to the axZm folder, adjust the path if needed!
    ajaxZoom.path = "<?php printTxt($axzm_path);?>"; 

    // Define your custom parameter query string
    // example=spinIpad has many presets for 360 images
    // 3dDir - best of all absolute path to the folder with 360/3D images
    // if it is a 2D image just pass 
    // zoomData=/path/to/your/image/image1.jpg|/path/to/other/image/image2.jpg instead of 3dDir
    // ajaxZoom.parameter = "example=spinIpad&3dDir=/pic/zoom3d/Uvex_Occhiali"; 
    // Define your custom parameter query string

    /*
    ajaxZoom.parameter = "example=imageCrop&zoomData=\
    /pic/zoom/furniture/furniture_005.jpg\
    |/pic/zoom/furniture/furniture_003.jpg\
    |/pic/zoom/boutique/boutique_001.jpg\
    |/pic/zoom/boutique/boutique_002.jpg\
    "; 
    */

    ajaxZoom.parameter = "example=imageCrop&3dDir=<?php printTxt($first_load360_dir); ?>";
    ajaxZoom.parameter += "&cmsMode=<?php printTxt($axzm_cms_mode); ?>";
    //ajaxZoom.parameter = "example=imageCrop&zoomDir=animals";

    ajaxZoom.divID = 'abc';
    
    if (jQuery.aZcropEd.playerResponsive){
        window.fullScreenStartSplash = {'enable': false, 'className': false, 'opacity': 0.75};
        jQuery.fn.axZm.openFullScreen(ajaxZoom.path, ajaxZoom.parameter, ajaxZoom.opt, ajaxZoom.divID, false, false);
    } else {
        // Load not responsive
        jQuery.fn.axZm.load({
            opt: ajaxZoom.opt,
            path: ajaxZoom.path,
            postMode: false,
            apiFullscreen: false,
            parameter: ajaxZoom.parameter,
            divID: ajaxZoom.divID
        });
    }

    // this is only for responsive editor layout
    window.thisLayoutAdjusted = false;
    var adjustThisLayout = function() {
        var winW = jQuery(window).innerWidth();
        var winH = jQuery(window).innerHeight();
        
        if (jQuery.aZcropEd.playerResponsive) {
            if (jQuery('#'+ajaxZoom.divID).height() + 150 > winH) {
                jQuery('#playerInnerWrap').css('minHeight', winH - 150);
                jQuery('#'+ajaxZoom.divID).css('height', winH - 150)
            } else if (jQuery('#'+ajaxZoom.divID).height() < 721 && winH < 721 + 150) {
                jQuery('#playerInnerWrap').css('minHeight', winH - 150);
                jQuery('#'+ajaxZoom.divID).height(winH - 150)
            }  else if (721 + 150 <= winH) {
                jQuery('#playerInnerWrap').css('minHeight', 721);
                jQuery('#'+ajaxZoom.divID).css('height', 721)
            }
        }

        if (winW >= 1490) {
            jQuery('#playerWrap').css({
                'float': 'left'
            });
            jQuery('#aZcR_tabs').css({
                'float': 'right', 
                marginTop: 35, 
                width: winW - jQuery('#playerWrap').outerWidth() - 50
            });
            jQuery('#outerWrap').css({
                margin: '', 
                width: '', 
                paddingLeft: 10, 
                paddingRight: 10
            });
            jQuery('#marginAfter').css({display: 'block'});
            window.thisLayoutAdjusted = true;
        }else{
            if (window.thisLayoutAdjusted){
                jQuery('#outerWrap').css({
                    margin: '0 auto',
                    width: jQuery('#playerWrap').outerWidth(),
                    paddingLeft: '',
                    paddingRight: ''
                });

                jQuery('#aZcR_tabs').css({'float': '', width: '', marginTop: 20});
                jQuery('#playerWrap').css({'float': ''});
                jQuery('#marginAfter').css({display: 'none'});
                //jQuery('#aZcomments').css({'float': 'left', width: 722})
                window.thisLayoutAdjusted = false;
            }
        }
    };

    jQuery(document).ready(function(){
        adjustThisLayout();
        setTimeout(adjustThisLayout, 1); // repeat once
        jQuery(window).bind('resize', adjustThisLayout);
        // Tabs can change document height
        jQuery('a[href^="#aZcR_tabs-"]').bind('click', adjustThisLayout);
    });

</script>
<?php
if (!$axzm_tpl_mode) {
?>
</body>
</html>
<?php
}
