/*!
*  Module: jQuery AJAX-ZOOM for Magento, /js/axzoom/axzoom.js
*  Copyright: Copyright (c) 2010-2018 Vadim Jacobi
*  License Agreement: http://www.ajax-zoom.com/index.php?cid=download
*  Version: 1.4.2
*  Date: 2018-05-28
*  Review: 2018-05-28
*  URL: http://www.ajax-zoom.com
*  Documentation: http://www.ajax-zoom.com/index.php?cid=modules&module=magento
*
*  @author    AJAX-ZOOM <support@ajax-zoom.com>
*  @copyright 2010-2018 AJAX-ZOOM, Vadim Jacobi
*  @license   http://www.ajax-zoom.com/index.php?cid=download
*/

window.preRenderAxzoom = function() {
    if (jQuery.axZm_psh.displayInSelector) {
        if (!(jQuery.isEmptyObject(jQuery.axZm_psh.IMAGES_360_JSON)
            && jQuery.isEmptyObject(jQuery.axZm_psh.IMAGES_JSON)
            && !jQuery.isPlainObject(jQuery.axZm_psh.VIDEOS_JSON)
        )) {

            jQuery(document).ready(function() {
                jQuery.axZm_psh.initParam.axZmMode = true;
                jQuery('<div id="axzoom_head" class="h2 axzoom_head">'+jQuery.axZm_psh.headSelector+'</div>')
                [jQuery.axZm_psh.displayInSelectorAppend ? 'appendTo' : 'prependTo'](jQuery.axZm_psh.displayInSelector);

                jQuery('#'+jQuery.axZm_psh.containerId)
                .css('display', '')
                .insertAfter('#axzoom_head');

                window.renderAxzoom();
            });

        } else {
            jQuery('#'+jQuery.axZm_psh.containerId).remove();
        }
    } else {
        window.renderAxzoom();
    }
};

