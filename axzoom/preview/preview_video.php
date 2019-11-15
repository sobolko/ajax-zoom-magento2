<?php
/**
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/preview/preview_video.php
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

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//ini_set('memory_limit', '5G');
//error_reporting(E_ALL);

use Magento\Framework\App\Bootstrap;
require '../../../app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$objectManager = $bootstrap->getObjectManager();

$request = $objectManager->get('Magento\Framework\App\Request\Http');
$scopeConfig = $objectManager->get('\Magento\Framework\App\Config\ScopeConfigInterface');
$conf = $scopeConfig->getValue('axzoom_options', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
$id_video = getValueMod('id_video');
$video = $objectManager->create('\Ax\Zoom\Model\Axvideo')->load($id_video)->getData();
$videojs_src = (array)json_decode($conf['plugin_settings']['defaultVideoVideojsJS'], true);

function getIssetMod($par)
{
    global $request;
    $val = $request->getParam($par);
    return isset($val);
}

function getValueMod($par)
{
    global $request;
    return $request->getParam($par);
}

function printTxt($a)
{
?>
<?=$a?>
<?php
}

if (!getIssetMod('id_video')) {
    //Mage::throwException('No parameters passed');
    // !!!
    echo ('No parameters passed');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>AJAX-ZOOM Preview</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta name="viewport" content="width=device-width,  minimum-scale=1, maximum-scale=1, user-scalable=no">

<style type="text/css"> 
    html {height: 100%; width: 100%; font-family: Tahoma, Arial; font-size: 10pt; margin: 0; padding: 0;}
    body {height: 100%; width: 100%; overflow: hidden; margin: 0; padding: 0;} 
    body:-webkit-fullscreen {width: 100%; height: 100%;}
    body:-ms-fullscreen {width: 100%; height: 100%;}
    a {color: blue; outline: 0; outline-style: none; text-decoration: none;} 
    a:visited {color: blue;} a:hover {color: green;}
    h2 {padding:0px; margin: 35px 0px 15px 0px; font-size: 22px;}
    h3 {font-family: Arial; color: #1A4A7A; font-size: 18px; padding: 20px 0px 3px 0px; margin: 0;}
    p {text-align: justify; text-justify: newspaper;}
    iframe, video {width: 100%; height: 100%;}
</style>

<script src="../axZm/plugins/jquery-1.8.3.min.js"></script>
<?php
if ($video['type'] == 'videojs' && (int)$conf['video_settings']['videoHtml5VideoJs'] == 'true') {
    if (isset($videojs_src['css1']) && $videojs_src['css1']) {
        printTxt('<link href="'.$videojs_src['css1'].'" rel="stylesheet">');
    }

    if (isset($videojs_src['css2']) && $videojs_src['css2']) {
        printTxt('<link href="'.$videojs_src['css2'].'" rel="stylesheet">');
    }
}
?>

</head>

<body>
<?php switch ($video['type']): ?>
<?php case 'youtube': ?>
<iframe src="https://www.youtube.com/embed/<?php printTxt($video['uid']); ?>" style="width: 100%; height: 100%;" 
    width="100%" height="100%" frameborder="0" 
    webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<?php break;?>

<?php case 'vimeo': ?>
<iframe src="https://player.vimeo.com/video/<?php printTxt($video['uid']); ?>?color=ff6944&title=0&byline=0&portrait=0" 
    style="width: 100%; height: 100%;" width="100%" height="100%" frameborder="0" 
    webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<?php break;?>

<?php case 'dailymotion': ?>
<iframe frameborder="0" src="//www.dailymotion.com/embed/video/<?php printTxt($video['uid']); ?>" 
    allowfullscreen></iframe>
<?php break;?>

<?php case 'videojs': ?>
<video id="my-video" class="video-js" controls preload="auto" width="100%" height="100%" 
    data-setup="{}" style="width: 100%; height: 100%;">

<source src="<?php printTxt($video['uid']); ?>" 
    type='video/<?php printTxt(pathinfo($video['uid'], PATHINFO_EXTENSION)); ?>'>
</video>
<?php break;?>
<?php endswitch; ?>

<?php
if ($video['type'] == 'videojs' && (int)$conf['video_settings']['videoHtml5VideoJs'] == 'true') {
    if (isset($videojs_src['js1']) && $videojs_src['js1']) {
        printTxt('<script src="'.$videojs_src['js1'].'"></script>');
    }

    if (isset($videojs_src['js2']) && $videojs_src['js2']) {
        printTxt('<script src="'.$videojs_src['js2'].'"></script>');
    }

    if (isset($videojs_src['js3']) && $videojs_src['js3']) {
        printTxt('<script src="'.$videojs_src['js3'].'"></script>');
    }
}
?>

<script type="text/javascript">
window.HELP_IMPROVE_VIDEOJS = false;
</script>
</body>
</html>