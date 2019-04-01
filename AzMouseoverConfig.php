<?php
/**
*  Module: jQuery AJAX-ZOOM for Magento, /app/code/local/Ax/Zoom/AzMouseoverConfig.php
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

$az_mouseover_config_magento = array(
    'vendor' => 'Magento',
    'exclude_opt_vendor' => array(
        'axZmPath',
        'lang',
        'images',
        'images360',
        'videos',
        'enableNativeSlider',
        'enableInTab',
        'enableInTabOpt',
        'enableCssInOtherPages',
        'default360settingsEmbed',
        'defaultVideoYoutubeSettings',
        'defaultVideoVimeoSettings',
        'defaultVideoDailymotionSettings',
        'defaultVideoVideojsSettings'
    ),
    'exclude_cat_vendor' => array('contents_settings'),
    'config_vendor' => array(
        'oneSrcImg' => true,
        'heightRatioOneImg' => 1.0,
        'zoomWidth' => '.product-shop|+1',
        'zoomHeight' => '.product-essential',
        'width' => 800,
        'height' => 800
    ),
    'config_extend' => array(
        'displayInSelector' => array(
            'prefix' => 'AJAXZOOM',
            'important' => true,
            'type' => 'string',
            'isJsObject' => false,
            'isJsArray' => false,
            'display' => 'text',
            'height' => null,
            'default' => '',
            'options' => null,
            'comment' => array(
                'EN' => '
                    Display only 360 player in jQuery selector defined in this field, 
                    e.g. .tab-content:eq(0) 
                    Keep empty to display 360 and images in one player.
                ',
                'DE' => '
                    Display only 360 player in jQuery selector defined. 
                    e.g. .tab-content:eq(0) 
                    Keep empty to display 360 and images in one player.
                '
            )
        ),
        'displayInSelectorAppend' => array(
            'prefix' => 'AJAXZOOM',
            'important' => false,
            'type' => 'bool',
            'isJsObject' => false,
            'isJsArray' => false,
            'display' => 'switch',
            'height' => null,
            'default' => true,
            'options' => null,
            'comment' => array(
                'EN' => '
                    If displayInSelector is set and this option is enabled, 
                    the 360 player will be appended to the container defined 
                    in displayInSelector option. If this option is disabled, 
                    the 360 player will be appended.
                ',
                'DE' => '
                    If displayInSelector is set and this option is enabled, 
                    the 360 player will be appended to the container defined 
                    in displayInSelector option. If this option is disabled, 
                    the 360 player will be appended.
                '
            )
        ),
        'displayInAzOpt' => array(
            'prefix' => 'AJAXZOOM',
            'important' => false,
            'type' => 'string',
            'isJsObject' => true,
            'isJsArray' => false,
            'display' => 'textarea',
            'height' => null,
            'default' => '{
    "mouseScrollEnable": true,
    "scroll": false,
    "spinNoInit": {
        "enabled": true
    }
}',
            'options' => null,
            'comment' => array(
                'EN' => '
                    Set AJAX-ZOOM options if "displayInSelector" or "displayInTab" options are enabled. 
                    For more details on how to set it, please see "azOptions360" option.
                ',
                'DE' => '
                    Set AJAX-ZOOM options if "displayInSelector" or "displayInTab" options are enabled. 
                    For more details on how to set it, please see "azOptions360" option.
                '
            )
        ),
        'magentoAllImages' => array(
            'prefix' => 'AJAXZOOM',
            'important' => false,
            'type' => 'bool',
            'isJsObject' => false,
            'isJsArray' => false,
            'display' => 'switch',
            'height' => null,
            'default' => false,
            'options' => null,
            'comment' => array(
                'EN' => '
                    Load all images independent on label (color), 
                    also from "Simple Products" which are bind 
                    to Configurable Product on start.
                ',
                'DE' => '
                    Load all images independent on label (color), 
                    also from "Simple Products" which are bind 
                    to Configurable Product on start.
                '
            )
        ),
        'magentoNoImage' => array(
            'prefix' => 'AJAXZOOM',
            'important' => false,
            'type' => 'bool',
            'isJsObject' => false,
            'isJsArray' => false,
            'display' => 'switch',
            'height' => null,
            'default' => false,
            'options' => null,
            'comment' => array(
                'EN' => '
                    Show magento no image available, 
                    if no AJAX-ZOOM no image available will be shown.
                ',
                'DE' => '
                    Show magento no image available, 
                    if no AJAX-ZOOM no image available will be shown.
                '
            )
        ),
        'magentoAdminThumb' => array(
            'prefix' => 'AJAXZOOM',
            'important' => false,
            'type' => 'bool',
            'isJsObject' => false,
            'isJsArray' => false,
            'display' => 'switch',
            'height' => null,
            'default' => false,
            'options' => null,
            'comment' => array(
                'EN' => '
                    Replace normal images in admin area with dynamically generated thumbs of these images
                ',
                'DE' => '
                    Replace normal images in admin area with dynamically generated thumbs of these images
                '
            )
        )
    )
);