window.renderAxzoom = function() {
Array.prototype.indexOf||(Array.prototype.indexOf=function(c,d){var b=this.length>>>0,a=Number(d)||0;a=0>a?Math.ceil(a):Math.floor(a);for(0>a&&(a+=b);a<b;a++)if(a in this&&this[a]===c)return a;return-1});

(function($) {
    var tofckr = null;

    // Need that later for not reloading if images are the same
    $.axZm_psh.IMAGES_AND_360_LOADED = JSON.stringify( {
        images: $.extend(true, {}, $.axZm_psh.IMAGES_JSON),
        images360: $.extend(true, {}, $.axZm_psh.IMAGES_360_JSON),
        videos: $.extend(true, {}, $.axZm_psh.VIDEOS_JSON)
    } );

    var addImgHotspots = function(o) {
        var hs = $.axZm_psh.IMAGES_HOTSPOTS;
        if ($.isPlainObject(hs) && !$.isEmptyObject(hs) && $.isPlainObject(o)) {
            $.each(o, function(k, v) {
                var f = v.img.replace(/^.*[\\\/]/, '');
                $.each(hs, function(idm, d) {
                    if (d.image_name == f) {
                        try {
                            o[k]['hotspotFilePath'] = $.parseJSON(d.hotspots);
                        } catch(err) {
                            console.log('Failed to parse hotspots for media id: '+idm+', file name: '+f);
                        }

                        return false;
                    }
                } );
            } );
        }

        return o;
    };

    $.axZm_psh.visIntervalInit = function() {
        if ($.axZm_psh.visInt) {
            clearInterval($.axZm_psh.visInt);
        }

        var state = $('#axZm_mouseOverWithGalleryContainer').is(':visible');
        $.axZm_psh.visInt = setInterval(function() {
            var vis = $('#axZm_mouseOverWithGalleryContainer').is(':visible');
            if (state != vis && vis) {
                $(window).trigger('resize');
                if ($.axZm) {
                    $.fn.axZm.resizeStart(1);
                }
            }

            state = vis;
        }, 1000);
    };

    // Swatches
    $.axZm_psh.resetAxZoom = function() {
        if (JSON.stringify( {
            images: $.axZm_psh.IMAGES_JSON,
            images360: $.axZm_psh.IMAGES_360_JSON,
            videos: $.axZm_psh.VIDEOS_JSON
            } ) == $.axZm_psh.IMAGES_AND_360_LOADED
        ) {
            return;
        }

        $.axZm_psh.IMAGES_AND_360_LOADED = JSON.stringify( {
            images: $.axZm_psh.IMAGES_JSON, 
            images360: $.axZm_psh.IMAGES_360_JSON,
            videos: $.axZm_psh.VIDEOS_JSON
        });

        $.mouseOverZoomInit.replaceImages( {
            divID: $.axZm_psh.divID,
            galleryDivID: $.axZm_psh.galleryDivID,
            images: addImgHotspots($.axZm_psh.IMAGES_JSON),
            images360: $.axZm_psh.IMAGES_360_JSON,
            videos: $.axZm_psh.VIDEOS_JSON
        });
    };

    // Change values
    $.axZm_psh.updateAxZoomAction = function(idxSel, checkout) {
        if ( 
            ($.isPlainObject(idxSel.images) && !$.isEmptyObject(idxSel.images))
            || ($.isPlainObject(idxSel.images360) && !$.isEmptyObject(idxSel.images360))
        ) {
            // Find 360 if not passed from associated
            if (idxSel.images360 && !$.isPlainObject(idxSel.images360)) {
                $.each($.axZm_psh.IMAGES_360_JSON, function(k, v) {
                    if (($.isArray(v.combinations) && v.combinations.length == 0)
                        || (v.combinations && $.isArray(v.combinations) 
                            && $.isArray(v.combinations[0]) && v.combinations[0].length == 0)
                    ) {
                        if (!$.isPlainObject(idxSel.images360)) {
                            idxSel.images360 = {};
                        }

                        idxSel.images360[k] = v;
                    }
                });
            };

            // add not associated videos
            if (idxSel.videos && !$.isPlainObject(idxSel.videos)) {
                $.each($.axZm_psh.VIDEOS_JSON, function(k, v) {
                    if (($.isArray(v.combinations) && v.combinations.length == 0)
                        || (v.combinations && $.isArray(v.combinations) 
                            && v.combinations[0].length == 0)
                    ) {
                        if (!$.isPlainObject(idxSel.videos)) {
                            idxSel.videos = {};
                        }

                        idxSel.videos[k] = v;
                    }
                });
            }

            if (JSON.stringify({images: idxSel.images, images360: idxSel.images360, videos: idxSel.videos}) == $.axZm_psh.IMAGES_AND_360_LOADED) {
                return;
            }

            $.axZm_psh.IMAGES_AND_360_LOADED = JSON.stringify({images: idxSel.images, images360: idxSel.images360, videos: idxSel.videos});

            if (checkout) {
                $.axZm_psh.initParam.images = addImgHotspots(idxSel.images);
                $.axZm_psh.initParam.images360 = idxSel.images360;
                $.axZm_psh.initParam.videos = idxSel.videos;
                $.mouseOverZoomInit($.axZm_psh.initParam);
            } else {
                $.mouseOverZoomInit.replaceImages( {
                    divID: $.axZm_psh.divID,
                    galleryDivID: $.axZm_psh.galleryDivID,
                    images: addImgHotspots(idxSel.images),
                    images360: idxSel.images360,
                    videos: idxSel.videos
                });
            }
        } else { // show initial images
            $.axZm_psh.resetAxZoom();
        }
    };

    $.axZm_psh.updateAxZoom = function(e, check) {

        var obj = $(this);
        var requestSet = function() {
            if (!spConfig) {
                return;
            }

            if (!check && e && e.type == 'change' && obj.val() == '') {
                $.axZm_psh.resetAxZoom();
                return;
            }

            var pairs = [];
            var inWhichObj = (check ? (spConfig.values ? spConfig.values : spConfig.state) : spConfig.state);
            for (var k in inWhichObj) {
                if (typeof spConfig.state[k] !== 'function'
                    && typeof spConfig.state[k] !== 'object'
                    && (spConfig.state[k] != false
                    || (spConfig.values && check))
                ) {
                    pairs.push(k + ':' + inWhichObj[k]);
                }
            }

            var idx = pairs.join(',');
            if (!check && !idx && e && e.type == 'click') {
                tofckr = setTimeout(function() {
                    $.axZm_psh.updateAxZoom(e);
                }, 100);
                return;
            }

            // Full pair
            var idxSel = $.axZm_psh.axAssociated[idx];

            if (check) {
                return idxSel;
            }

            if (idxSel) {
                $.axZm_psh.updateAxZoomAction(idxSel);
            } else {
                // try to find whatever
                var foundSmth;
                var triggered = false;
                $.each($.axZm_psh.axAssociated, function(k, v) {
                    if (k.indexOf(idx) != -1) {
                        foundSmth = v;
                        if ($.isPlainObject(v.images360) || $.isPlainObject(v.videos)) {
                            triggered = true;
                            $.axZm_psh.updateAxZoomAction(v);
                            return false;
                        }
                    }
                } );

                if (foundSmth && !triggered) {
                    $.axZm_psh.updateAxZoomAction(foundSmth);
                }
            }
        };

        if (check) {
            return requestSet();
        } else {
            try {
                window.clearTimeout(tofckr);
                tofckr = null;
            } catch(err) {
            }

            setTimeout(function() {
                requestSet();
            }, 50);
        }

    };

    // Shortcut for maybe other applications or additional functionality
    // window.resetAxZoom() will reset AJAX-ZOOM to the state when article is loaded without attribute selection
    window.resetAxZoom = $.axZm_psh.resetAxZoom;
    window.updateAxZoom = $.axZm_psh.updateAxZoom;

    // Init AJAX-ZOOM
    if ($.axZm_psh.displayInSelector) {
        if ($.axZm_psh.initParam.azOptions360) {
            $.axZm_psh.initParam.azOptions360 = $.extend(true, {}, $.axZm_psh.initParam.azOptions360, $.axZm_psh.displayInAzOpt);
        } else {
            $.axZm_psh.initParam.azOptions360 = $.extend(true, {}, $.axZm_psh.displayInAzOpt);
        }

        if ($.axZm_psh.initParam.azOptions) {
            $.axZm_psh.initParam.azOptions = $.extend(true, {}, $.axZm_psh.initParam.azOptions, $.axZm_psh.displayInAzOpt);
        } else {
            $.axZm_psh.initParam.azOptions = $.extend(true, {}, $.axZm_psh.displayInAzOpt);
        }
    }

    $.axZm_psh.initParam.images = addImgHotspots($.axZm_psh.initParam.images);
    $.mouseOverZoomInit($.axZm_psh.initParam);

    if ($.axZm_psh.displayInSelector) {
        $.axZm_psh.visIntervalInit();
    }

    var bsl = 0;
    var bsas = 0;
    window.setTimeout(function() {
        if (!bsl && $('.swatch-label').length) {
            bsl = 1;
            $('.swatch-label').click($.axZm_psh.updateAxZoom);
        }
    }, 1);

    window.setTimeout(function() {
        if (!bsas && $('.super-attribute-select').length) {
            bsas = 1;
            $('.super-attribute-select').change($.axZm_psh.updateAxZoom);
        }
    }, 1);

   $(document).ready(function() {
        if (!bsl) {
            bsl = 1;
            $('.swatch-label').click($.axZm_psh.updateAxZoom);
        }

        if (!bsas) {
            bsas = 1;
            $('.super-attribute-select').change($.axZm_psh.updateAxZoom);
        }

        if (window.spConfig && spConfig.values) {
            var aaa = window.updateAxZoom(null, true);
            if (aaa) {
                jQuery.axZm_psh.updateAxZoomAction(aaa);
            }
        }
   } );

   if (window.ConfigurableMediaImages && !jQuery.axZm_psh.displayInSelector) {
        window.ConfigurableMediaImages.getImageObject = function(productId, imageUrl) {
            var key = productId+'-'+imageUrl;
            if(!ConfigurableMediaImages.imageObjects[key]) {
                var image = $('<img />');
                image.attr('src', jQuery.axZm_psh.axZmPath + 'icons/empty.gif');
                ConfigurableMediaImages.imageObjects[key] = image;
            }

            return ConfigurableMediaImages.imageObjects[key];
        };
   }

    /*
    $(document).ready(function() {
        $('.swatch-label').click($.axZm_psh.updateAxZoom); // version 1.9.1 or more
        $('.super-attribute-select').change($.axZm_psh.updateAxZoom);

        if (window.spConfig && spConfig.values) {
            var aaa = window.updateAxZoom(null, true);
            if (aaa) {
                jQuery.axZm_psh.updateAxZoomAction(aaa);
            }
        }
    } );
    */

} )(jQuery);
};