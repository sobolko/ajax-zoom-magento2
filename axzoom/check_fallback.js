/**
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/check_fallback.js
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

if (!window.mageAzJsUrl) {
    mageAzJsUrl = '/js/axzoom/';
}

document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"check_jquery.js'%3E%3C/script%3E"));

if (!jQuery.isFunction(jQuery.mouseOverZoomInit)) {
    document.writeln(unescape("%3Clink rel='stylesheet' type='text/css' href='"+mageAzJsUrl+"axZm/axZm.css' %3E"));
    document.writeln(unescape("%3Clink rel='stylesheet' type='text/css' href='"+mageAzJsUrl+"axZm/extensions/axZmThumbSlider/skins/default/jquery.axZm.thumbSlider.css' %3E"));
    document.writeln(unescape("%3Clink rel='stylesheet' type='text/css' href='"+mageAzJsUrl+"axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoom.5.css' %3E"));
    document.writeln(unescape("%3Clink rel='stylesheet' type='text/css' href='"+mageAzJsUrl+"axZm/extensions/axZmMouseOverZoom/mods/jquery.axZm.mouseOverZoomMagento.5.css' %3E"));
    document.writeln(unescape("%3Clink rel='stylesheet' type='text/css' href='"+mageAzJsUrl+"axZm/extensions/jquery.axZm.expButton.css' %3E"));

    if (!jQuery.isFunction(jQuery.fancybox)) {
        document.writeln(unescape("%3Clink rel='stylesheet' type='text/css' href='"+mageAzJsUrl+"axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.css' %3E"));
        document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/plugins/demo/jquery.fancybox/jquery.fancybox-1.3.4.pack.js'%3E%3C/script%3E"));
    }

    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/jquery.axZm.js'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/axZmThumbSlider/lib/jquery.mousewheel.min.js'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/axZmThumbSlider/lib/jquery.axZm.thumbSlider.js'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/plugins/spin/spin.min.js'%3E%3C/script%3E"));

    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/jquery.axZm.expButton.js'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/jquery.axZm.imageCropLoad.js'%3E%3C/script%3E"));

    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoom.5.js'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/axZmMouseOverZoom/jquery.axZm.mouseOverZoomInit.5.js'%3E%3C/script%3E"));

    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/extensions/jquery.axZm.openAjaxZoomInFancyBox.js'%3E%3C/script%3E"));
    document.writeln(unescape("%3Cscript type='text/javascript' src='"+mageAzJsUrl+"axZm/plugins/JSON/jquery.json-2.3.min.js'%3E%3C/script%3E"));
}
