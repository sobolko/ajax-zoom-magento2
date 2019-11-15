/**
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/check_jquery_admin.php
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

;(function () {
    // IE < 9 indexOf hack for arrays
    if (!Array.prototype.indexOf) {
        Array.prototype.indexOf = function (elt /*, from*/) {
            var len = this.length >>> 0;
            var from = Number(arguments[1]) || 0;
            from = (from < 0) ? Math.ceil(from) : Math.floor(from);
            if (from < 0) {
                from += len;
            }

            for (; from < len; from++) {
                if (from in this && this[from] === elt) {
                    return from;
                }
            }

            return -1;
        };
    }

    var pathname = window.location.pathname, 
        basePath = '', pathArr = pathname.split('/');

    if (pathArr.length < 3) {
        pathArr = pathname.split('\\');
    }

    for (var i = 1; i < pathArr.indexOf('index.php'); i++) {
        basePath += '/' + pathArr[i];
    }

    if (!basePath){basePath = '/';} else {basePath += '/';} basePath = basePath.replace(/\/\/+/g, '\/');

    // If jQuery is not defined inject jQuery 1.11.3 core with jQuery.noConflict() and jquery-migrate 1.2.1
    if ((typeof jQuery === 'undefined') && !window.jQuery) {
        document.write(unescape("%3Cscript type='text/javascript' src='"+basePath+"js/axzoom/jquery-1.11.3.min.js'%3E%3C/script%3E"));
        document.write(unescape("%3Cscript type='text/javascript'%3EjQuery.noConflict();%3C/script%3E"));  
        document.write(unescape("%3Cscript type='text/javascript' src='"+basePath+"js/axzoom/jquery-migrate-1.2.1.min.js'%3E%3C/script%3E"));
    } else {
        // Include jquery-migrate 1.2.1 as it can be missing
        document.write(unescape("%3Cscript type='text/javascript' src='"+basePath+"js/axzoom/jquery-migrate-1.2.1.min.js'%3E%3C/script%3E"));
    }
})();
