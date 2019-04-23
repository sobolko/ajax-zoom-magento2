<?php
/**
*  AzMouseoverSettings.php
*  Copyright: Copyright (c) 2010-2018 Vadim Jacobi
*  License Agreement: http://www.ajax-zoom.com/index.php?cid=download
*  Version: 1.0.20
*  Date: 2018-08-26
*  Review: 2018-08-26
*  URL: http://www.ajax-zoom.com
*  Documentation: http://www.ajax-zoom.com/index.php?cid=docs
*
*  @author    AJAX-ZOOM <support@ajax-zoom.com>
*  @copyright 2010-2018 AJAX-ZOOM, Vadim Jacobi
*  @license   http://www.ajax-zoom.com/index.php?cid=download
*/

if (!class_exists('AzMouseoverSettings', false)) {

    class AzMouseoverSettings
    {
        public $vendor = '';
        public $last_updated = '2018-08-26';
        public $config = array();
        public $items = array();
        public $config_vendor = array();
        public $config_extend = array();
        public $categories = array();
        public $exclude_opt_vendor = array('axZmPath', 'lang', 'images', 'images360', 'videos');
        public $exclude_cat_vendor = array();

        public function __construct()
        {
            $arguments = func_get_args();

            if (!empty($arguments) && is_array($arguments[0])) {
                foreach ($arguments[0] as $key => $property) {
                    if (property_exists($this, $key)) {
                        $this->{$key} = $property;
                    }
                }
            }

            $this->defineCategories();
            $this->defineConfig();
            $this->extendConfig();
        }

        public function cleanComment($comment)
        {
            $comment = str_replace(array("\r\n", "\r", "\n", "\t"), ' ', $comment);
            $comment = preg_replace('!\s+!', ' ', $comment);
            return trim($comment);
        }

        public function defineCategories()
        {
            $this->categories = array(
                'plugin_settings' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => 'Plugin settings for '.$this->vendor,
                        'DE' => 'Plugin Einstellungen für '.$this->vendor
                    )
                ),
                'contents_settings' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => 'Contents settings',
                        'DE' => 'Inhalts Einstellungen'
                    )
                ),
                'general_settings' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => 'General settings',
                        'DE' => 'Allgemeine Einstellungen'
                    )
                ),
                'product_tour' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => '360 "Product tour"',
                        'DE' => '360 "Produkt Tour"'
                    )
                ),
                'fullscreen_gallery' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => 'Fullscreen gallery',
                        'DE' => 'Vollbild Galerie'
                    )
                ),
                'mouseover' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => 'Specific options for the mouseover zoom',
                        'DE' => 'Spezifische optionen für mousever zoom'
                    )
                ),
                'video_settings' => array(
                    'prefix' => 'AZ_CAT',
                    'title' => array(
                        'EN' => 'Video settings',
                        'DE' => 'Videos Einstellungen'
                    )
                )

            );
        }

        public function defineItemes()
        {
            $this->items = array(
                'images' => array(
                    'prefix' => '',
                    'category' => 'contents_settings',
                    'important' => true,
                    'type' => 'object, array',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => null,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
    Object containing absolute paths to the master images, optional with titles. Start with 1, not 0. 
    Your master image "img" can be as big as you want, it never loads into cache 
    You can also protect the directory with .htaccess or other http access restrictions. 

    <pre class="language-js"><code class="language-js">
    images: {
        1: {
            img: "/pic/fashion_001.jpg", 
            title: "Test Title 1"
        },
        2: {
            img: "/pic/fashion_002.jpg", 
            title: "Test Title 2"
        },
        3: {
            img: "/pic/fashion_003.jpg", 
            title: "Test Title 3"
        }
    }
    </code></pre>

    Alternatively you can link to your static images with these keys: <br /> <br />
    <ul>
        <li>"zoom" - big mouseover image </li>
        <li>"preview" - preview image </li>
        <li>"thumb" - image for the gallery </li>
        <li>"img" - your master image is needed anyway to open AJAX-ZOOM on click; 
            this image can be in a directory with restricted access over http;
        </li>
        <li>"title" - optional title</li>
        <li>"hotspotFilePath" - Optionally you can define "hotspotFilePath" for each images 
            which is the path to the file containing data for hotspots. 
            For more information see <a href="http://www.ajax-zoom.com/examples/example33.php">example33.php</a> 
            or JSON / JS object with hotspots configuration. The hotspots will be 
            visible in fullscreen or responsive fancybox.
        </li>
    </ul>
    <br /> <br />
    So the images object would look like this:
    <pre class="language-js"><code class="language-js">
    images: {
        1: {
            zoom: "/cache/fashion_001_1200x1200.jpg",
            preview: "/cache/fashion_001_350x400.jpg",
            thumb: "/cache/fashion_001_80x100.jpg"
            img: "/pic/fashion_001.jpg", 
            title: "Test Title 1"
        },
        2: {
            zoom: "/cache/fashion_002_1200x1200.jpg",
            preview: "/cache/fashion_002_350x400.jpg",
            thumb: "/cache/fashion_002_80x100.jpg",
            img: "/pic/fashion_002.jpg", 
            title: "Test Title 2"
        },
        3: {
            zoom: "/cache/fashion_003_1200x1200.jpg",
            preview: "/cache/fashion_003_350x400.jpg",
            thumb: "/cache/fashion_003_80x100.jpg",
            img: "/pic/fashion_003.jpg", 
            title: "Test Title 3"
        }
    }
    </code></pre>
     
    In case "zoom", "preview" and "thumb" are not defined, 
    AJAX-ZOOM will generate these images out of "img" instantly on-the-fly.
    <br /> <br /> 
    "images" option can be also of type array, e.g. 
    <pre class="language-js"><code class="language-js">
        images: [
            {    img: "/pic/fashion_001.jpg", 
                title: "Test Title 1"
            },
            {    img: "/pic/fashion_002.jpg", 
                title: "Test Title 2"
            },
            {    img: "/pic/fashion_003.jpg", 
                title: "Test Title 3"
            }
        ]
    </code></pre>

                        ',
                        'DE' => ''
                    )
                ),

                'images360' => array(
                    'prefix' => '',
                    'category' => 'contents_settings',
                    'important' => true,
                    'type' => 'object, array',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => null,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
    Object or an array with paths to the folders which contain 360 degree images; 
    mostly you would want to add only one 360 spin but the "images360" object can contain as many as you like; 

    <pre class="language-js"><code class="language-js">
    images360: {
        1: {
            path: "/pic/zoom3d/Uvex_Occhiali", 
            position: "first"
        }
    }
    </code></pre>

    Other possible keys are: <br /> <br />
    <ul>
        <li>"thumb" - direct link to thumb image displayed in the gallery. 
            In case "thumb" is not present AJAX-ZOOM will internally request 
            first image of your 360 and generate the thumb. 
            You can disable this by setting "images360Thumb" option below to 
            false so you will only get a 360 degree icon which is ok too.
        </li>
        <li>"thumbImg" - instead of "thumb" you can also directly link to an image under "path" out of which 
            AJAX-ZOOM will generate the gallery thumb
        </li>
        <li>"title" - optional title</li>
        <li>"hotspotFilePath" - optional path to the file which contains data for hotspots. For more information see 
            <a href="http://www.ajax-zoom.com/examples/example33.php">example33.php</a> 
            or JSON / JS object with hotspots configuration.
        </li>
        <li>"crop" - JSON generated with <a href="http://www.ajax-zoom.com/examples/example35.php">example35.php</a> 
            or path to the file (static or dynamically generated) which contains this JSON.
        </li>
        <li>"is3D" - not needed; tells the plugin that the path is multilevel 360 spherical or half spherical 3D. 
        </li>
        <li>"frame" - the frame number for thumbnails in the gallery and from which frame the 360 should start. 
        </li>
    </ul>

    <br /><br />Most AJAX-ZOOM main script options can be set with JavaScript. 
    You could define these options for each 360 individually, e.g. <br /> <br />
    <ul>
        <li>"spinReverse" - reverse the direction of the spin</li>
        <li>"spinDemoTime" - time in ms during which AJAX-ZOOM will make one turn</li>
        <li>"someThingElse..." - any other key</li>
    </ul>
                        ',
                        'DE' => ''
                    )
                ),
                'videos' => array(
                    'prefix' => '',
                    'category' => 'contents_settings',
                    'important' => true,
                    'type' => 'object, array',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => null,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
    Object containing videos configuration. Start with 1, not 0. 

    <pre class="language-js"><code class="language-js">
    videos: {
        1: {key: "YmcyTNWs9_Q", type: "youtube", position: "last", thumbImg: false},
        2: {key: "171617419", type: "vimeo", position: "last", thumbImg: false},
        3: {key: "x20g8k1", type: "dailymotion", position: "last", thumbImg: false, title: "Lorem..."},
        4: {
            key: "http://vjs.zencdn.net/v/oceans.mp4", 
            type: "html5", position: "last", 
            thumbImg: false, 
            settings: {}, 
            title: "Lorem..."
        }
    }
    </code></pre>

    Configuration keys: <br /> <br />
    <ul>
        <li>"key" - key of the video or url for html5</li>
        <li>"type" - type of the video, possible values are youtube, vimeo, dailymotion or html5 </li>
        <li>"position" - position in the gallery (first, last, afterFirst)</li>
        <li>"thumbImg" - link to thumbnail / image. For youtube / vimeo / dailymotion they can be received instanly, 
            not need to be deifned
        </li>
        <li>"settings" - for html5 you can define several options over "settings" which is an object too. 
            This will extend the global settings for html5 player.
            In e-commerce plugins these settings can be also set individually for each video over backend. 
        </li>
        <li>"title" - optional title</li>
    </ul>
                        ',
                        'DE' => ''
                    )
                )

            );
        }

        public function defineConfig()
        {
            /*
            'OPTION' => array(
                'prefix' => 'AJAXZOOM',
                'category' => 'plugin_settings',
                'vendor' => array(),
                'important' => false,
                'useful' => false,
                'type' => 'bool',
                'isJsObject' => false,
                'isJsArray' => false,
                'display' => 'switch',
                'height' => null,
                'default' => false,
                'options' => null,
                'comment' => array(
                    'EN' => '

                    ',
                    'DE' => '

                    '
                )
            )
            */

            $this->config = array(

                // plugin_settings
                'enableInFrontDetail' =>  array(
                    'prefix' => 'AJAXZOOM', // prefix AJAXZOOM OR AZ
                    'category' => 'plugin_settings', // key $this->categories
                    'important' => false, // true / false
                    'type' => 'bool', // bool, int, float, string, object, array, mixed, function
                    'isJsObject' => false, // true, false
                    'isJsArray' => false, // true, false
                    'display' => 'switch', // text, textarea, switch, select
                    'height' => null, // height of textarea if applied
                    'default' => true, // default value
                    'options' => null, // array for select
                    'comment' => array(
                        'EN' => '
                            Eneable / disable AJAX-ZOOM output in product detail view.
                        ',
                        'DE' => '
                            Aktivieren / deaktivieren AJAX-ZOOM Output in Produktdetailansicht.
                        '
                    )
                ),

                'enableCssInOtherPages' =>  array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
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
                            Add a small css file to frontend for embedding AJAX-ZOOM in responsive iframe.
                        ',
                        'DE' => '
                            Eine kleine css Datei weiteren Shopbereichen hinzufügen, um AJAX-ZOOM 360 Player 
                            im responsiven iframe einzubetten.
                        '
                    )
                ),

                'enableNativeSlider' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'vendor' => array('Shopware 5'),
                    'important' => true,
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Eneable native '.$this->vendor.' slider for all products 
                            instead of AJAX-ZOOM mouseover gallery. 
                            The 360s will be injected into this native Shopware 5 gallery. 
                            Please note that you might need to clear shop teplate cache to see the results.
                        ',
                        'DE' => '
                            Den default '.$this->vendor.' Slider / Galerie 
                            anstatt von AJAX-ZOOM Mouseover Zoom für alle Produkte aktivieren. 
                            Die 360 Animationen werden in diesem Slider automatisch dargestellt.
                            Bitte beachten Sie, dass Sie eventuell den Shop-Teplate-Cache löschen müssen, 
                            um die Ergebnisse zu sehen.
                        '
                    )
                ),

                'enableInTab' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'vendor' => array('Shopware 5'),
                    'important' => true,
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Display 360 / 3D / videos in tab contant. 
                            Please note that you might need to clear shop teplate cache to see the results.
                        ',
                        'DE' => '
                            Anzeige von 360 / 3D / Videos im Registerinhalt. 
                            Bitte beachten Sie, dass Sie eventuell den Shop-Teplate-Cache löschen müssen, 
                            um die Ergebnisse zu sehen.
                        '
                    )
                ),

                'enableInTabOpt' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'vendor' => array('Shopware 5'),
                    'important' => false,
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => null,
                    'default' => '{
    "ajaxZoomOpenMode": "fullscreen",
    "thumbSliderPosition": "bottom",
    "maxSizePrc": "1.0|-200",
    "azOptions360": {
        "mouseScrollEnable": false,
        "mouseScrollEnableFS": false,
        "mNavi": {
            "enabled": true,
            "gravity": "bottomLeft",
            "order": {
                "mZoomOut": 5,
                "mZoomIn": 15
            }
        }
    }
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Override default options if "enableInTab" is activated 
                            globally or set for particular product.
                        ',
                        'DE' => '
                            Überschreiben der Standardoptionen, wenn "enableInTab" global aktiviert ist 
                            oder für ein bestimmtes Produkt gesetzt ist.
                        '
                    )
                ),

                'displayOnlyForThisProductID' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            CSV with product IDs for which AJAX-ZOOM will be <b>only</b> enabled. 
                            Leave blank to have it enabled for all products! 
                            This option can be useful e.g. if you want to make A/B tests and enable 
                            AJAX-ZOOM only for certain products, e.g. 7,15 would 
                            enable AJAX-ZOOM only for products with ID 7 and 15.
                        ',
                        'DE' => '
                            CSV mit Produkt-IDs für die AJAX-ZOOM ausschließlich aktiviert wird.
                            Lassen Sie das Feld leer um AJAX-ZOOM für alle Produkte zu aktivieren!
                            Diese Option ist nützlich z.B. wenn Sie A / B - Tests durchführen wollen, 
                            z.B. 7,15 würde AJAX-ZOOM nur für Produkte mit ID 7 und 15 freischalten.
                        '
                    )
                ),

                'default360settings' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{
    "position": "first",
    "spinReverse": "true",
    "spinBounce": "false",
    "spinDemoRounds": "3",
    "spinDemoTime": "4500"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Default configuration settings when importing / creating new 360 over backend.
                        ',
                        'DE' => '
                            Die Standardeinstellungen beim Import / Erstellen neuer 360 über das Backend.
                        '
                    )
                ),

                'default360settingsEmbed' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{
    "fullScreenCornerButton": "true",
    "fullScreenCornerButtonTouch": "false",
    "scroll": "false",
    "mouseScrollEnable": "true"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Default 360 degree configuration settings settings when embedded into 
                            CMS / Blog and simmilar 
                            (not product detail page). 
                        ',
                        'DE' => '
                            Die Standardeinstellungen für das Einbetten der 360 Grad Animationen in CMS / Blog  
                            oder ähnlich (nicht Produkt Detailseite). 
                        '
                    )
                ),

                'defaultVideoYoutubeSettings' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Default configuration settings for new YouTube video.
                        ',
                        'DE' => '
                            Standard-Konfigurationseinstellungen für neues YouTube Video.
                        '
                    )
                ),

                'defaultVideoVimeoSettings' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Default configuration settings for new Vimeo video.
                        ',
                        'DE' => '
                            Standard-Konfigurationseinstellungen für neues Vimeo Video.
                        '
                    )
                ),

                'defaultVideoDailymotionSettings' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Default configuration settings for new Dailymotion video.
                        ',
                        'DE' => '
                            Standard-Konfigurationseinstellungen für neues Dailymotion Video.
                        '
                    )
                ),

                'defaultVideoVideojsSettings' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Default configuration settings for new HTML5 (videojs) video.
                        ',
                        'DE' => '
                            Standard-Konfigurationseinstellungen für neues HTML5 (videojs) Video.
                        '
                    )
                ),

                'defaultVideoVideojsJS' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "css1": "//vjs.zencdn.net/5.11.9/video-js.css",
    "css2": "",
    "js1": "//vjs.zencdn.net/ie8/1.1.2/videojs-ie8.min.js",
    "js2": "//vjs.zencdn.net/5.11.9/video.js",
    "js3": ""
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Sources for videojs CSS and JS which will be included in frontend template.
                        ',
                        'DE' => '
                            Quellen für Videojs CSS und JS Dateien, die in der Frontend-Vorlage enthalten sein werden.
                        '
                    )
                ),

                'pngModeCssFix' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'plugin_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Include CSS file for making 
                            some areas of the player transparent which only makes sense 
                            if pngMode option is set to auto or true and you are 
                            really using transparent png images.
                            ',
                        'DE' => '
                            Eine CSS Datei einschließen, die bestimmte Bereiche des Players transparent macht, 
                            was nur Sinn macht, wenn pngMode eingeschaltet ist (auto, true) und Sie im Shop 
                            tatsächlich fast ausschließlich PNG Bilder mit transparenten Bereichen verwenden.
                        '
                    )
                ),

                // general_settings
                'axZmPath' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'auto',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Path to AJAX-ZOOM, e.g. "/zoomTest/axZm/". 
                            The default "auto" value might not always work so if you experience any difficulties 
                            please set the path manually. 
                            It is recommended to set this path manually anyway!
                        ',
                        'DE' => '
                            Pfad zu AJAX-ZOOM, z.B. "/zoomTest/axZm/".
                            Der Wert "auto", welches standardmäßig definiert ist, 
                            kann unter Umständen nicht funktionieren! 
                            Definieren Sie diesen Pfad daher manuell in jedem Fall!
                        '
                    )
                ),

                'axZmMode' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enabling "axZmMode" option will let the mouseover 
                            extension act as most other AJAX-ZOOM examples: 
                            the AJAX-ZOOM player is displayed directly inline. 
                            Users can zoom in with mouse wheel / pinch zoom 
                            without clicking on mouseover image. 
                            Accordingly, the mouseover / preview images are not loaded. 
                            This option were added within this extension primary because 
                            of AJAX-ZOOM mouseover extension 
                            being already implemented into several e-commerce plugins / modules 
                            and it is simply convenient if you want to display AJAX-ZOOM like this.
                        ',
                        'DE' => '
                            Durch Aktivierung der Option "axZmMode" kann die Mouseover-Erweiterung 
                            sich wie die meisten anderen AJAX-ZOOM-Beispiele verhalten: 
                            der AJAX-ZOOM-Player wird direkt angezeigt. Benutzer können mit dem Mausrad / Fingern zoomen, 
                            ohne auf das Mouseover-Bild zu klicken. 
                            Folgend werden die Mouseover / Vorschaubilder nicht geladen. 
                            Diese Option wurde innerhalb dieser Erweiterung hinzugefügt, 
                            da die Mouseover-Erweiterung von AJAX-ZOOM bereits in mehrere 
                            E-Commerce-Plugins / Module implementiert ist und es einfach ist, 
                            wenn Sie AJAX-ZOOM auf diese Art anzeigen möchten.
                        '
                    )
                ),

                'maxZoomMode' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Set AJAX-ZOOM settings the way, that the user has only the possibility 
                            to spin at not zoomed state; zoom on click goes down to 100%; 
                            an other click at zoomed state will zoom out again.
                        ',
                        'DE' => '
                            Die AJAX-ZOOM Einstellungen werden so ein, dass der Benutzer nur die Möglichkeit hat, 
                            bei 360 nur im nicht gezoomten Zustand zu drehen; beim Klick zoomt der Player auf 100%. 
                            Beim weiteren Klick im voll gezoomten Zustand wird wieder verkleinert.
                        '
                    )
                ),

                'maxZoomSetBtn' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            If "maxZoomMode" is activated, enable a single button, 
                            which will fully zoom out when clicked on it. 
                            The button appears only at zoomed state.
                        ',
                        'DE' => '
                            Wenn "maxZoomMode" aktiviert ist, erscheint eine Schaltfläche, 
                            bei der beim Anklicken der Player vollständig verkleinert. 
                            Die Schaltfläche erscheint nur im gezoomten Zustand.
                        '
                    )
                ),

                'divID' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'az_mouseOverZoomContainer',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            DIV (container) ID for mouseover zoom :-)
                        ',
                        'DE' => '
                            DIV (Container) ID für Mouseover Zoom.
                        '
                    )
                ),

                'galleryDivID' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'az_mouseOverZoomGallery',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            DIV (container) id of the gallery, set to false to disable gallery.
                        ',
                        'DE' => '
                            DIV (Container) ID für die Galerie, mit false wird die Galerie abgeschaltet.
                        '
                    )
                ),

                'lang' => array(
                    'prefix' => '',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => '',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Language, e.g. "en", "fr" or "de". 
                            On default, when not defined / empty, the language is browser or system language.
                        ',
                        'DE' => '
                            Sprache, z.B. "en", "fr" oder "de". 
                            Wenn nicht definiert / leer, wird die Sprache des Browsers bzw. des Systems ermittelt.
                        '
                    )
                ),

                'disableAllMsg' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            AJAX-ZOOM produces some notifications within the player 
                            telling that image tiles or other files are generating and returns the result. 
                            This happens only when an image or 360 images are opened for the first time. 
                            This is also the reason why preloading is slow at first. 
                            With this switch you can disable these notifications in the frontend.
                        ',
                        'DE' => '
                            AJAX-ZOOM produziert einige Meldungen direkt im Player wenn beispielsweise 
                            Bildkacheln oder andere Bilddateien automatisch generiert werden. 
                            Dies geschieht nur, wenn Bilder bzw. 360 zum ersten Mal geöffnet werden. Dies ist 
                            und auch der Grund, weshalb beim ersten Laden das Vorladen spührbar länger dauert. 
                            Über diese Einstellung können Sie diese Benachrichtigungen abschalten.
                        '
                    )
                ),

                'dotNavigation' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 400,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable dot navigation if browser / device resolution is less than this value. 
                            See also "thumbSliderMinSize" option. Setting both 
                            - "dotNavigation" and "thumbSliderMinSize"  
                            to same value will result in dot navigation replacing 
                            gallery on devices with small resolution. Set to 0 to disable.
                        ',
                        'DE' => '
                            Aktivieren der Punktnavigation, wenn die Browser- / Geräteauflösung 
                            kleiner als dieser Wert ist. Siehe auch die Option "thumbSliderMinSize". 
                            Wenn "dotNavigation" und "thumbSliderMinSize" auf den 
                            gleichen Wert eingestellt sind, dann ersetzt "dotNavigation" die Galerie 
                            auf Geräten mit geringer Auflösung. Mit 0 wird die Punktnavigation abgeschaltet.
                        '
                    )
                ),

                'floorWidth' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Prevent browser computed width with decimals for AJAX-ZOOM containers.
                        ',
                        'DE' => '
                            Verhindert die Browser berechnete Breite mit Dezimalstellen für AJAX-ZOOM-Container.
                        '
                    )
                ),

                'pngMode' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'select',
                    'height' => null,
                    'default' => null,
                    'options' => array(
                        array('null', 'null'),
                        array('auto', 'auto'),
                        array('true', 'true'),
                        array('false', 'false')
                    ),
                    'comment' => array(
                        'EN' => '
                            Enable PNG mode in different ways: 
                            null will not change the configuration set in zoomConfig.inc.php or 
                            other php configuration files at all; 
                            auto, true and false will set pngMode setting in PHP. 
                            Auto means that cached thumbnails, image tiles etc. will be only kept as PNG 
                            if source images are PNG too. Transparancy is also preserved. 
                            If true, all cached images will be converted to PNG, even if they are JPG. 
                            If false, all cached images will be converted to JPG.
                        ',
                        'DE' => '
                            Aktivieren des PNG-Moduses auf unterschiedliche Weisen:
                            Null ändert nicht die Konfiguration in zoomConfig.inc.php 
                            oder andere PHP-Konfigurationsdateien; 
                            Auto, true und false setzen "pngMode" Einstellung.
                            Auto bedeutet, dass zwischengespeicherte Miniaturbilder, Bildkacheln usw. 
                            nur dann als PNG aufbewahrt werden, wenn auch die Quellbilder PNG sind. 
                            Dabei werden eventuelle Transparenzberiche erhalten. 
                            Wenn true, werden alle zwischengespeicherten Bilder in PNG konvertiert, 
                            auch wenn die Quellbilder JPG sind.
                            Wenn false, werden alle zwischengespeicherten Bilder in JPG konvertiert.
                        '
                    )
                ),

                'hideGalleryOneImage' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Instantly hide gallery if there is only one image, one video or one 360/3D.
                        ',
                        'DE' => '
                            Galerie automatisch ausblenden, wenn nur ein Bild bzw. nur ein 360/3D vorhanden sind.
                        '
                    )
                ),

                'hideGalleryAddClass' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'axZm_mouseOverNoMargin',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            This option is mainly for the layout with vertical gallery 
                            which is located next (left or right) to mouseover area. 
                            The most solid CSS layout for vertical gallery is when "divID" is wrapped by a div 
                            which has a left or right margin. This margin corresponds to vertical gallery width + 
                            some space in between. So if "hideGalleryOneImage" option is activated 
                            and there is only one image, 
                            only one 360 or no images / 360s at all, then the container 
                            represented by "galleryDivID" option is hidden 
                            and there is more space which can be filled. 
                            To do that we simply add a CSS class with margin 0 to the parent of "divID" 
                            overriding the left or right margin which is not needed for the gallery. 
                            You can change the "hideGalleryAddClass" 
                            to your own class name or set it to false to prevent this action.
                        ',
                        'DE' => '
                            Diese Option ist hauptsächlich für Layouts mit vertikalen Galerie. 
                             Wenn Galerie nicht angezeigt werden soll, dann wird 
                             diese CSS Klasse dem Container automatisch hinzugefügt. 
                             Bei false wird keine CSS Klasse hinzugefügt.
                        '
                    )
                ),

                'galleryHover' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Use mouseenter (mouseover) for switching between images. 
                            You can specify an integer which will represent the time in ms to wait 
                            for switching after the mouse enters the thumb; true defaults to 200.
                        ',
                        'DE' => '
                            Mouseenter (mouseover) bei der Galerie 
                            für Umschalten zwischen den Bildern / 360 verwenden. 
                            Man kann auch eine Zahl definieren. 
                            Dies wäre dann die Zeit in ms bei der abgewartet wird, 
                            ehe der Umschaltvorgang startet; true setzt den Standardwert von 200 (ms).
                        '
                    )
                ),

                'gallerySwitchSlide' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Placeholder, not finished yet (todo).
                        ',
                        'DE' => '
                            Placeholder, not finished yet (todo).
                        '
                    )
                ),

                'galleryAxZmThumbSlider' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Use $.axZmThumbSlider on gallery thumbnails or not.
                        ',
                        'DE' => '
                            Den AJAX-ZOOM $.axZmThumbSlider für die Galerie verwenden.
                        '
                    )
                ),

                'galleryAxZmThumbSliderParamHorz' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'example' => '{
    "thumbLiStyle": {
        "borderRadius": 3
    },
    "btn": true,
    "btnClass": "axZmThumbSlider_button_new",
    "btnHidden": true,
    "btnOver": false,
    "scrollBy": 1,
    "centerNoScroll": true
}',
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            $.axZmThumbSlider options if "galleryAxZmThumbSlider" is enabled 
                            and gallery is horizontal. For full list of options see under: 
                            http://www.ajax-zoom.com/axZm/extensions/axZmThumbSlider/ 
                        ',
                        'DE' => '
                            $.axZmThumbSlider Optionen wenn "galleryAxZmThumbSlider" 
                            aktiviert ist und die Galerie horizontal ist. Für die komplette Liste der Optionen 
                            siehe http://www.ajax-zoom.com/axZm/extensions/axZmThumbSlider
                        '
                    )
                ),

                'galleryAxZmThumbSliderParamVert' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'example' => '{
    "thumbLiStyle": {
        "borderRadius": 3
    },
    "btn": true,
    "btnClass": "axZmThumbSlider_button_new",
    "btnHidden": true,
    "btnOver": false,
    "scrollBy": 1,
    "centerNoScroll": true
}',
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            $.axZmThumbSlider options if "galleryAxZmThumbSlider" is enabled 
                            and gallery is vertical. For full list of options see under: 
                            http://www.ajax-zoom.com/axZm/extensions/axZmThumbSlider/ 
                        ',
                        'DE' => '
                            $.axZmThumbSlider Optionen wenn "galleryAxZmThumbSlider" 
                            aktiviert ist und die Galerie vertikal ist. Für die komplette Liste der Optionen 
                            siehe http://www.ajax-zoom.com/axZm/extensions/axZmThumbSlider
                        '
                    )
                ),

                'thumbSliderPosition' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'left-bottom',
                    'options' => array(
                        array('left-bottom', 'left-bottom'),
                        array('left-top', 'left-top'),
                        array('right-bottom', 'right-bottom'),
                        array('right-top', 'right-top'),
                        array('top-left', 'top-left'),
                        array('top-right', 'top-right'),
                        array('bottom-left', 'bottom-left'),
                        array('bottom-right', 'bottom-right'),
                        array('top', 'top (fixed)'),
                        array('right', 'right (fixed)'),
                        array('bottom', 'bottom (fixed)'),
                        array('left', 'left (fixed)'),
                        array('left-right', 'left-right'),
                        array('right-left', 'right-left'),
                        array('bottom-top', 'bottom-top'),
                        array('top-bottom', 'top-bottom')
                    ),
                    'comment' => array(
                        'EN' => '
                            Position of the thumb slider. Possible values are 
                            left, top, right, bottom, as well as any combination of these separated 
                            with the dash, e.g. left-bottom.
                        ',
                        'DE' => '
                            Position des Thumbsliders. Mögliche Werte sind top, left, right, bottom, 
                            sowie jegliche Kombination aus den obigen getrennt mit einem Strich, 
                            also z.B. left-bottom.
                        '
                    )
                ),

                'thumbSliderPositionSwitch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'auto',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Possible values: "auto" as string or integer. 
                            Auto means depending on whether the screen is in portrait or 
                            landscape mode the first or second value of "thumbSliderPosition" 
                            suited for the resolution will be chosen. For the value set as 
                            integer the second position from "thumbSliderPosition" will be 
                            chosen if width of the screen is less than this integer and 
                            the position suits for current resolution.
                        ',
                        'DE' => '
                            Mögliche Werte: "auto" als String oder Integer.
                            Auto bedeutet, je nachdem, ob der Bildschirm im Hochformat oder
                            Landscape-Modus ist, wird der erste oder zweite Wert aus "thumbSliderPosition" 
                            automatisch passend zur Auflösung gewählt. Bei einer Zahl wird die zweite 
                            Position aus "thumbSliderPosition" genommen, wenn die Bildschirmbreite 
                            kleiner ist als diese Zahl und die Position passt.
                        '
                    )
                ),

                'thumbSliderMinSize' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 400,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Minimal browser width for the gallery to be displayed. 
                            See also "dotNavigation" option!
                        ',
                        'DE' => '
                            Minimale Breite des Browserfensters, damit die Galerie angezeigt wird. 
                            Siehe auch "dotNavigation" Option.
                        '
                    )
                ),

                'thumbSliderDimensionHorz' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 80,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Height of the container for the gallery when placed horizontally.
                        ',
                        'DE' => '
                            Höhe des Containers für Galerie, wenn sie horizontal erscheint.
                        '
                    )
                ),

                'thumbSliderDimensionVert' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 80,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Width of the container for the gallery when placed vertically.
                        ',
                        'DE' => '
                            Breite des Containers für Galerie, wenn sie vertikal erscheint.
                        '
                    )
                ),

                'thumbSliderAutoSizeHorz' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumb CSS size will be set instantly depending on 
                            "thumbSliderDimensionHorz" and other options.
                        ',
                        'DE' => '
                            Stellt die CSS Größen für Miniaturansichten automatisch abhängig von 
                            "thumbSliderDimensionHorz" und anderen Optionen ein, 
                             wenn Galerie horizontal erscheint.
                        '
                    )
                ),

                'thumbSliderAutoSizeVert' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumb CSS size will be set instantly depending on 
                            "thumbSliderDimensionVert" and other options.
                        ',
                        'DE' => '
                            Stellt die CSS Größen für Miniaturansichten automatisch abhängig von 
                            "thumbSliderDimensionVert" und anderen Optionen ein, 
                             wenn Galerie horizontal erscheint.
                        '
                    )
                ),

                'thumbSliderAutoMarginHorz' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 7,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumb margin when set instantly for horizontal gallery.
                        ',
                        'DE' => '
                            Abstand der Miniaturansichten in horizontalen Galerie, wenn 
                            "thumbSliderAutoSizeHorz" eingeschaltet ist.
                        '
                    )
                ),
                
                'thumbSliderAutoMarginVert' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 7,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumb margin when set instantly for vertical gallery.
                        ',
                        'DE' => '
                            Abstand der Miniaturansichten in vertikallen Galerie, wenn 
                            "thumbSliderAutoSizeHorz" eingeschaltet ist.
                        '
                    )
                ),

                'thumbSliderAutoExtendHorz' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Extend slider height by a fixed value 
                            when set instantly for horizontal gallery - "thumbSliderAutoSizeHorz".
                        ',
                        'DE' => '
                            Erweitern die Höhe des Sliders um einen festen Wert, wenn diese für horizontale Galerie 
                            automatisch eingestellt wird - "thumbSliderAutoSizeHorz"
                        '
                    )
                ),

                'thumbSliderAutoExtendVert' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Extend slider width by a fixed value 
                            when set instantly for vertical gallery - "thumbSliderAutoSizeVert"
                        ',
                        'DE' => '
                            Erweitern die Breite des Sliders um einen festen Wert, wenn diese für vertikale Galerie 
                            automatisch eingestellt wird - "thumbSliderAutoSizeVert"
                        '
                    )
                ),

                'thumbSliderHorzMargin' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 10,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Horizontal margin of the vertical gallery to mouseover container.
                        ',
                        'DE' => '
                            Margin bzw. Abstand der vertikalen Galerie zu mouseover Container.
                        '
                    )
                ),

                'thumbsFadeIn' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 200,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Fadein duration / speed of the thumbnails added to the gallery.
                        ',
                        'DE' => '
                            Einblenddauer für neu geladene Miniaturansichten in der Galerie.
                        '
                    )
                ),

                'thumbW' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 64,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Gallery image thumb width. Please note that when "galleryAxZmThumbSlider" is enabled, 
                            the final thumbnail width and height are determined by CSS 
                            set over "galleryAxZmThumbSliderParam".
                        ',
                        'DE' => '
                            Galeriebild Breite; bitte beachten Sie, 
                            dass wenn "galleryAxZmThumbSlider" eingeschaltet ist, 
                            dann wird die finale Thumbnail Breite und Höhe auch über CSS 
                            in "galleryAxZmThumbSliderParam" bestimmt.
                        '
                    )
                ),

                'thumbH' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 64,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Gallery image thumb height. Please note that when "galleryAxZmThumbSlider" is enabled, 
                            the final thumbnail width and height are determined by CSS 
                            set over "galleryAxZmThumbSliderParam".
                        ',
                        'DE' => '
                            Galeriebild Höhe; bitte beachten Sie, 
                            dass wenn "galleryAxZmThumbSlider" eingeschaltet ist, 
                            dann wird die finale Thumbnail Breite und Höhe auch über CSS 
                            in "galleryAxZmThumbSliderParam" bestimmt.
                        '
                    )
                ),

                'thumbRetina' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Double resolution of the thumb image.
                        ',
                        'DE' => '
                            Doppelte Auflösung für Galeriebild.
                        '
                    )
                ),

                'thumbMode' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => false,
                    'options' => array(
                        array('false', 'false'),
                        array('cover', 'cover'),
                        array('contain', 'contain')
                    ),
                    'comment' => array(
                        'EN' => '
                            Create thumbnails in a way similar to css background values cover and contain.
                        ',
                        'DE' => '
                            Miniatursichen ähnlich der CSS background Werten cover und contain erstellen.
                        '
                    )
                ),

                'qualityThumb' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 100,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Jpeg quality of the gallery thumbs.
                        ',
                        'DE' => '
                            Jpeg Qualität der Galerie Bilder.
                        '
                    )
                ),

                'thumbIcon' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Place an icon over gallery thumbnail depending on media type. 
                            The icon has CSS class axZmThumbSliderIcon and the source is defined in 
                            "thumbIconFile" option.
                        ',
                        'DE' => '
                            Setzen eines Symbols über Galerie-Miniaturansicht abhängig von Medientyp.
                            Das Symbol hat die CSS-Klasse axZmThumbSliderIcon. Die Quelle des Symbols wird in 
                            "thumbIconFile" Option definiert.
                        '
                    )
                ),

                'thumbIconFile' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "360": "360_1.png",
    "3D": "3d_1.png",
    "youtube": "youtube_icon_30.png",
    "vimeo": "vimeo_icon_30.png",
    "dailymotion": "video_icon-30.png",
    "html5": "video_icon-30.png"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Source for icons placed over the thumbnails in the gallery. 
                            If only filename without path is defined the path presumed to be the 
                            /axZm/icons folder. You can also set complete different paths including the image  
                            filename instead.
                        ',
                        'DE' => '
                            Quelle für Symbole, welche über Galerie-Miniaturansichten 
                            abhängig von Medientyp gesetzt werden. Wenn nur Dateiname ohne Pfad definiert ist, 
                            werden die Pfade im /axZm/icons Ordner vermutet. 
                            Sie können stattdessen auch komplett andere Pfade inklusive der Bilddatei definieren.
                        '
                    )
                ),

                'thumbWfs' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 64,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Fullscreen gallery image thumb width.
                        ',
                        'DE' => '
                            Physische Breite des Galeriebildes in Vollbild Galerie.
                        '
                    )
                ),

                'thumbHfs' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 64,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Fullscreen gallery image thumb height.
                        ',
                        'DE' => '
                            Physische Höhe des Galeriebildes in Vollbild Galerie.
                        '
                    )
                ),

                'thumbRetinaFs' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Double the resolution of fullscreen gallery image thumbs.
                        ',
                        'DE' => '
                            Doppelte Auflösung für Vollbild Galeriebild.
                        '
                    )
                ),

                'thumbModeFs' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => false,
                    'options' => array(
                        array('false', 'false'),
                        array('cover', 'cover'),
                        array('contain', 'contain')
                    ),
                    'comment' => array(
                        'EN' => '
                            Create thumbnails in a way similar to css background values cover and contain.
                        ',
                        'DE' => '
                            Miniatursichen ähnlich der CSS background Werten cover und contain erstellen.
                        '
                    )
                ),

                'qualityThumbFs' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 100,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Jpeg Quality of the thumbnails in fullscreen gallery.
                        ',
                        'DE' => '
                            Jpeg Qualität der Vollbild Galerie Bilder.
                        '
                    )
                ),

                'quality' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 90,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Jpeg quality of the preview image.
                        ',
                        'DE' => '
                            Jpeg Qualität der Voransicht Bilder.
                        '
                    )
                ),

                'qualityZoom' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 80,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Jpeg quality of the zoom image shown in the flyout window.
                        ',
                        'DE' => '
                            Jpeg Qualität der Bilder im Mouseover Fenster.
                        '
                    )
                ),

                'firstImageToLoad' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Image from "images" option which should be loaded at first. 
                            See also "images360firstToLoad" option below. 
                            The "images" option does not exist as defineable option 
                            in AJAX-ZOOM modules / plugins for 
                            ecommerce systems because obviously the purpose of the modules is to set it instantly.
                        ',
                        'DE' => '
                            Bild aus "images" Option, welche zuerst geladen werden soll. 
                            Siehe auch "images360firstToLoad" Option. 
                            Die Option "images" existiert nicht als definierbare Option 
                            in AJAX-ZOOM-Modulen / Plugins für E-Commerce-Systeme, 
                            weil offensichtlich der Zweck der Module ist, es automatisch einzustellen.
                        '
                    )
                ),

                'images360firstToLoad' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'important' => true,
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            In case present load 360 from "images360" first and not an image from "images".
                        ',
                        'DE' => '
                            Wenn 360 präsent ist, dann wird die 360 animation zuerst geladen.
                        '
                    )
                ),

                'images360Thumb' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Show first image of the spin as thumb.
                        ',
                        'DE' => '
                            Als Galerie Bild wird automatisch das erste Bild eines 360 / 3D angezeigt.
                        '
                    )
                ),

                'images360ThumbDefaultPostion' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'first',
                    'options' => array(
                        array('first', 'first'),
                        array('afterfirst', 'afterfirst'),
                        array('last', 'last')
                    ),
                    'comment' => array(
                        'EN' => '
                            Default position of the thumbnail representing a 360/3D in the gallery. 
                            The position can be also defined for each thumb individually. 
                            Possible values are: "first", "afterfirst" and "last". 
                            See also "videoThumbDefaultPostion" option.
                        ',
                        'DE' => '
                            Standardposition der Miniaturansicht, die ein 360 / 3D in der Galerie darstellt.
                            Die Position kann auch für jede Miniaturansicht individuell definiert werden.
                            Mögliche Werte sind: "first", "afterfirst" und "last".
                            Siehe auch "videoThumbDefaultPostion" Option.
                        '
                    )
                ),

                'images360ThumbBeforeVideo' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            If activated thumbnails in the gallery representing a 360/3D will be 
                            appended before thumbnails representing videos.
                        ',
                        'DE' => '
                            Wenn aktiviert, werden Miniaturbilder, die eine 360 / 3D darstellen, 
                            vor Miniaturbildern, die Videos darstellen, der Galerie hinzugefügt.
                        '
                    )
                ),

                'images360Overlay' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Add a div with class "spinOverlImg" or "spinOverl" over the gallery thumb. 
                            On default it has a 360 icon as background.
                        ',
                        'DE' => '
                            Bei Aktivierung wird ein DIV mit der Klasse "spinOverlImg" oder "spinOverl" 
                            über das Galerie Bild hinzugefügt. 
                            Es enthält ein 360 Icon als Hintergrund.
                        '
                    )
                ),
                
                'images360Small' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-360-600.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Placeholder image when 360 is slided. 
                            If only filename without path is defined the path presumed to be the 
                            /axZm/icons folder. You can also set complete different paths including the image  
                            filename instead.
                        ',
                        'DE' => '
                            Platzhalterbild, wenn 360 verschoben wird. 
                            Wenn nur Dateiname ohne Pfad definiert ist, 
                            werden die Pfade im /axZm/icons Ordner vermutet. 
                            Sie können stattdessen auch komplett andere Pfade inklusive der Bilddatei definieren.
                        '
                    )
                ),

                'images360Big' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-360-1200.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Placeholder image when 360 is slided. 
                            If only filename without path is defined the path presumed to be the 
                            /axZm/icons folder. You can also set complete different paths including the image  
                            filename instead.
                        ',
                        'DE' => '
                            Platzhalterbild, wenn 360 verschoben wird. 
                            Wenn nur Dateiname ohne Pfad definiert ist, 
                            werden die Pfade im /axZm/icons Ordner vermutet. 
                            Sie können stattdessen auch komplett andere Pfade inklusive der Bilddatei definieren.
                        '
                    )
                ),

                'images360example' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'mouseOverExtension360Ver5',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Configuration set which is passed to ajax-zoom when opening a 360/3D. 
                            The configuration set can be found in /axZm/zoomConfigCustom.inc.php
                        ',
                        'DE' => '
                            Wert des Konfiguration Sets, welches an AJAX-ZOOM übergeben wird wenn es ein 360/3D ist. 
                            Die configuration des Sets kann in /axZm/zoomConfigCustom.inc.php 
                            gefunden / erweitert werden.
                        '
                    )
                ),

                'zoomMsg360' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en" : "Drag to spin 360°, scroll to zoom in and out, right click and drag to pan",
    "de" : "Ziehen um 360° zu drehen, zoomen mit dem mausrad, rechte maustaste ziehen verschiebt die Ansicht",
    "fr" : "Faites glisser pour tourner à 360 °, faites défiler pour zoomer dans et hors, cliquer et faire glisser à droite pour vous déplacer",
    "es" : "Arrastrar para girar en 360º, Rueda del ratón para utilizar el Zoom, botón derecho para mover la imagen"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message displayed under mouse over zoom when 360 is loaded, 
                            e.g. "Drag to spin 360, scroll to zoom". 
                            For more than one language define a js object, e.g. 
                            {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Nachricht, welche unter der Voransicht angezeigt wird, wenn darin 360 geladen ist. 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomMsg360_touch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en" : "Drag to spin 360°, pinch to zoom in and out",
    "de" : "Ziehen um 360° zu drehen, zoomen mit zwei fingern",
    "fr" : "Faites glisser pour tourner à 360 °, pincer pour zoomer et dézoomer",
    "es" : "Arrastrar para girar en 360º, pellizcar para ampliar y reducir"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message displayed under mouse over zoom when 360 is loaded on touch devices. 
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Nachricht, welche unter der Voransicht angezeigt wird, wenn 
                            darin 360 auf einem touch Device geladen ist. 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomMsg' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en" : "Drag to spin 360°, scroll to zoom in and out, right click and drag to pan",
    "de" : "Ziehen um 360° zu drehen, zoomen mit dem Mausrad, rechte Maustaste ziehen verschiebt die Ansicht",
    "fr" : "Faites glisser pour tourner à 360 °, faites défiler pour zoomer dans et hors, cliquer et faire glisser à droite pour vous déplacer",
    "es" : "Arrastrar para girar en 360º, Rueda del ratón para utilizar el Zoom, botón derecho para mover la imagen"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message displayed under the Player only, when "axZmMode" is enabled and an image is loaded. 
                            For more than one language define a js object, e.g. 
                            {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Nachricht, welche nur unter dem Player angezeigt wird, wenn axZmMode Option aktiviert ist 
                            und ein Bild darin angezeigt wird. Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomMsg_touch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en" : "Drag to spin 360°, pinch to zoom in and out",
    "de" : "Ziehen um 360° zu drehen, zoomen mit zwei fingern",
    "fr" : "Faites glisser pour tourner à 360 ° , pincer pour zoomer et dézoomer",
    "es" : "Arrastrar para girar en 360º, pellizcar para ampliar y reducir"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message displayed under the player, when "axZmMode" is enabled 
                            and an image is loaded on touch devices. 
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Nachricht, welche unter dem Player angezeigt wird, wenn 
                            "axZmMode" Option aktiviert ist und ein Bild auf einem touch Device geladen ist. 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'preloadMouseOverImages' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => true,
                    'options' => array(
                        array('false', 'false'),
                        array('true', 'true'),
                        array('oneByOne', 'oneByOne')
                    ),
                    'comment' => array(
                        'EN' => '
                            Preload all preview and mouseover images, possible values: false, true oder "oneByOne".
                        ',
                        'DE' => '
                            Alle Bilder für das Mouseover vorladen. Mögliche Werte sind 
                            false, true oder "oneByOne".
                        '
                    )
                ),

                'noImageAvailableClass' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => 'axZm_mouseOverNoImage',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            In case there are no images in "images", nor there are any in "images360", 
                            a div with some image as background can be appended to the 
                            container and receive this options value as CSS class. 
                            For more than one language define a js object, 
                            e.g. {"en": "axZm_mouseOverNoImage_en", "de": "axZm_mouseOverNoImage_de"}
                        ',
                        'DE' => '
                            Falls es weder Bilder, noch 360 gibt, 
                            wird ein DIV mit einem Bild als Hintergrund angezeigt. 
                            Der Wert diese Option ist die CSS Klasse, welche dem Container hinzugefügt wird. 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "axZm_mouseOverNoImage_en", "de": "axZm_mouseOverNoImage_de"}
                        '
                    )
                ),

                'width' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'auto',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Width of the preview image or "auto" 
                            (depending on parent container size - "divID", see above). 
                            This is also the value which will be passed to your AJAX-ZOOM imaging server 
                            to generate this image on-the-fly. 
                            If width or height are set to "auto", both: width and height of the preview image 
                            are set to 50% of the value taken from "mouseOverZoomWidth", so on default it is 600px
                        ',
                        'DE' => '
                            Breite des Bildes in der Voransicht oder "auto" 
                            (ist dann abhängig von der Größe des Containers - "divID"). 
                            Wenn Breite und Höhe auf "auto" stehen und "responsive" Option aktiviert ist, 
                            dann werden beide Werte auf 50% des Wertes von "mouseOverZoomWidth" eingestellt, 
                            also standardmäßig sind es 600px.
                        '
                    )
                ),

                'height' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'auto',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Height of the preview image or "auto" 
                            (depending on parent container size - "divID", see above). 
                            This is also the value which will be passed to your AJAX-ZOOM imaging server 
                            to generate this image on-the-fly. 
                            If width or height are set to "auto", both: width and height of the preview image 
                            are set to 50% of the value taken from "mouseOverZoomWidth", so on default it is 600px
                        ',
                        'DE' => '
                            Höhe des Bildes in der Voransicht oder "auto" 
                            (ist dann abhängig von der Größe des Containers - "divID"). 
                            Wenn Breite und Höhe auf "auto" stehen und "responsive" Option aktiviert ist, 
                            dann werden beide Werte auf 50% des Wertes von "mouseOverZoomWidth" eingestellt, 
                            also standardmäßig sind es 600px.
                        '
                    )
                ),

                'oneSrcImg' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Use same image for "preview image" - 
                            the image which is hovered and the big "flyout image".
                        ',
                        'DE' => '
                            Das gleiche Bild in der Voransicht, als auch Mouseover Fenster verwenden. 
                        '
                    )
                ),

                'heightRatio' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'float, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1.0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            "heightRatio" with instantly adjust the height of mouseover 
                            container depending on width calculated by the browser, e.g. 1.0 will always 
                            (only limited by "maxSizePrc" option) make height same as width; 
                            a value of 1.5 will make the preview like a portrait. 
                            You can also set "heightRatio" to "auto". 
                            In this case the height will be adjusted to cover available space instantly! 
                            Please note that when your images are not always same proportion, 
                            then the container will also change the size when the user switches to a different image.
                        ',
                        'DE' => '
                            Wenn "responsive" Option aktiviert, dann wird über diese Option die Höhe des Containers 
                            für die Voransicht je nach Breite automatisch kalkuliert. 
                            Bei 1.0 wird also die Höhe gleich der Breite gesetzt 
                            (eine tatsächliche Abweichung / Verringerung der Höhe ergibt sich 
                            ggf. nur durch "maxSizePrc" Option). 
                            Bei 1.5 wird die Preview in Portrait Dimensionen erscheinen. 
                            Sie können "heightRatio", also diese Option, ebenfalls auf "auto" setzen. 
                            Dann wird das Maximum Sichtbare als Höhe gesetzt. 
                            Der Container wird jedoch bei Bildern, mit unterschiedlichen Proportionen 
                            beim Wechsel in der Höhe "springen".
                        '
                    )
                ),

                'heightRatioOneImg' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string, int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'same',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Overwrites "heightRatio" if only one image / 360 is present and 
                            e.g. gallery is instantly disabled. Set to 
                            "same" in order to prevent any impact on "heightRatio" option.
                        ',
                        'DE' => '
                            Überschreibt "heightRatio" wenn nur ein Bild bzw. 
                            ein 360 vorhanden ist und die Galerie automatisch deaktiviert ist. 
                            Sie können als Wert aber auch "same" angeben. 
                            Dadurch wird jegliche Auswirkung dieses Wertes auf "heightRatio" vermieden. 
                        '
                    )
                ),

                'heightMaxWidthRatio' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'array, string, bool',
                    'isJsObject' => false,
                    'isJsArray' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => '[]',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Similar as you would set max-width: someValue @media only screen condition 
                            you can define "heightRatio" 
                            depending on the width of the browser, e.g. ["960|0.8", "700|0.7"]. 
                            You can define a string or js array with more than one conditions.
                        ',
                        'DE' => '
                            Ähnlich der CSS max-width: someValue @media only screen Bedingung 
                            kann hier der Wert des "heightRatio" abhängig von Browser Fenster Breite 
                            gesetzt werden, z.B. ["960|0.8", "700|0.7"]
                        '
                    )
                ),

                'widthRatio' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'array, string, bool',
                    'isJsObject' => false,
                    'isJsArray' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Oposit of "heightRatio".
                        ',
                        'DE' => '
                            Gegenteil von "heightRatio".
                        '
                    )
                ),

                'widthMaxHeightRatio' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'array, string, bool',
                    'isJsObject' => false,
                    'isJsArray' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => '[]',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Oposit of "heightMaxWidthRatio"
                        ',
                        'DE' => '
                            Gegenteil von "heightMaxWidthRatio".
                        '
                    )
                ),

                'maxSizePrc' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string, float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => '1.0|-120|-25',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Limit the actual height if "heightRatio" option is set. 
                            Setting "heightRatio" option may result in that the height of the mouseover zoom 
                            is bigger than window height and the image is not fully visible. 
                            To prevent this you can limit the calculated height with this "maxSizePrc" option. 
                            The value of 1.0 would limit the height to 100% of window height; 
                            a value of 0.8 to 80% of window height; 
                            you can also define two values, e.g. \'1.0|-120\' 
                            which would be window height minus 120px. 
                            \'1.0|auto\' would be window height minus thumbnails
                            gallery height if it is positioned at bottom or top.
                            If you define third value e.g. \'1.0|-120|-25\' it will replace 
                            the second value if gallery is not positioned at bottom or top. 
                            The fourth value can be a jQuery selector for HTML elements 
                            which height should be substracted from the calculated height
                            of the player, e.g. \'1.0|-120|-25|#header_top\'
                        ',
                        'DE' => '
                            Schränkt die Höhe ein, wenn "heightRatio" optionen gesetzt sind. 
                            Wenn "heightRatio" gesetzt ist, dann könnte die Höhe größer sein, als die eigentliche 
                            Fenster Größe und das Bild nicht als Ganzes (ohne scrollen) zu sehen ist. 
                            Um dies zu verhindern kann man über diese Option die Höhe einschränken. 
                            Der Wert von 1.0 würde die Höhe auf 100% des Fensters einschränken; 
                            ein Wert von 0.8 auf 80%; bei dem Wert \'1.0|-120\' wären dies 100% minus 120px. 
                            \'1.0|auto\' wäre Fensterhöhe minus Höhe der Miniaturansichten Galerie, 
                            wenn diese unten oder oben positioniert ist. 
                            Wenn Sie einen dritten Wert definieren, z.B. \'1.0|-120|-25\' 
                            wird es den zweiten ersetzen, wenn die Galerie nicht unten oder oben positioniert ist. 
                            Der vierte Wert kann ein jQuery-Selektor für HTML-Elemente sein, wessen Höhe 
                            von der berechneten Höhe subtrahiert werden sollte, z.B \'1.0|-120|-25|#header_top\'
                        '
                    )
                ),

                'mouseOverZoomWidth' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'important' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1200,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Max width of the image that will be shown in the zoom window; 
                            this is the value which will be passed to your AJAX-ZOOM imaging server 
                            to generate this image on-the-fly. 
                            Please note that the size is limited by $zoom[\'config\'][\'allowDynamicThumbsMaxSize\'] 
                            which is can be set in \'/axZm/zoomConfig.inc.php\'. 
                            You can also specify a link to the image, see "images" option above. 
                            To set the width of the fly out window see "zoomWidth" under "mouseOverZoomParam".
                        ',
                        'DE' => '
                            Maximale Breite des Bildes, welches im Zoom Fenster gezeigt wird. 
                            Dies ist auch der Wert, welches an den AJAX-ZOOM dynamischen Bild Generator 
                            übergeben wird um das Bild zu skalieren. Bitte beachten Sie, dass 
                            der Wert bei $zoom[\'config\'][\'allowDynamicThumbsMaxSize\'] limitiert ist und 
                            nur in \'/axZm/zoomConfig.inc.php\' 
                            bzw. in \'zoomConfigCustomAZ.inc.php\' verändert werden kann. 
                            Die Breite des Fensters wird über "zoomWidth" in "mouseOverZoomParam" definiert. 
                        '
                    )
                ),

                'mouseOverZoomHeight' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'important' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1200,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Max height of the image that will be shown in the zoom window; 
                            this is the value which will be passed to your AJAX-ZOOM imaging server 
                            to generate this image on-the-fly. 
                            Please note that the size is limited by $zoom[\'config\'][\'allowDynamicThumbsMaxSize\'] 
                            which is can be set in \'/axZm/zoomConfig.inc.php\'. 
                            You can also specify a link to the image, see "images" option above. 
                            To set the height of the fly out window see "zoomHeight" under "mouseOverZoomParam".
                        ',
                        'DE' => '
                            Maximale Höhe des Bildes, welches im Zoom Fenster gezeigt wird. 
                            Dies ist auch der Wert, welches an den AJAX-ZOOM dynamischen Bild Generator 
                            übergeben wird um das Bild zu skalieren. Bitte beachten Sie, dass 
                            der Wert bei $zoom[\'config\'][\'allowDynamicThumbsMaxSize\'] limitiert ist und nur in 
                            \'/axZm/zoomConfig.inc.php\' bzw. in \'zoomConfigCustomAZ.inc.php\' verändert werden kann. 
                            Die Breite des Fensters wird über "zoomWidth" in "mouseOverZoomParam" definiert.
                        '
                    )
                ),

                'mouseOverContain' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Create preview and the image, which is shown in the zoom window, 
                            with the same size / proportion 
                            as it is set in "mouseOverZoomWidth" and "mouseOverZoomHeight" options. 
                            Empty space will be filled with white color or kept transparent for png images.
                        ',
                        'DE' => '
                            Voransichtsbild, sowie das Bild, welches im Zoom Fenster gezeigt wird, 
                            entsprechend der Dimensionen / Proportion 
                            der in "mouseOverZoomWidth" und "mouseOverZoomHeight" gesetzten Werten erstellen. 
                            Leere Bereiche werden dabei mit weißer Farbe bzw. bei PNG mit transparent gefüllt.
                        '
                    )
                ),

                'ajaxZoomOpenMode' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'fancyboxFullscreen',
                    'options' => array(
                        array('fullscreen', 'fullscreen (browser window or screen) '),
                        array('fancyboxFullscreen', 'fancyboxFullscreen (responsive fancybox)')
                    ),
                    'comment' => array(
                        'EN' => '
                            Determines how AJAX-ZOOM is opened when the user clicks on preview images / lens, 
                            possible values: \'fullscreen\' (see also "fullScreenApi" option below) 
                            or \'fancyboxFullscreen\'.
                        ',
                        'DE' => '
                            Hier kann bestimmt werden, wie AJAX-ZOOM geöffnet wird, wenn 
                            der Nutzer auf die Voransicht / Linse klickt. 
                            Mögliche Werte sind: \'fullscreen\' (siehe auch "fullScreenApi" Option) 
                            oder \'fancyboxFullscreen\'
                        '
                    )
                ),

                'ajaxZoomOpenModeTouch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'fullscreen',
                    'options' => array(
                        array('fullscreen', 'fullscreen (browser window or screen) '),
                        array('fancyboxFullscreen', 'fancyboxFullscreen (responsive fancybox)')
                    ),
                    'comment' => array(
                        'EN' => '
                            Determines how AJAX-ZOOM is opened when the user touch - clicks on 
                            preview images / lens, possible values: \'fullscreen\' and \'fancyboxFullscreen\'.
                        ',
                        'DE' => '
                            Hier kann bestimmt werden, wie AJAX-ZOOM geöffnet wird, wenn 
                            der Nutzer auf die Voransicht / Linse mit touch event klickt. 
                            Mögliche Werte sind: \'fullscreen\' und \'fancyboxFullscreen\'.
                        '
                    )
                ),

                'fancyBoxFullscreenParam' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 120,
                    'default' => '{
    "boxMargin": 30,
    "boxPadding": 10,
    "boxCenterOnScroll": true,
    "boxOverlayShow": true,
    "boxOverlayOpacity": 0.85,
    "boxOverlayColor": "#777",
    "boxTransitionIn": "fade",
    "boxTransitionOut": "fade",
    "boxSpeedIn": 300,
    "boxSpeedOut": 300,
    "boxEasingIn": "swing",
    "boxEasingOut": "swing",
    "boxShowCloseButton": true,
    "boxEnableEscapeButton": true,
    "boxOnComplete": function(){}
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            If fancyboxFullscreen is used in "ajaxZoomOpenMode" option, Fancybox options. 
                            Prefixed with box and capitalized first letter!
                        ',
                        'DE' => '
                            Wenn fancybox in "ajaxZoomOpenMode" gesetzt ist, sind dies die Fancybox Optionen. 
                            Dabei ist der Präfix box und erster Buchstabe wird großgeschrieben.
                        '
                    )
                ),

                'example' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'mouseOverExtension360Ver5',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Configuration set which is passed to ajax-zoom when opening a 2D image. 
                            The configuration set can be found in /axZm/zoomConfigCustom.inc.php
                        ',
                        'DE' => '
                            Wert des Konfiguration Sets, 
                            welches an AJAX-ZOOM übergeben wird wenn es ein 2D Bild ist. 
                            Die configuration des Sets kann in /axZm/zoomConfigCustom.inc.php gefunden 
                            / erweitert werden.
                        '
                    )
                ),

                'exampleFancyboxFullscreen' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'mouseOverExtension360Ver5',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Configuration set which is passed to ajax-zoom when ajaxZoomOpenMode 
                            is \'fancyboxFullscreen\'.
                        ',
                        'DE' => '
                            Wert des Konfiguration Sets, welches an AJAX-ZOOM übergeben wird, 
                            wenn ajaxZoomOpenMode auf \'fancyboxFullscreen\' gesetzt ist.
                        '
                    )
                ),

                'enforceFullScreenRes' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 768,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enforce "ajaxZoomOpenMode" to be "fullscreen" if screen width is less than this value.
                        ',
                        'DE' => '
                            Erzwingen / setzen "ajaxZoomOpenMode" auf "fullscreen", 
                            wenn Breite des Browser Fensters kleiner ist, als dieser Wert.
                        '
                    )
                ),

                'prevNextArrows' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Put prev / next buttons over mouseover zoom. CSS: .axZm_mouseOverPrevNextArrows;
                        ',
                        'DE' => '
                            Prev / next Pfeile über Mouseover Zoom / Voransicht anzeigen, 
                            CSS: .axZm_mouseOverPrevNextArrows;
                        '
                    )
                ),

                'prevNextArrowsTouch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Put prev / next buttons over mouseover zoom for touch devices. 
                            CSS: .axZm_mouseOverPrevNextArrows;
                        ',
                        'DE' => '
                            Prev / next Pfeile über Mouseover Zoom / Voransicht für touch Geräte anzeigen, 
                            CSS: .axZm_mouseOverPrevNextArrows;
                        '
                    )
                ),

                'prevNextArrowsSlide' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Slide images to right or left when clicked on the prev / next buttons.
                        ',
                        'DE' => '
                            Das Bild jeweils nach rechts oder links "sliden", wenn 
                            auf die prev / next Pfeile geklickt wird.
                        '
                    )
                ),

                'prevNextArrowsOver' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1500,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Hide prev / next buttons when mouse is not over the player. 
                            The value determines the delay after which the event is fired. 
                            Set to 0 to disable.
                        ',
                        'DE' => '
                            Prev / next Pfeile ausblenden, wenn Maus nicht über dem Player ist. 
                            Der Wert bestimmt die Verzögerung, nach der das Ereignis ausgelöst wird. 
                            Auf 0 stellen, um zu deaktivieren.
                        '
                    )
                ),

                'disableScrollAnm' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Disable animation while zooming with AJAX-ZOOM and using mousewheel.
                        ',
                        'DE' => '
                            Deaktivieren der Animation während mit AJAX-ZOOM und dem Mausrad gezoomt wird.
                        '
                    )
                ),

                'fullScreenApi' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Try to open AJAX-ZOOM at browsers fullscreen mode, 
                            possible on modern browsers except IE < 10 and mobile.
                        ',
                        'DE' => '
                            AJAX-ZOOM im Browser als Vollbild öffnen, 
                            möglich in modernen Browsern außer IE <10 und mobil;
                        '
                    )
                ),

                'axZmCallBacks' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            AJAX-ZOOM has several callbacks / hooks, 
                            http://www.ajax-zoom.com/index.php?cid=docs#onBeforeStart
                        ',
                        'DE' => '
                            AJAX-ZOOM kann mit zahlreichen Callbacks bzw. Hooks erweitert werden. 
                            Docu: http://www.ajax-zoom.com/index.php?cid=docs#onBeforeStart
                        '
                    )
                ),

                'azOptions' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Some AJAX-ZOOM options can be set with JS when AJAX-ZOOM is inited. 
                            Normally you would be defining them in /axZm/zoomConfig.inc.php 
                            or /axZm/zoomConfigCustom.inc.php; this field is for convenience reasons. Example: 
                            {"fullScreenCornerButton": false} - this would disable the button for fullscreen. 
                        ',
                        'DE' => '
                            Einige AJAX-ZOOM-Optionen können mit JS eingestellt werden, 
                            wenn AJAX-ZOOM initialisiert wird. 
                            Normalerweise würde man sie in /axZm/zoomConfig.inc.php 
                            oder /axZm/zoomConfigCustom.inc.php einstellen; 
                            Aus Bequemlichkeitsgründen kann man diese auch hier einstellen. Beispiel:
                            {"fullScreenCornerButton": false} - 
                            dies würde die Schaltfläche für Vollbild deaktivieren. 
                        '
                    )
                ),

                'azOptions360' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'object',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Same as above but specifically for 360/3D. 
                            In AJAX-ZOOM modules for ecommerce systems you can also set these options 
                            for each 360 individually... 
                            As an example for setting this option manually would be such an object: 
                            {"mouseScrollEnable": true, 
                            "mNavi": {"enabled": true, "gravity": "bottomLeft", 
                            "order": {"mZoomOut": 5, "mZoomIn": 15}}}
                        ',
                        'DE' => '
                            Das gleiche wie "azOptions", nur für 360. In AJAX-ZOOM Modulen für diverse Ecommerce Systeme 
                            können diese Optionen für jede 360 auch individuell gesetzt werden. 
                            Als Beispiel für die manuelle Einstellung dieser Option wäre ein solches Objekt: 
                            {"mouseScrollEnable": true, 
                            "mNavi": {"enabled": true, "gravity": "bottomLeft", 
                            "order": {"mZoomOut": 5, "mZoomIn": 15}}}
                        '
                    )
                ),

                'postMode' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'general_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Set AJAX-ZOOM to use POST instead of GET.
                        ',
                        'DE' => '
                            AJAX-ZOOM mit POST anstatt GET nutzen.
                        '
                    )
                ),

                'cropAxZmThumbSliderParam' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'object',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'example' => '{
    "btn": true,
    "btnClass": "axZmThumbSlider_button_new",
    "btnHidden": true,
    "centerNoScroll": true,
    "thumbImgWrap": "azThumbImgWrapRound",
    "scrollBy": 1
}',
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Slider settings for 360° "Product Tour". Can be kept empty. 
                            See also "galleryAxZmThumbSliderParam" option for more info.
                        ',
                        'DE' => '
                            Slider Einstellungen für 360° "Produkt Tour". 
                            Kann leer bleiben. Für mehr Info siehe "galleryAxZmThumbSliderParam" Option.
                        '
                    )
                ),

                'cropSliderPosition' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'bottom',
                    'options' => array(
                        array('left-bottom', 'left-bottom'),
                        array('left-top', 'left-top'),
                        array('right-bottom', 'right-bottom'),
                        array('right-top', 'right-top'),
                        array('top-left', 'top-left'),
                        array('top-right', 'top-right'),
                        array('bottom-left', 'bottom-left'),
                        array('bottom-right', 'bottom-right'),
                        array('top', 'top (fixed)'),
                        array('right', 'right (fixed)'),
                        array('bottom', 'bottom (fixed)'),
                        array('left', 'left (fixed)'),
                        array('left-right', 'left-right'),
                        array('right-left', 'right-left'),
                        array('bottom-top', 'bottom-top'),
                        array('top-bottom', 'top-bottom')
                    ),
                    'comment' => array(
                        'EN' => '
                            Position of the 360° "Product Tour" slider, 
                            possible values: "top", "right", "bottom", "left" and any combination 
                            between those separated with dash.
                        ',
                        'DE' => '
                            Position der 360 "Produkt Tour" Galerie, 
                            mögliche Werte sind: "top", "right", "bottom", "left", sowie 
                            jegliche Kombination getrennt durch einen Strich.
                        '
                    )
                ),

                'cropSliderPositionSwitch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'string, int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'auto',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Possible values: "auto" as string or integer. 
                            Auto means depending on whether the screen is in portrait or 
                            landscape mode the first or second value of "cropSliderPosition" 
                            suited for the resolution will be chosen. For the value set as 
                            integer the second position from "cropSliderPosition" will be 
                            chosen if width of the screen is less than this integer and 
                            the position suits for current resolution.
                        ',
                        'DE' => '
                            Mögliche Werte: "auto" als String oder Integer.
                            Auto bedeutet, je nachdem, ob der Bildschirm im Hochformat oder
                            Landscape-Modus ist, wird der erste oder zweite Wert aus "cropSliderPosition" 
                            automatisch passend zur Auflösung gewählt. Bei einer Zahl wird die zweite 
                            Position aus "cropSliderPosition" genommen, wenn die Bildschirmbreite 
                            kleiner ist als diese Zahl und die Position passt.
                        '
                    )
                ),

                'cropSliderPosPriority' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Prioritize placing 360° "Product Tour" slider, 
                            defined in "cropSliderPosition", before positioning fullscreen thumbnail slider, 
                            defined in "fsSliderPosition".
                        ',
                        'DE' => '
                            Priorisieren die Positionierung des 360° "Produkt Tour" Sliders, 
                            definiert in "cropSliderPosition", vor Positionierung des 
                            Vollbild Miniaturansichten Sliders, definiert in "fsSliderPosition".
                        '
                    )
                ),

                'cropSliderReposition' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'right-bottom',
                    'options' => array(
                        array('left-bottom', 'left-bottom'),
                        array('left-top', 'left-top'),
                        array('right-bottom', 'right-bottom'),
                        array('right-top', 'right-top'),
                        array('opposite', 'opposite')
                    ),
                    'comment' => array(
                        'EN' => '
                            In case the positions of the gallery slider and the slider for 360 Product tour collude, 
                            the alternative position. 
                            Possible values: "left-bottom", "left-top", "right-bottom", "right-top" and "opposite".
                        ',
                        'DE' => '
                            Falls die Positionen der slider für Gallerie und 360 Produkt Tour kollidieren, 
                            wird die alternative bzw. ausweichende Position gewählt. 
                            Mögliche Werte sind: "left-bottom", "left-top", 
                            "right-bottom", "right-top" und "opposite".
                        '
                    )
                ),

                'cropSliderDimension' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 86,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Width or height (depending on position) of the instantly 
                            created container for the 360° "Product Tour" thumb slider.
                        ',
                        'DE' => '
                            Breite oder Höhe (je nach Position) des Containers in dem 
                            der 360° "Product Tour" Galerie angezeigt wird. 
                        '
                    )
                ),

                'cropSliderThumbAutoSize' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumb CSS size will be set instantly depending on "cropSliderDimension" option.
                        ',
                        'DE' => '
                            CSS Werte für die 360° "Product Tour" 
                            automatisch in Abhängigkeit von "cropSliderDimension" setzen. 
                        '
                    )
                ),

                'cropSliderThumbAutoMargin' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 10,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumb margin when set instantly.
                        ',
                        'DE' => '
                            Anstand der Bilder wenn "cropSliderThumbAutoSize" eingeschaltet ist.
                        '
                    )
                ),

                'cropSliderThumbDescr' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable descriptions for the thumbs in the slider for 360° "Product Tour".
                        ',
                        'DE' => '
                            Beschreibungen in der 360° "Produkt Tour" Galerie aktivieren.
                        '
                    )
                ),

                'cropSliderThumbDescrMargin' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Extend margin under the thumbs for thumb descriptions.
                        ',
                        'DE' => '
                            Abstand unter den Miniaturansichten für Beschreibungen erweitern.
                        '
                    )
                ),

                'cropSliderMinSize' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 400,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Minimal size of the player (width or height) for 360° "Product Tour" gallery 
                            (if present) to be shown.
                        ',
                        'DE' => '
                            Minimale Größe des Players um die 360° "Product Tour" sichtbar zu machen.
                        '
                    )
                ),

                'cropSliderNotParent' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => '',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Prevent showing 360° "Product Tour" gallery when the parent container 
                            matches the id or CSS class represented as string in this option value, 
                            e.g. "az_mouseOverZoomContainer". 
                            For more than one value split them with vertical dash.
                        ',
                        'DE' => '
                            Verhindert das Anzeigen der 360° "Produkt Tour" Gallerie, wenn 
                            übergeordneter Container den Wert dieser Option als ID oder 
                            CSS Klasse entspricht, z.B. "az_mouseOverZoomContainer". 
                            Für mehrere Werte können diese mit einem vertikalen Strich 
                            in einem String getrennt werden.
                        '
                    )
                ),

                'cropAxZmEbOpt' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'product_tour',
                    'type' => 'obejct',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 150,
                    'default' => '{
    "marginX": 5,
    "marginY": 5,
    "openSpeed": 300,
    "closeSpeed": 300,
    "fadeInSpeed": 200,
    "autoOpen": false,
    "arrow": true,
    "thumbSlider": true,
    "parO": "#axZm_zoomLayer",
    "dynText": true,
    "dynTextBtn": [
        {"base": 0.016, "min": 12, "max": 24, "important": true}
    ],
    "dynTextTitle": [
        {"base": 0.016, "min": 18, "max": 28, "important": true}
    ],
    "dynTextDescr": [
        ["*", {"base": 0.012, "min": 12, "max": 24, "important": true}],
        ["h3", {"base": 0.016, "min": 16, "max": 32, "important": true}]
    ]
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Options for $.axZmEb function which is used for descriptions for 
                            360° "Product Tour". Documentation to be updated...
                        ',
                        'DE' => '
                            Einstellungen für die $.axZmEb Funktion, welche für die Beschreibungen der 
                            360° "Product Tour" genutzt werden. Dokumentation muss noch geschrieben werden...
                        '
                    )
                ),

                'fsAxZmThumbSliderParam' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'obejct',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'example' => '{
    "scrollBy": 1,
    "btn": true,
    "btnClass": "axZmThumbSlider_button_new",
    "btnLeftText": null,
    "btnRightText": null,
    "btnHidden": true,
    "pressScrollSnap": true,
    "centerNoScroll": true,
    "wrapStyle": {
        "borderWidth": 0
    }
}',
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            $.axZmThumbSlider options for fullscreen gallery. 
                            For full list of options see under: 
                            /modules/ajaxzoom/axZm/extensions/axZmThumbSlider/ 
                        ',
                        'DE' => '
                            $.axZmThumbSlider Optionen für Vollbild Galerie. 
                            Für die komplette Liste der Optionen siehe 
                            http://www.ajax-zoom.com/axZm/extensions/axZmThumbSlider/
                        '
                    )
                ),

                'fsSliderPosition' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'right-bottom',
                    'options' => array(
                        array('left-bottom', 'left-bottom'),
                        array('left-top', 'left-top'),
                        array('right-bottom', 'right-bottom'),
                        array('right-top', 'right-top'),
                        array('top-left', 'top-left'),
                        array('top-right', 'top-right'),
                        array('bottom-left', 'bottom-left'),
                        array('bottom-right', 'bottom-right'),
                        array('top', 'top (fixed)'),
                        array('right', 'right (fixed)'),
                        array('bottom', 'bottom (fixed)'),
                        array('left', 'left (fixed)'),
                        array('left-right', 'left-right'),
                        array('right-left', 'right-left'),
                        array('bottom-top', 'bottom-top'),
                        array('top-bottom', 'top-bottom')
                    ),
                    'comment' => array(
                        'EN' => '
                            Position of the fullscreen thumbnail slider. 
                            Possible values are: 
                            "top", "right", "bottom", "left" and any combination between those separated with dash, 
                            e.g. "right-bottom". Positions with dash mean that the second after dash 
                            is the alternative position which could be switched instantly depending on the logic 
                            described in "fsSliderPositionSwitch" option.
                        ',
                        'DE' => '
                            Position der Vollbild Galerie. Mögliche Werte sind top, left, right, bottom, 
                            sowie jegliche Kombination aus den obigen getrennt mit einem Strich, 
                            also z.B. left-bottom. Positionen mit Strich (also zwei) bedeuten, dass je nach 
                            einstellbaren Logik, beschrieben in "fsSliderPositionSwitch" Option, 
                            die Position sich dynamisch verändern kann.
                        '
                    )
                ),

                'fsSliderReposition' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'right-bottom',
                    'options' => array(
                        array('left-bottom', 'left-bottom'),
                        array('left-top', 'left-top'),
                        array('right-bottom', 'right-bottom'),
                        array('right-top', 'right-top'),
                        array('opposite', 'opposite')
                    ),
                    'comment' => array(
                        'EN' => '
                            In case sliders position collude, the alternative position. 
                            Possible values: "left-bottom", "left-top", "right-bottom", "right-top", "opposite".
                        ',
                        'DE' => '
                            Falls die Positionen der slider für Gallerie und 360 Produkt Tour kollidieren, 
                            wird die alternative bzw. ausweichende Position gewählt. 
                            Mögliche Werte sind: "left-bottom", "left-top", 
                            "right-bottom", "right-top" und "opposite".
                        '
                    )
                ),

                'fsSliderPositionSwitch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'string, int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'auto',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Switch fsSliderPosition instantly depending on certain conditions. 
                            This switch is only applied when fsSliderPosition value is 
                            "top-left", "top-right", "bottom-left" or "bottom-right". 
                            Possible values are "auto" or integer; 
                            When "auto" is enabled an alternative position will be chosen depending 
                            on screen being in portrait or landscape mode. If value of 
                            fsSliderPositionSwitch is integer, alternative position will be 
                            only chosen if screen width (or height - depending on first value 
                            of fsSliderPosition before dash) is less than fsSliderPositionSwitch value.
                        ',
                        'DE' => '
                            Mögliche Werte: "auto" als String oder Integer.
                            Auto bedeutet, je nachdem, ob der Bildschirm im Hochformat oder
                            Landscape-Modus ist, wird der erste oder zweite Wert aus "fsSliderReposition"  
                            automatisch passend zur Auflösung gewählt. Bei einer Zahl wird die zweite 
                            Position aus "fsSliderReposition" genommen, wenn die Bildschirmbreite 
                            kleiner ist als diese Zahl und die Position passt.
                        '
                    )
                ),

                'fsSliderDimension' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 80,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Width or height of the container for fullscreen slider 
                            (depends on if it is vertical or horizontal).
                        ',
                        'DE' => '
                            Breite oder Höhe (je nach Position) des Containers in dem 
                            der Vollbild Galerie angezeigt wird.
                        '
                    )
                ),

                'fsSliderThumbAutoSize' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Adjust fsSliderDimension depending on 
                            fsAxZmThumbSliderParam instantly (to be improved).
                        ',
                        'DE' => '
                            CSS werte für Vollbild Galerie Miniaturansichten 
                            automatisch in Abhängigkeit von "fsSliderDimension" setzen. 
                        '
                    )
                ),

                'fsSliderThumbAutoMargin' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 7,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Thumbnails margin when fsSliderThumbAutoSize is enabled.
                        ',
                        'DE' => '
                            Anstand der Miniaturansichten wenn "fsSliderThumbAutoSize" eingeschaltet ist.
                        '
                    )
                ),

                'fsSliderThumbAutoExtend' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Extend slider width / height by this pixel value when set instantly.
                        ',
                        'DE' => '
                            Erweitern die Breite bzw. Höhe der Vollbild Galerie um einen festen Wert, 
                            wenn "fsSliderThumbAutoSize" eingeschaltet ist.
                        '
                    )
                ),

                'fsSliderThumbDescr' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Dummy for future implementation (todo).
                        ',
                        'DE' => '
                            Dummy für künftige Implementierung (todo).
                        '
                    )
                ),

                'fsSliderMinSize' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 400,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Minimal size of the player for fullscreen gallery to be shown.
                        ',
                        'DE' => '
                            Minimale Größe des Players, damit die Vollbild Galerie angezeigt wird.
                        '
                    )
                ),

                'fsSliderGalleryFadeInAnm' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'Center',
                    'options' => array(
                        array('Center', 'Center'),
                        array('Top', 'Top'),
                        array('Right', 'Right'),
                        array('Bottom', 'Bottom'),
                        array('Left', 'Left'),
                        array('StretchVert', 'StretchVert'),
                        array('StretchHorz', 'StretchHorz'),
                        array('SwipeHorz', 'SwipeHorz'),
                        array('SwipeVert', 'SwipeVert'),
                        array('Vert', 'Vert'),
                        array('Horz', 'Horz')
                    ),
                    'comment' => array(
                        'EN' => '
                            Switching animation between images in fullscreen mode. 
                            Possible values: "Center", "Top", "Right", "Bottom", "Left", 
                            "StretchVert", "StretchHorz", "SwipeHorz", "SwipeVert", "Vert", "Horz".
                        ',
                        'DE' => '
                            Animation beim Umschalten zwischen den Bildern. 
                            Mögliche Werte sind: "Center", "Top", "Right", "Bottom", "Left", 
                            "StretchVert", "StretchHorz", "SwipeHorz", "SwipeVert", "Vert", "Horz".
                        '
                    )
                ),

                'fsSliderNotParent' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'axZm_mouseOverSpinWrapper',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Prevent showing fullscreen gallery when the parent container 
                            matches the id or CSS class represented as string in this option value, 
                            e.g. "az_mouseOverZoomContainer". 
                            For more than one value split them with vertical dash.
                        ',
                        'DE' => '
                            Verhindert das Anzeigen der Vollbild Galerie, wenn 
                            übergeordneter Container den Wert dieser Option als ID oder 
                            CSS Klasse entspricht, z.B. "az_mouseOverZoomContainer". 
                            Für mehrere Werte können diese mit einem vertikalen Strich 
                            in einem String getrennt werden.
                        '
                    )
                ),

                'onGallerySwitch' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'fullscreen_gallery',
                    'type' => 'function',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => null,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Callback function triggered every time an item is switched. 
                            The function receives 4 argumets: index of the item, item information as object, 
                            mouseover instance and gallery instance. 
                            In AJAX-ZOOM modules avoid writing the actual functions, instead pass a reference, 
                            e.g. window.myCustomFunc
                        ',
                        'DE' => '
                            Callback-Funktion ausgelöst, wenn ein Element umgeschaltet wird. 
                            Die Funktion erhält 4 Argumete: Index des Items, Item Information als 
                            Objekt, Mouseover Instanz und Galerie Instanz. 
                            In AJAX-ZOOM Modulen meiden Sie es die Funktion selbst zu schreiben, 
                            stattdessen referenzieren Sie einfach, z.B. window.myCustomFunc
                        '
                    )
                ),
                
                // mouseover settings
                'position' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'important' => true,
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'right',
                    'options' => array(
                        array('inside', 'inside'),
                        array('top', 'top'),
                        array('right', 'right'),
                        array('bottom', 'bottom'),
                        array('left', 'left')
                    ),
                    'comment' => array(
                        'EN' => '
                            Position of the flyout zoom window, possible values: 
                            "inside", "top", "right", "bottom", "left".
                        ',
                        'DE' => '
                            Position des mouseover Zoom Fensters, 
                            mögliche Werte: "inside", "top", "right", "bottom", "left".
                        '
                    )
                ),

                'posAutoInside' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 150,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Applies when width (left, right) or height (top, bottom) 
                            of zoom window are less than this px value 
                            (zoomWidth || zoomHeight are set to auto); 
                            if zoomWidth || zoomHeight are fixed, 
                            applies when zoom window is out of page border.
                        ',
                        'DE' => '
                            Automatische Umschaltung von "position" Option auf "inside" wenn 
                            Breite oder Höhe des Zoom Fensters diesen Wert unterschreitet und 
                            (zoomWidth || zoomHeight werden automatisch gesetzt). 
                            Wenn zoomWidth || zoomHeight fixe Angaben sind, dann 
                            wird diese Unschaltung angewandt, wenn das Zoom Fenster 
                            nicht ganz zu sehen wäre. 
                        '
                    )
                ),

                'touchScroll' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.8,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            If width of the mouseover zoom container is more than 80% (0.8) of the window width, 
                            then for touch devises the inner zoom will be not 
                            triggered and the user can scroll down. 
                            Click for open AJAX-ZOOM remains. 
                            Set this value to 0 if you want to enable the slider for touch devices only.
                        ',
                        'DE' => '
                            Wenn Preview Container breiter ist, als 80% (0.8) des Browser Fensters, 
                            dann wird für touch Devices das inner zoom deaktiviert und der user kann 
                            runterscrollen. Klick für AJAX-ZOOM bleibt. 
                            Wenn dieser Wert 0 ist, dann wird diese Funktion 
                            des Sliders nur für Touch-Geräte Aktiviert.
                        '
                    )
                ),

                'noMouseOverZoom' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Disable mouseover zoom for all devices.
                        ',
                        'DE' => '
                            Mouseover zoom komplett deaktivieren. 
                            Wenn Sie dies nur für touch Devices aktivieren wollen, dann setzen Sie 
                            "touchScroll" Option auf 0.
                        '
                    )
                ),

                'noMouseOverZoomTouch' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable "noMouseOverZoom" only for touch devices 
                            (mouseover zoom works for touch devices too but is not very convenient 
                            so this option is enabled on default now).
                        ',
                        'DE' => '
                            Aktivieren der "noMouseOverZoom" Option nur für Touch-Geräte. 
                            (Mouseover-Zoom funktioniert auch für Touch-Geräte, 
                            es ist aber nicht sehr bequem, so dass diese Option 
                            jetzt standardmäßig aktiviert ist).
                        '
                    )
                ),

                'noMouseOverZoomInside' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Disable mouseover zoom if it is \'inside\' (inner zoom).
                        ',
                        'DE' => '
                            Mouseover-Zoom deaktivieren, wenn es "inside" ist.
                        '
                    )
                ),

                'noMouseOverZoomInsideTouch' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Disable mouseover zoom if it is inside (inner zoom) and touch event.
                        ',
                        'DE' => '
                            Mouseover-Zoom deaktivieren, 
                            wenn es "inside" ist und es sich um Touch-Geräte handelt.
                        '
                    )
                ),

                'mouseOverZoomHybrid' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'important' => true,
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable mouseover zoom and slider at the same time for not touch devices.
                        ',
                        'DE' => '
                            Aktivieren des Mouseover-Zoom und Sliders für Vorschaubilder 
                            gleichzeitig für nicht Touch-Geräte.
                        '
                    )
                ),

                'slideTouchTime' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 200,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Slide time in ms after touch and drag.
                        ',
                        'DE' => '
                            Slide Zeit in ms nach Berühren und ziehen.
                        '
                    )
                ),

                'slideTime' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 300,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Slide time after clicked on prev / next buttons.
                        ',
                        'DE' => '
                            Slide Zeit in ms nach klicken auf prev / next Tasten.
                        '
                    )
                ),

                'posInsideArea' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.2,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            When "posAutoInside" is enabled and inner zoom fires or "position" 
                            option is set to \'inside\' right away - there is no lens. 
                            However you will notice that the reaction to mouse movements occure somewhere 
                            in the middle of the image; 
                            at the edges mostly nothing happens in similar scripts. 
                            With this option you can adjust how far from 
                            the edge mouse movements should be captured. 
                            The range is between 0 and 0.5;
                        ',
                        'DE' => '
                            Wenn "posAutoInside" eingeschaltet ist und Inner Zoom eingeschaltet wird, 
                            bzw. "position" ist bereits auf \'inside\' eingestellt, 
                            dann ist die Linse nicht sichtbar. Je nach Größe des Bildes wird man dann merken, 
                            dass nur innerhalb des Zentrums des Bildes das Innere Zoom bewegt wird. 
                            Mit dieser Option kann dem entgegengewirkt werden. 
                            Der Wertebereich liegt zwischen 0 und 0.5;
                        '
                    )
                ),

                'posInsideScaleAnm' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 20,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            When inner zoom fires there will be a zoom 
                            animation. This value determines the smoothness of this zoom animation. 
                            The higher the value, the longer the animation will last. Set to 0 to disable.
                        ',
                        'DE' => '
                            Wenn der innere Zoom eingeschaltet ist, wird es eine Zoom-Animation ausgeführt. 
                            Dieser Wert bestimmt die dauer dieser Zoom-Animation. 
                            Je größer der Wert, desto länger wird die Animation dauern. 
                            Auf 0 setzen, um zu deaktivieren
                        '
                    )
                ),

                'autoFlip' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 200,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Flip right to left and bottom to top if less than int px value or false to disable.
                        ',
                        'DE' => '
                            Automatische Umkehren der Position right - left und bottom - top wenn 
                            Breite bzw. Höhe kleiner als dieser Wert sind. Setzen Sie diese Option auf false um 
                            sie auszuschalten.
                        '
                    )
                ),

                'biggestSpace' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Overrides position option and instantly chooses the direction, 
                            disables autoFlip; plays nicely when zoomWidth and zoomHeight are set to \'auto\'.
                        ',
                        'DE' => '
                            Wenn diese Option aktiviert ist, dann wird das Zoom Fenster im Browser dort angezeigt, 
                            wo am meisten Platz ist. Funktioniert gut, wenn "zoomWidth" und "zoomHeight" auf 
                            \'auto\' gesetzt sind. 
                        '
                    )
                ),

                'zoomFullSpace' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Uses full screen height (does not align to the map / disables adjustY) 
                            if position is right or left || uses full screen width 
                            (does not align to the map / disables adjustX) 
                            if position is top or bottom.
                        ',
                        'DE' => '
                            Durch die Aktivierung dieser Option wird die volle Breite im Browser ausgenutzt, 
                            wenn "position" right oder left ist. Wenn "position" top oder bottom ist, 
                            dann wird volle Höhe ausgenutzt.
                        '
                    )
                ),

                'zoomWidth' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'important' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 530,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Width of the zoom window e.g. 540 or \'auto\' or 
                            jQuery selector|correction value, e.g. \'#refWidthTest|+20\'; 
                            so if you want to have a width of the zoom window same as 
                            for example a responsive container 
                            to the right (so it is fully covered) and max possible height, 
                            then define the id of this container to the right, 
                            e.g. \'myArticleData\', set "zoomWidth" 
                            to \'#myArticleData|+10\' and "zoomHeight" to \'auto\'. 
                            If you have a three column design and want to cover both containers to the right, 
                            then just define both containers in the jQuery selector, 
                            e.g. \'.pb-center-column,.pb-right-column|+20\'; 
                            the margin between the containers is not taken into account 
                            but you can adjust the result with the second value after vertical bar.
                        ',
                        'DE' => '
                            Breite des Zoom Fensters, z.B. 540 oder \'auto\'. 
                            Man kann aber auch einen jQuery Selector selector|correction als Referenz angeben mit 
                            zusätzlichen Korrekturwert, z.B. \'#refWidthTest|+20\'; 
                            wenn Sie also die Breite gleich einem anderen responsiven 
                            Container rechts von der Voransicht einstellen wollen, 
                            so dass es voll überdeckt wird und möglichst maximale Höhe 
                            erreichen wollen, dann definieren Sie am besten eine ID für diesen Container rechts, 
                            z.B. \'myArticleData\', setzen dann "zoomWidth" auf \'#myArticleData|+10\' 
                            und "zoomHeight" auf \'auto\'. Sie können aber auch mehrere Elemente überdecken, 
                            z.B. \'.pb-center-column,.pb-right-column|+20\'. 
                            Die Abstände zwischen den beiden Containern werden nicht mitberechnet, 
                            können aber meist über Korrekturwert ausgeglichen werden.
                        '
                    )
                ),

                'zoomHeight' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'important' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 450,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Height of the zoom window e.g. 375, or \'auto\' or jQuery selector|correction value, 
                            e.g. \'#refWidthTest|+20\'; if your selector matches more than one element, 
                            e.g. \'.pb-center-column,.pb-right-column|+20\', then 
                            the highest value will be chosen. This is different from 
                            the multiple selector in "zoomWidth", where the values are added.
                        ',
                        'DE' => '
                            Höhe des Zoom Fensters, z.B. 375, \'auto\' oder jQuery selector|correction als Referenz
                            mit zusätzlichen Korrekturwert, z.B. \'#refWidthTest|+20\'. 
                            Wenn der Selector mehr als ein Element umfasst, dann wird anderes als bei "zoomWidth" 
                            der höchste Höhenwert genommen. 
                        '
                    )
                ),

                'autoMargin' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 15,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            If zoomWidth or zoomHeight are set to \'auto\', the margin to the edge of the screen.
                        ',
                        'DE' => '
                            Wenn "zoomWidth" oder "zoomHeight" auf \'auto\' gesetzt sind, 
                            ist dieser Wert der automatische Abstand vom Rand des Browser Fensters.
                        '
                    )
                ),

                'adjustX' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 15,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Horizontal margin of the zoom window.
                        ',
                        'DE' => '
                            Horizontaler Versatz zwischen Voransicht und Zoom Fenster.
                        '
                    )
                ),

                'adjustY' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => -1,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Vertical margin of the zoom window.
                        ',
                        'DE' => '
                            Vertikaler Versatz zwischen Voransicht und Zoom Fenster.
                        '
                    )
                ),

                'lensOpacity' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1.0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Opacity of the selector lens.
                        ',
                        'DE' => '
                            Durchsichtigkeit der Zoom Linse.
                        '
                    )
                ),

                'lensStyle' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 50,
                    'default' => '{}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Quickly override CSS of the lens.
                        ',
                        'DE' => '
                            Hier kann man schnell Inline CSS reinschreiben.
                        '
                    )
                ),

                'lensClass' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => '',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Define CSS class for the lens.
                        ',
                        'DE' => '
                            Name der CSS Klasse für die Zoom Linse.
                        '
                    )
                ),

                'lensMessage' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en" : "Full screen",
    "de": "Vollbild",
    "fr": "Plein écran",
    "es": "Pantalla completa"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Text string on the lens. 
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Text in der Linse. 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomAreaBorderWidth' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 1,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Border thickness of the zoom window.
                        ',
                        'DE' => '
                            Breite des Randes (Border) für das Zoom Fenster.
                        '
                    )
                ),

                'galleryFade' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 300,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Speed of inner fade or false.
                        ',
                        'DE' => '
                            Die Geschwindigkeit des Blendvorganges in ms 
                            beim Wechsel zwischen den Bildern oder false.
                        '
                    )
                ),

                'shutterSpeed' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 150,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Speed of shutter fadein or false; applies only 
                            if image proportions are different from container.
                        ',
                        'DE' => '
                            Die Geschwindigkeit der Anpassung von Proportionen 
                            während des Überblendvorgangs in ms oder false.
                        '
                    )
                ),

                'showFade' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 300,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Speed of fade in for mouse over.
                        ',
                        'DE' => '
                            Geschwindigkeit des Einblendens von Zoom Fenster.
                        '
                    )
                ),

                'hideFade' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 300,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Speed of fade out for mouse over.
                        ',
                        'DE' => '
                            Geschwindigkeit des Ausblendens von Zoom Fenster.
                        '
                    )
                ),

                'flyOutSpeed' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Speed for flyout or false to disable.
                        ',
                        'DE' => '
                            Geschwindigkeit des "Ausfliegens" des Zoom Fensters in ms 
                            oder false zum Deaktivieren.
                        '
                    )
                ),

                'flyOutTransition' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'linear',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Transition of the flyout.
                        ',
                        'DE' => '
                            Typ der Transition für das "Ausfliegen" des Zoom Fensters.
                        '
                    )
                ),

                'flyOutOpacity' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.6,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Initial opacity for flyout.
                        ',
                        'DE' => '
                            Anfängliche Durchsichtigkeit des Zoom Fensters beim "Ausfliegen".
                        '
                    )
                ),

                'flyBackSpeed' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Speed for fly back or false to disable.
                        ',
                        'DE' => '
                            Geschwindigkeit des "Zurückfliegens" des Zoom Fensters in ms 
                            oder false zum Deaktivieren.
                        '
                    )
                ),

                'flyBackTransition' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'linear',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Transition type of the fly back.
                        ',
                        'DE' => '
                            Typ der Transition für das "Zurückfliegen" des Zoom Fensters.
                        '
                    )
                ),

                'flyBackOpacity' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.2,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Final opacity of fly back.
                        ',
                        'DE' => '
                            Finale Durchsichtigkeit nach dem "Zurückfliegen".
                        '
                    )
                ),

                'autoScroll' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Scroll page when clicked on the thumb and the mouse over preview image 
                            is not fully visible.
                        ',
                        'DE' => '
                            Wenn auf die Galerie geklickt wird muss die Voransicht nicht ganz sichtbar sein,  
                            weil die Seite zu weit gescrollt wurde. 
                            Bei aktivieren dieser Option wird automatisch dorthin gescrollt, 
                            wo die Voransicht gänzlich sichtbar ist.
                        '
                    )
                ),

                'smoothMove' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'useful' => true,
                    'display' => 'text',
                    'height' => null,
                    'default' => 6,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Integer bigger than 1 indicates smoother movements. Set 0 to disable.
                        ',
                        'DE' => '
                            Geschmeidigkeit der Transition im Zoom Fenster. 
                            Zahl größer als 1 bedeutet mehr Geschmeidigkeit.  
                            Beim 0 wird die Geschmeidigkeit abgeschaltet.
                        '
                    )
                ),

                'tint' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Color value around the lens or false.
                        ',
                        'DE' => '
                            Farbwert um die Linse herum oder false zum Deaktivieren.
                        '
                    )
                ),

                'tintOpacity' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.3,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Opacity of the area around the lens when "tint" option is set to some color value.
                        ',
                        'DE' => '
                            Durchsichtigkeit der Fläche um die Linse herum, wenn 
                            "tint" Option einen Wert hat. 
                        '
                    )
                ),

                'tintFilter' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string, bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Apply filter to the image, 
                            e.g. "blur", "grayscale", "sepia", "lighten", "darken", "invert", "saturate"; 
                            see also .axZm_mouseOverEffect>img CSS.
                        ',
                        'DE' => '
                            Man kann auch einen CSS Filter für die Fläche um die Linse herum anwenden. 
                            z.B. "blur", "grayscale", "sepia", "lighten", "darken", "invert", "saturate"; 
                            siehe auch .axZm_mouseOverEffect>img CSS.
                        '
                    )
                ),

                'tintFilterBack' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'none',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Background-color if some "tintFilter" is enabled, e.g. #FFF
                        ',
                        'DE' => '
                            Background-color wenn ein "tintFilter" eiingeschaltet ist, z.B. #FFF
                        '
                    )
                ),

                'tintLensBack' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Show background image in the lens.
                        ',
                        'DE' => '
                            Hintergrundbild in der Linse anzeigen.
                        '
                    )
                ),

                'showTitle' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable / disable title on zoom window
                        ',
                        'DE' => '
                            Titel im Zoom Fenster anzeigen.
                        '
                    )
                ),

                'titlePosition' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'top',
                    'options' => array(
                        array('top', 'top'),
                        array('bottom', 'bottom'),
                        array('above', 'above')
                    ),
                    'comment' => array(
                        'EN' => '
                            Position of the title, possible values are: \'top\', \'bottom\' or \'above\'.
                        ',
                        'DE' => '
                            Position des Titels, mögliche Werte sind \'top\', \'bottom\' (im Zoom Fenster) 
                            oder \'above\'.
                        '
                    )
                ),

                'titleParentContainer' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => '',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            jQuery selector, e.g. #abc_title, 
                            as parent container for title other than inside the lense, 
                            can be anywhere.
                        ',
                        'DE' => '
                            jQuery selector, z.B. #abc_title, als Übergeordneten Container für den Titel / Text, 
                            welches sich nicht innerhalb des zoom Fensters befindet.
                        '
                    )
                ),

                'titlePermanent' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            When "titleParentContainer" is defined, title (text) will be set after loading / 
                            switch of the image.
                        ',
                        'DE' => '
                            Wenn "titleParentContainer" definiert ist, wird nach dem Laden / Umschalten 
                            des Bildes der Titel (Text) sofort gesetzt.
                        '
                    )
                ),

                'cursorPositionX' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.5,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Cursor over lens horizontal offset, 0.5 is middle
                        ',
                        'DE' => '
                            Horizontaler Versatz des Maus Zeigers in der Linse. 0.5 ist Mitte.
                        '
                    )
                ),

                'cursorPositionY' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.55,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Cursor over lens vertical offset, 0.5 is middle.
                        ',
                        'DE' => '
                            Vertikaler Versatz des Maus Zeigers in der Linse. 0.5 ist Mitte.
                        '
                    )
                ),

                'loading' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Display loading information, CSS class: .mouseOverLoading;
                            See also "spinner" option.
                        ',
                        'DE' => '
                            Zeige "loading" Information, CSS .mouseOverLoading; 
                            Siehe auch "spinner" option.
                        '
                    )
                ),

                'loadingMessage' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en": "Loading...",
    "de": "Loading...",
    "fr": "Loading...",
    "es": "Loading..."
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Loading message, not needed, can be just the "spinner" - see below.
                        ',
                        'DE' => '
                            Loading Nachricht, nicht unbedingt notwendig, kann auch nur der "spinner" sein.
                        '
                    )
                ),

                'zoomHintEnable' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable zoom icon which disappears on mouse hover; 
                            CSS class: .axZm_mouseOverZoomHint; 
                            if you want to change the position or the icon simply modify the CSS class;
                        ',
                        'DE' => '
                            Einen Icon als Indikator für Zoom Anzeigen. Dieser verschwindet bei mouseover. 
                            CSS Klasse: .axZm_mouseOverZoomHint; 
                            Wenn Sie eine andere Position wünschen, dann solltet Sie die CSS Klasse überschreiben.
                        '
                    )
                ),

                'zoomHintText' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object, string',
                    'isJsObject' => true,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "en" : "Zoom",
    "de" : "Zoom",
    "fr" : "Zoom",
    "es" : "Zoom"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Text which will be appended next to the icon enabled by "zoomHintEnable". 
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Text neben dem Icon wenn "zoomHintEnable" Option eingeschaltet ist. 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomMsgHover' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{ 
    "en" : "Roll over the image to zoom in",
    "de" : "Für größere Ansicht mit der Maus über das Bild ziehen",
    "fr" : "Survolez l\'image pour zoomer",
    "es" : "Pase el cursor sbore la imagen para hacer zoom con la rueda del ratón"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message which can appear under the mouse over zoom, CSS class: .axZm_mouseOverZoomMsg; 
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Textnachricht unter der Voransicht. CSS Klasse: .axZm_mouseOverZoomMsg; 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomMsgHoverTouch' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{ 
    "en" : "Tap to open expanded view",
    "de" : "Klicken Sie auf das Bild, um erweiterte Ansicht zu öffnen",
    "fr" : "Cliquez sur l\'image pour ouvrir la vue élargie",
    "es" : "Haga clic para ampliar la imagen"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message which can appear under the mouse over zoom for touch devices, 
                            CSS class: .axZm_mouseOverZoomMsg;
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Textnachricht unter der Voransicht für Touch Geräte. CSS Klasse: .axZm_mouseOverZoomMsg; 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'zoomMsgClick' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'object, string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => null,
                    'default' => '{
    "en" : "Click to open expanded view",
    "de" : "Klicken Sie auf das Bild, um erweiterte Ansicht zu öffnen",
    "fr" : "Cliquez sur l\'image pour ouvrir la vue élargie",
    "es" : "Haga clic para ampliar la imagen"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Message which can appear under the mouse over zoom when the mouse enters it, 
                            CSS class: .axZm_mouseOverZoomMsg; 
                            For more than one language define a js object, 
                            e.g. {"en": "english text", "de": "german text"}
                        ',
                        'DE' => '
                            Textnachricht, welche unter der Voransicht erscheint, 
                            wenn der User mit der Maus darüber geht. 
                            CSS Klasse: .axZm_mouseOverZoomMsg; 
                            Für mehrere Sprachen können Sie ein js Objekt definieren, 
                            z.B. {"en": "english text", "de": "german text"}
                        '
                    )
                ),

                'slideInTime' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'int',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 200,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Slide in time if "noMouseOverZoom" is enabled 
                            or "touchScroll" option enables for touch devices.
                        ',
                        'DE' => '
                            Geschwindigkeit des Slideanimation, wenn "noMouseOverZoom" aktiviert ist 
                            oder durch "touchScroll" Option dies einschaltet.
                        '
                    )
                ),

                'slideInEasingCSS3' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'easeOutExpo',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            jQuery equivalent of easing or own function (string), 
                            e.g. "cubic-bezier(0.21,0.51,0.4,2.02)", see also cubic-bezier.com
                        ',
                        'DE' => '
                            jQuery Äquivalent für easing oder eigene Funktion, 
                            z.B. "cubic-bezier(0.21,0.51,0.4,2.02)", siehe auch cubic-bezier.com
                        '
                    )
                ),

                'slideInEasing' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'easeOutExpo',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            jQuery easing function for sliding in (fallback if CSS3 animation is not supported).
                        ',
                        'DE' => '
                            jQuery easing Funktion für Slide in (Fallback für CSS3 Animationen).
                        '
                    )
                ),

                'slideInScale' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.8,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Scale initial size (goes eventually to 1.0 while animation).
                        ',
                        'DE' => '
                            Anfängliche Skalierung (geht dann während der Animation bis 1.0).
                        '
                    )
                ),

                'slideOutScale' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0.8,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Scale slideout size.
                        ',
                        'DE' => '
                            Ziel Skalierung.
                        '
                    )
                ),

                'slideOutOpacity' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'float',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 0,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Slideout opacity.
                        ',
                        'DE' => '
                            Slideout Durchsichtigkeit.
                        '
                    )
                ),

                'slideOutDest' => array(
                    'prefix' => 'AJAXZOOM_MOZP',
                    'category' => 'mouseover',
                    'type' => 'integer',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 4,
                    'options' => array(
                        array('1', '1'),
                        array('2', '2'),
                        array('3', '3'),
                        array('4', '4'),
                    ),
                    'comment' => array(
                        'EN' => '
                            Target slideout position, possible values: 1, 2, 3 or 4;
                        ',
                        'DE' => '
                            Ziel slideout Position. Mögliche Werte sind: 1, 2, 3 oder 4;
                        '
                    )
                ),

                'onInit' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'function',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Callback JS function or reference ot it.
                        ',
                        'DE' => '
                            Callback JS Funktion oder Referenz.
                        '
                    )
                ),

                'onLoad' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'function, null',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Callback JS function or reference ot it.
                        ',
                        'DE' => '
                            Callback JS Funktion oder Referenz.
                        '
                    )
                ),

                'onImageChange' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'function, null',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Callback JS function or reference ot it.
                        ',
                        'DE' => '
                            Callback JS Funktion oder Referenz.
                        '
                    )
                ),

                'onMouseOver' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'function, null',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Callback JS function or reference ot it.
                        ',
                        'DE' => '
                            Callback JS Funktion oder Referenz.
                        '
                    )
                ),

                'onMouseOut' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'function, null',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Callback JS function or reference ot it.
                        ',
                        'DE' => '
                            Callback JS Funktion oder Referenz.
                        '
                    )
                ),

                'spinner' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Use ajax loading spinner without gif files etc. 
                            Set to false to disable. 
                        ',
                        'DE' => '
                            CSS / JS Spinner für Loading Animation verwenden.
                        '
                    )
                ),

                'spinnerParam' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'mouseover',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 150,
                    'default' => '{ 
    "lines": 11,
    "length": 3,
    "width": 3,
    "radius": 4,
    "corners": 1,
    "rotate": 0, 
    "color": "#FFFFFF",
    "speed": 1,
    "trail": 90, 
    "shadow": false,
    "hwaccel": false,
    "className": "spinner", 
    "zIndex": 2e9,
    "top": 0, 
    "left": 1 
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Spinner options, for more info see: 
                            http://fgnass.github.com/spin.js/ 
                        ',
                        'DE' => '
                            Optionen für den Spinner, siehe auch http://fgnass.github.com/spin.js/
                        '
                    )
                ),
                
                'imagesVideoFirstToLoad' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Load video first if present. See also "images360firstToLoad" option.
                        ',
                        'DE' => '
                            Videos, wenn vorhanden, zuerst laden. Siehe auch "images360firstToLoad" Option.
                        '
                    )
                ),

                'imagesVideoImg' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-play-256.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Used as replacement if "imagesVideoThumb" 
                            is enabled and poster image is missing.
                        ',
                        'DE' => '
                            Wird als Ersatz für das nicht Vorhandene Posterbild des Videos genutzt, 
                            wenn "imagesVideoThumb" aktiviert ist.
                        '
                    )
                ),

                'imagesVideoBroken' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-play-256.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Replacement thumbnail image if instant loading of the poster image 
                            from youtube, vimeo etc. fails.
                        ',
                        'DE' => '
                            Ersatz für Posterbild, wenn das Laden von YouTube, Vimeo etc. 
                            nicht funktioniert. 
                        '
                    )
                ),

                'imagesVideoOverlay' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => false,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Add a div with class "videoOverlImg" or "videoOverl" over the gallery thumb.
                        ',
                        'DE' => '
                            Fügt einen div Container mit Klasse "videoOverlImg" oder "videoOverl" 
                            über die Galerie Miniaturansicht.
                        '
                    )
                ),

                'imagesVideoThumb' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Show real poster image as thumb in the gallery if defined.
                        ',
                        'DE' => '
                            Zeigt reales Posterbild als Miniaturansicht in der Galerie, falls definiert.
                        '
                    )
                ),

                'imagesVideoBig' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-play-1200.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Placeholder image for videos in mouseover.
                        ',
                        'DE' => '
                            Platzhalter Bild für Videos in Mouseover.
                        '
                    )
                ),

                'imagesVideoSmall' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-play-600.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Placeholder image for videos in mouseover.
                        ',
                        'DE' => '
                            Platzhalter Bild für Videos in Mouseover.
                        '
                    )
                ),

                'videoThumb' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 150,
                    'default' => '{
    "youtube": {
        "url": "https://i1.ytimg.com/vi/",
        "img": "mqdefault"
    },
    "vimeo": {
        "url": "https://vimeo.com/api/v2/video/",
        "img": "thumbnail_medium"
    },
    "dailymotion": {
        "url": "https://api.dailymotion.com/video/",
        "img": "thumbnail_480_url"
    }
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Settings including sources for poster images for 
                            YouTube, Vimeo and Dailymotion.
                        ',
                        'DE' => '
                            Einstellungen für Quellen, sowie Größe für Posterbilder aus 
                            YouTube, Vimeo und Dailymotion.
                        '
                    )
                ),

                'videoThumbDefaultPostion' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'select',
                    'height' => null,
                    'default' => 'last',
                    'options' => array(
                        array('first', 'first'),
                        array('afterfirst', 'afterfirst'),
                        array('last', 'last'),
                    ),
                    'comment' => array(
                        'EN' => '
                            Default position of the thumbnail representing a video in the gallery. 
                            The position can be also defined for each thumb individually. 
                            Possible values are: "first", "afterfirst" and "last". 
                            See also "images360ThumbDefaultPostion" option. 
                        ',
                        'DE' => '
                            Standardposition der Miniaturansicht, die ein Video in der Galerie darstellt. 
                            Die Position kann auch für jedes Video - Miniaturansicht individuell definiert werden.
                            Mögliche Werte sind: "first", "afterfirst" und "last".
                            Siehe auch "images360ThumbDefaultPostion" Option.
                        '
                    )
                ),

                'videoUrl' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 70,
                    'default' => '{
    "youtube": "//www.youtube-nocookie.com/embed/",
    "vimeo": "//player.vimeo.com/video/",
    "dailymotion": "//www.dailymotion.com/embed/video/"
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Url of the external players.
                        ',
                        'DE' => '
                            URL für die externen Player.
                        '
                    )
                ),

                'videoSettings' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 200,
                    'default' => '{
    "youtube": {
        "autoplay": 0,
        "controls": 1,
        "showinfo": 0,
        "loop": 0,
        "fs": 1,
        "rel": 0,
        "modestbranding": 0,
        "iv_load_policy": 3,
        "theme": "light"
    },
    "vimeo": {
        "autopause": 1,
        "autoplay": 0,
        "badge": 1,
        "byline": 0,
        "color": "eaeaea",
        "loop": 0,
        "portrait": 0,
        "title": 0
    },
    "dailymotion": {
        "autoplay": 0,
        "logo": 0,
        "quality": 720,
        "related": 0,
        "html5": 1
    },
    "html5": {
        "controls": true,
        "autoplay": false,
        "loop": false,
        "muted": false,
        "preload": "auto"
    }
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Api / default parameters for the players.
                        ',
                        'DE' => '
                            API / Standardeinstellungen für die Video Player.
                        '
                    )
                ),

                'videoHtml5ClickPlay' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Toggle play/pause for HTML5 players when clicked anywhere on the player.
                        ',
                        'DE' => '
                              Toggle Play / Pause für HTML5-Player, wenn irgendwo auf dem Player geklickt wird.
                        '
                    )
                ),

                'videoHtml5Poster' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-play-1200.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Specific default poster image for HTML5 player.
                        ',
                        'DE' => '
                            Standard Ersatzposter für HTML5 Videos.
                        '
                    )
                ),

                'videoHtml5VideoJs' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'bool',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'switch',
                    'height' => null,
                    'default' => true,
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Enable Videojs player for html5 videos.
                        ',
                        'DE' => '
                            Videojs Player für HTML5 Videos aktivieren.
                        '
                    )
                ),

                'videoHtml5VideoJsSkin' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'vjs-default-skin',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Skin for Videojs player.
                        ',
                        'DE' => '
                            Skin für Videojs Player.
                        '
                    )
                ),

                'videoHtml5VideoJsPoster' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'string',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'text',
                    'height' => null,
                    'default' => 'media-video-1200.png',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Specific default poster image for HTML5 Videojs player.
                        ',
                        'DE' => '
                            Standard Ersatzposter für HTML5 - Videojs Players.
                        '
                    )
                ),

                'videoHtml5VideoJsOpt' => array(
                    'prefix' => 'AJAXZOOM',
                    'category' => 'video_settings',
                    'type' => 'object',
                    'isJsObject' => false,
                    'isJsArray' => false,
                    'display' => 'textarea',
                    'height' => 100,
                    'default' => '{
    "controlBar": {
        "muteToggle": false
    },
    "techOrder": ["html5", "flash", "other supported tech"]
}',
                    'options' => null,
                    'comment' => array(
                        'EN' => '
                            Options for Videojs player.
                        ',
                        'DE' => '
                            Standard Optionen für Videojs Player.
                        '
                    )
                )

            );
        }

        public function extendConfig($after = 'pngModeCssFix')
        {
            if (!empty($this->config_extend)) {
                foreach ($this->config_extend as $k => $v) {
                    $keys = array_keys($this->config);
                    $idx = array_search($after, $keys);
                    if ($idx > 0) {
                        $idx += 1;
                        $v['category'] = 'plugin_settings';
                        $this->config = array_merge(
                            array_slice($this->config, 0, $idx),
                            array($k => $v),
                            array_slice($this->config, $idx)
                        );
                        $after = $k;
                    }
                }
            }
        }

        public function getCategories()
        {
            return $this->categories;
        }

        public function getOptionsList()
        {
            $cnf = $this->config;
            $ret = array();

            foreach ($cnf as $k => $v) {
                if (in_array($v['category'], $this->exclude_cat_vendor)
                    || in_array($k, $this->exclude_opt_vendor)
                ) {
                    continue;
                }

                if ($this->vendor && isset($v['vendor']) && !empty($v['vendor']) && is_array($v['vendor'])) {
                    if (!in_array($this->vendor, $v['vendor'])) {
                        continue;
                    }
                }

                $ret[$k] = $v['prefix'].'_'.strtoupper($k);
            }

            return $ret;
        }

        public function getConfig($plain = false)
        {
            $cnf = $this->config;
            if (!$plain && !empty($this->config_vendor)) {
                foreach ($this->config_vendor as $k => $v) {
                    if (isset($cnf[$k])) {
                        $cnf[$k]['default'] = $v;
                    }
                }
            }

            if (!$plain && $this->vendor) {
                foreach ($this->exclude_opt_vendor as $v) {
                    if (isset($cnf[$v])) {
                        unset($cnf[$v]);
                    }
                }
            }

            if (!$plain && $this->vendor) {
                foreach ($cnf as $k => $v) {
                    if (!empty($this->exclude_cat_vendor)) {
                        if (in_array($v['category'], $this->exclude_cat_vendor)) {
                            unset($cnf[$k]);
                        }
                    }

                    if (isset($cnf[$k]) && isset($v['vendor']) && !empty($v['vendor']) && is_array($v['vendor'])) {
                        if (!in_array($this->vendor, $v['vendor'])) {
                            unset($cnf[$k]);
                        }
                    }
                }
            }

            return $cnf;
        }

        public function cleanJsStr($value)
        {
            return $value;
            //return trim(str_replace(array("\r", "\n", "\t", '\"'), array('', '', '', '&#34;'), $value));
        }

        public function jsVal($value, $chk = true, $clean = false, $txt = false)
        {
            if ($chk === false) {
                return $value;
            }

            // $value is / could be saved as string
            if (!is_bool($value) && !is_null($value)
                && ($value == 'false' || $value == 'true' || $value == 'null' || is_numeric($value)
                    || substr(trim($value), 0, 1) == '{' || substr(trim($value), 0, 1) == '[')
                ) {
                    return $value;
            } elseif (is_bool($value) && $value) {
                return 'true';
            } elseif (is_bool($value) && !$value) {
                return 'false';
            } elseif (is_null($value) || $value == 'NULL') {
                return 'null';
            } else {
                if ($clean) {
                    return '"' . $this->cleanJsStr($value) . '"';
                } elseif ($txt) {
                    return $value;
                } else {
                    return '"' . $value . '"';
                }
            }
        }

        public function jsKeyVal($key, $value, $chk = true)
        {
            return '"'.$key.'": '.$this->jsVal($value, $chk);
        }

        public function jsObjFromArr($array, $chk)
        {
            $ret = '{';
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    $ret .= '"'.$k.'": '.$this->jsObjFromArr($v, $chk).', ';
                } else {
                    $ret .= $this->jsKeyVal($k, $v, $chk) . ', ';
                }
            }
            $ret = trim($ret, ', ');
            $ret .= '}';
            return $ret;
        }

        /* generate init parameters based on passed cfg options */
        public function getInitJs($c = array())
        {
            $cd = array(
                'cfg' => array(),
                'holder_object' => 'jQuery.axZm_psh',
                'exclude_opt' => array(),
                'exclude_cat' => array(),
                'defaults' => false,
                'differ' => false,
                'ovrprefix' => false,
                'min' => false
            );

            foreach ($cd as $k => $v) {
                if (isset($c[$k])) {
                    ${$k} = $c[$k];
                } else {
                    ${$k} = $v;
                }
            }

            if (!is_string($ovrprefix)) {
                $ovrprefix = false;
            }

            $mouseover_param = array();
            $param = array();
            $return = '';
//echo '<table border="1">';
            foreach ($this->getConfig(true) as $k => $v) {
//echo '<tr>';
                $cat = $v['category'];

                if ($ovrprefix !== false) {
                    $save_var = $ovrprefix.'_'.strtoupper($k);
                } else {
                    $save_var = $v['prefix'].'_'.strtoupper($k);
                }

                if ($cat == 'plugin_settings'
                    || $cat == 'license'
                    || (!$defaults && !(isset($cfg[$k])
                    || isset($cfg[$save_var])))
                    || in_array($k, $exclude_opt)
                    || in_array($cat, $exclude_cat)
                ) {
                    continue;
                }

                $value = $defaults ? $v['default'] : isset($cfg[$k]) ? $cfg[$k] : $cfg[$save_var];

                if (!$defaults && $differ && $k != 'divID' && $k != 'galleryDivID') {
//                    print '<td>' . $k . '</td>';
//                    print '<td>' . $v['default'] . '</td>';
//                    print '<td>' . $value . '</td>';

                    if ($this->jsVal($v['default'], true, true) == $this->jsVal($value, true, true)) {
                        continue;
                    }
                }

                if ($cat == 'mouseover') {
                    $mouseover_param[$k] = $this->jsKeyVal($k, $value);
                } else {
                    $param[$k] = $this->jsKeyVal($k, $value);
                }
//echo '</tr>';                
            }
//echo '</table>';            

            if (!$defaults) {
                $param['lang'] = $this->jsKeyVal('lang', $holder_object.'.shopLang.substr(0, 2)', false);
                $param['axZmPath'] = $this->jsKeyVal('axZmPath', '('.$holder_object.'.axZmPath || \'../axZm/\')', false);

                $param['images'] = $this->jsKeyVal('images', $holder_object.'.IMAGES_JSON', false);
                $param['images360'] = $this->jsKeyVal('images360', $holder_object.'.IMAGES_360_JSON', false);
                $param['videos'] = $this->jsKeyVal('videos', $holder_object.'.VIDEOS_JSON', false);
            }

            if (!empty($mouseover_param)) {
                $param['mouseOverZoomParam'] = '"mouseOverZoomParam": {'.implode(', ', $mouseover_param).'}';
            }

            //$param['mouseOverZoomParam'] = '"position":"right"';
            //$param['main'] = '"galleryPosition":"right"';

//            print_r($param);
//            exit;
            

            $return = '{'.implode(', ', $param).'}';

            if ($min) {
                return $this->minifyJs($return);
            }

            // Could be valid JSON, must not
            return $return;
        }

        public function minifyJs($return)
        {
            $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';
            $return = preg_replace($pattern, '', $return);
            $return = trim(str_replace(array("\r", "\n", "\t"), array(' ', ' ', ''), $return));
            $return = preg_replace('/( )+/', ' ', $return);
            return $return;
        }

        public function getConfigFrontJs($c = array())
        {
            $cd = array(
                'window' => 'window.',
                'holder_object' => 'jQuery.axZm_psh',
                'exclude_opt' => array(),
                'ovrprefix' => false,
                'exclude_cat' => array()
            );

            foreach ($cd as $k => $v) {
                if (isset($c[$k])) {
                    ${$k} = $c[$k];
                } else {
                    ${$k} = $v;
                }
            }

            if (!is_string($ovrprefix)) {
                $ovrprefix = false;
            }

            $js = array();
            $js['mouseOverZoomParam'] = array();

            foreach ($this->config as $k => $v) {
                $cat = $v['category'];

                if ($cat == 'plugin_settings'
                    || $cat == 'license'
                    || in_array($k, $exclude_opt)
                    || in_array($cat, $exclude_cat)) {
                    continue;
                }

                if ($cat == 'mouseover') {
                    if ($ovrprefix !== false) {
                        $js['mouseOverZoomParam'][$k] = $ovrprefix.'_'.strtoupper($k);
                    } else {
                        $js['mouseOverZoomParam'][$k] = $window.$v['prefix'].'_'.strtoupper($k);
                    }
                } else {
                    if ($ovrprefix !== false) {
                        $js[$k] = $ovrprefix.'_'.strtoupper($k);
                    } else {
                        $js[$k] = $window.$v['prefix'].'_'.strtoupper($k);
                    }
                }
            }

            $js['lang'] = $holder_object.'.shopLang.substr(0, 2)';
            $js['axZmPath'] = '('.$holder_object.'.axZmPath || \'../axZm/\')';

            $js['images'] = $holder_object.'.IMAGES_JSON';
            $js['images360'] = $holder_object.'.IMAGES_360_JSON';
            $js['videos'] = $holder_object.'.VIDEOS_JSON';

            return $this->jsObjFromArr($js, false);
        }

        public function docuLine($l, $k, $v)
        {
            $ret = '';
            $example = array('EN' => 'Example', 'DE' => 'Beispiel');
            $longdefault = strlen($v['default']) > 30 ? true : false;

            $ret .= '<tr data-az_opt_name="'.$k.'" data-az_opt_cat="'.$v['category'].'"><td>'.$k.'</td><td>';

            if ($longdefault) {
                $ret .= '{...}';
            } else {
                if ($v['default'] === true || $v['default'] === false || $v['default'] === null || $v['default'] === 0) {
                    $ret .= strtolower(var_export($v['default'], true));
                } else {
                    $ret .= $v['default'] ?  $v['default'] : '""';
                }
            }

            $ret .= '</td><td>';
            $ret .= '<div class="optionsType">'.$v['type'].'</div>';
            $ret .= (isset($v['comment'][$l]) && !empty($v['comment'][$l]) ? $v['comment'][$l] : $v['comment']['EN']);

            if ($longdefault) {
                $ret .= '<div class="optionsDefaultHeading">Default:</div>';
                $ret .= '<pre class="language-js"><code class="language-js">'.$v['default'].'</code></pre>';
            } elseif (isset($v['example'])) {
                $ret .= '<div class="optionsDefaultHeading">'.(isset($example[$l]) ?
                    $example[$l] : $example['EN']).':</div>';
                $ret .= '<pre class="language-js"><code class="language-js">'.$v['example'].'</code></pre>';
            }

            $ret .= '</td></tr>';

            return $ret;
        }

        public function docuCat($l, $key)
        {
            return '<tbody data-az_options_head="'.$key.'"><tr><th colspan="3" class="optionsTabCat">'
            .(isset($this->categories[$key]['title'][$l])
            ? $this->categories[$key]['title'][$l]
            : $this->categories[$key]['title']['EN'])
            . '</th></tr></tbody>';
        }

        public function getDocu($c = array())
        {
            $mouse_over_zoom_param = array(
                'EN' => 'All specific options for the mouseover zoom 
                    are keys of mouseOverZoomParam object!',
                'DE' => 'Alle spezifische optionen für mousever zoom 
                    sind Schlüßel des mouseOverZoomParam Objektes!'
            );

            $cd = array(
                'l' => 'EN',
                'cls' => 'optionsTable',
                'skip_cat' => array(),
                'skip_options' => array(),
                'contents' => true,
                'plugin_settings' => true
            );

            foreach ($cd as $k => $v) {
                if (isset($c[$k])) {
                    ${$k} = $c[$k];
                } else {
                    ${$k} = $v;
                }
            }

            $this->defineItemes();
            $cat = '';
            $cat_prev = '';

            $ret = '<div id="'.$cls.'Select" style="margin-bottom: 3px; text-align: right;"></div>';
            $t = '<table class="'.$cls.'">';
            $h = '<tr><th>Option</th><th>Default</th><th>Description</th></tr>';

            $ret .= $t;
            $arr = array_merge($this->items, $this->config);

            foreach ($arr as $k => $v) {
                $cat = $v['category'];

                if (in_array($cat, $skip_cat) || in_array($k, $skip_options)) {
                    continue;
                }

                if ($cat != $cat_prev) {
                    if (substr($ret, -8) != '</tbody>' && strstr($ret, '<tbody>')) {
                        $ret .= '</tbody>';
                    }

                    $ret .= $this->docuCat($l, $cat);
                    $ret .= '<tbody data-az_options_cat="'.$cat.'">';

                    if ($cat == 'mouseover') {
                        $ret .= '<tr><td>mouseOverZoomParam</td><td>{... ... ...}<td>'
                            .(isset($mouse_over_zoom_param[$l]) ?
                            $mouse_over_zoom_param[$l] : $mouse_over_zoom_param['EN']).'</td>';
                    }

                    $ret .= $h;
                    $cat_prev = $cat;
                }

                $ret .= $this->docuLine($l, $k, $v);
            }

            if (substr($ret, -8) != '</tbody>') {
                $ret .= '</tbody>';
            }

            $ret .= '</table>';
            return $ret;
        }

        public function docuJS($c = array())
        {
            $cd = array(
                'cls' => 'optionsTable',
                'l' => 'EN',
                'tag' => false
            );

            foreach ($cd as $k => $v) {
                if (isset($c[$k])) {
                    ${$k} = $c[$k];
                } else {
                    ${$k} = $v;
                }
            }

            $js = '';

            if ($tag) {
                $js .= '<script type="text/javascript">'."\n";
            }

            $js .= <<<EOT
jQuery(function () {
    var optionsTable = '$cls';
    var chooseToFind = {'EN': 'Options (Choose to find)', 'DE': 'Optionen (auswählen um zu finden)'};
    var l = '$l';

    var scrollToAnchor = function(aid, adj, ttt, clb) {
        var aTag;
        adj = adj || -30;
        ttt = ttt || 'slow';
        clb = clb || function(){};

        if (aid instanceof jQuery) {
            aTag = aid;
        } else if (aid && (aid.indexOf('.') != -1 || aid.indexOf('#') != -1)) {
            aTag = $(aid);
        } else {
            aTag = $('a[name="'+ aid +'"]');
        }

        if (aTag.length == 0){
            return;
        }

        var offs = aTag.offset();
        var naviH = jQuery('#az_topNaviAjaxZoom').height() || 0;
        var scrollT = offs.top - naviH + (adj || 0);
        if (offs && offs.top) {
            jQuery('html, body').animate( {
                scrollTop: scrollT
            }, {
                duration: ttt,
                queue: false,
                complete: clb
            } );
        }
    };

    $('.' + optionsTable + ' code').each(function() {
        var _this = $(this);
        var c = $(this).html();
        try {
           var aaa = JSON.parse(c);
           var bbb = JSON.stringify(aaa, null, "\t");
           _this.html(bbb);
        }
        catch (e) {

        }
    } );

    jQuery('.' + optionsTable + ' code').each(function() {
        var _this = jQuery(this);
        var c = jQuery(this).html();

        try {
           var aaa = JSON.parse(c);
           var bbb = JSON.stringify(aaa, null, "\t");
           _this.html(bbb);
        }
        catch (e) {

        }
    } );

     jQuery('.' + optionsTable + ':not(.methods) td:nth-child(2)').each(function() {
         var txt = jQuery.trim(jQuery(this).html());
         if (txt == 'false' || txt == 'true'){
             jQuery(this).html('<span style="color: green">'+txt+'</span>');
        } else if (txt == 'null' || txt == 'NULL') {
            jQuery(this).html('<span style="color: blue">null</span>');
        } else if (txt.charAt(0) == '{' || txt.charAt(0) == '[') {
            jQuery(this).html('<span style="color: #5F6364">'+txt+'</span>');
        } else if (parseFloat(txt) == txt) { 
            jQuery(this).html('<span style="color: red">'+txt+'</span>');
        } else if (txt.charAt(0) != '"'){
            jQuery(this).html('<span>"'+txt+'"</span>');
        }
    } );

    jQuery('.' + optionsTable + ' tbody[data-az_options_head]').each(function() {
        var a = '<i class="glyphicon glyphicon-triangle-right"></i>';
        jQuery('th', jQuery(this)).prepend(a);
    } );

    jQuery('.' + optionsTable + ' tbody[data-az_options_head]').on('click', function(e) { 
        var _this = jQuery(this);
        var val = jQuery(this).attr('data-az_options_head');
        if (jQuery('.' + optionsTable + ' tbody[data-az_options_cat="'+val+'"]').css('display') == 'none') {
            jQuery('i', _this).removeClass('glyphicon-triangle-right');
            jQuery('i', _this).addClass('glyphicon-triangle-bottom');
        } else {
            jQuery('i', _this).addClass('glyphicon-triangle-right');
            jQuery('i', _this).removeClass('glyphicon-triangle-bottom');
        }
        jQuery('.' + optionsTable + ' tbody[data-az_options_cat="'+val+'"]').toggle();
        _this.toggleClass('active');
    } ).addClass('active').trigger('click');

    var opt = [];
    jQuery('.' + optionsTable + ' tr[data-az_opt_name]').each(function() {
        opt.push(jQuery(this).attr('data-az_opt_name'));
    } );
    opt.sort();

    var sel = '<select><option value="">'+(chooseToFind[l] || chooseToFind['EN'])+'</option>';
    jQuery(opt).each(function(k, v) {
        sel += '<option value="'+v+'">'+v+'</option>';
    } );
    sel += '</select>';

    sel = jQuery(sel).on('change', function() {
        var _this = jQuery(this).blur();
        var val = _this.blur().val();
        if (!val) {
            jQuery('.' + optionsTable + ' tbody[data-az_options_head]').each(function() {
                var _this = jQuery(this);
                if (_this.next().css('display') != 'none') {
                    _this.trigger('click');
                }
            } );
            return;
        }
        var tr = jQuery('.' + optionsTable + ' tr[data-az_opt_name="'+val+'"]');
        if (tr.closest('tbody').css('display') == 'none') {
            var cat = tr.attr('data-az_opt_cat');
            jQuery('.' + optionsTable + ' tbody[data-az_options_head="'+cat+'"]').trigger('click');
        }
        tr.addClass('az_highlight_tr');
        scrollToAnchor(tr, -120, null, function() {
            setTimeout(function(){
                tr.removeClass('az_highlight_tr');
            }, 2000);
        } );
    } ).appendTo('#'+optionsTable+'Select');
} );
EOT;
            if ($tag) {
                $js .= '</script>'."\n";
            }
            return $js;
        }

        public function docuCss($c = array())
        {
            $cd = array(
                'cls' => 'optionsTable',
                'l' => 'EN',
                'tag' => false
            );

            foreach ($cd as $k => $v) {
                if (isset($c[$k])) {
                    ${$k} = $c[$k];
                } else {
                    ${$k} = $v;
                }
            }

            $description = array(
                'EN' => 'Description',
                'DE' => 'Beschreibung'
            );

            $css = '';

            if ($tag) {
                $css .= '<style type="text/css">'."\n";
            }

            $css .= '
    .optionsTable, .optionsTable * {box-sizing: border-box;}
    .optionsTable {width: 100%; border-spacing: 0; font-size: 10pt; 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;}
    .optionsTable tbody[data-az_options_head].active th{background-color: #337AB7; 
        border-color: #337AB7 !important; color: #FFF;}
    .optionsTable tbody[data-az_options_head] th i {margin-right: 10px; top: 0px; font-size: 10pt; color: #9E9E9E;}
    .optionsTable tbody[data-az_options_head].active th i{color: #FFF;}
    .optionsTable td {transition: background-color .15s ease;}
    .optionsTable th {white-space: nowrap;}
    .optionsTable td, .optionsTable th {padding-left: 5px; padding-right: 5px; vertical-align: top; line-height: 1.2em; 
        text-align: left; padding-top: 3px; padding-bottom: 8px; border-bottom: 1px solid #DDD;}
    .optionsTable th {background-color: #EEE; padding-top: 10px; 
        padding-bottom: 10px; font-size: 10pt; font-weight: bold;}
    .optionsTable th.optionsTabCat {cursor: pointer; background-color: #EEE; padding-top: 10px; padding-bottom: 10px; 
        font-weight: normal; font-size:14pt; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;}

    .optionsTable ul, .optionsTable ol {padding-left: 14px; margin-top: 0;}
    .optionsTable li {margin-bottom: 5px; padding-left: 14px;}
    .optionsTable tbody tr:nth-child(odd) { background-color: #F9F9F9;}
    .optionsTable tbody tr:nth-child(even) { background-color: #FFF;}
    .optionsTable .removed {text-decoration: line-through;}
    .optionsTable .optionsType {text-align: right; font-size: 8pt; font-weight: bold; position: relative; color: #777; 
    top: -3px; right: -2px; float: right; margin-left: 20px; margin-bottom: 11pt;min-width: 100px;}

    .optionsTable tr td:last-child{border-right: #DDD 1px solid;}
    .optionsTable tr th:last-child{border-right: #DDD 1px solid;}
    .optionsTable tr td:first-child{border-left: #DDD 1px solid;}
    .optionsTable tr th:first-child{border-left: #DDD 1px solid;}
    .optionsTable tbody:first-child tr:first-child th{border-top: #DDD 1px solid;}

    .optionsTable .az_highlight_tr, .optionsTable .az_highlight_tr td {background-color: yellow; 
    transition: background-color .15s ease;}
    .optionsTable .optionsDefaultHeading {font-weight: bold; margin-top: 20px;}
    .optionsTable pre {border-radius: 0; border-top-width: 0; border-right-width: 0; border-bottom-width: 0; 
    box-shadow: none; margin-top: 0; white-space: pre-wrap;}
    .optionsTable code{border-top: #DDD 1px solid; border-right: #DDD 1px solid; border-bottom: #DDD 1px solid; 
    white-space: pre-wrap; padding-left: 2px; padding-right: 2px; tab-size: 2;}

    @media only screen and (max-width: 1024px){
        .optionsTable tbody tr:nth-child(odd) {background-color: #FFF;}
        .optionsTable,.optionsTable>tbody,.optionsTable>tbody>tr>th,
            .optionsTable>tbody>tr>td,.optionsTable>tbody>tr {display: block;}
        .optionsTable>tbody>tr>th:not(.optionsTabCat) {display: none;}
        .optionsTable>tbody>tr>td {position: relative; padding-left: 100px;}
        .optionsTable>tbody>tr>td:nth-of-type(1), .optionsTable>tbody>tr>td:nth-of-type(2), 
        .optionsTable>tbody>tr>td:nth-of-type(3) {border-bottom: 1px solid #DDD;}
        .optionsTable tr[data-az_opt_name]>td:nth-of-type(1){padding-bottom: 5px; padding-top: 5px;}
        .optionsTable tr[data-az_opt_name]>td:nth-of-type(2){border-bottom: none; 
            border-left: #DDD 1px solid; border-right: #DDD 1px solid;}
        .optionsTable>tbody>tr.subOpt>td{background-color: #D4D4D4; color: #000;}
        .optionsTable>tbody>tr>td[colspan="3"] {background-color: #FFF !important;}
        .optionsTable>tbody>tr>td:before {position: absolute; top: 3px; left: 3px;width: 90px; white-space: nowrap;}
        .optionsTable tr:nth-child(2n+1) {background-color: #FFF;}
        .optionsTable>tbody>tr>td:nth-of-type(1) {background-color: #EEE; font-size:14pt; 
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; 
            border-left: #DDD 1px solid; border-right: #DDD 1px solid;}
        .optionsTable>tbody>tr>td:nth-of-type(3) {overflow-x: hidden; border-left: #DDD 1px solid;}
        .optionsTable>tbody>tr>td:nth-of-type(1):before { content: "";}
        .optionsTable>tbody>tr>td:nth-of-type(2):before { content: "Default:";}
        .optionsTable>tbody>tr>td:nth-of-type(3):before { 
            content: "'.(isset($description[$l]) ? $description[$l] : $description['EN']).':";}
    }
            ';

            if ($tag) {
                $css .= '</style>'."\n";
            }

            if ($cls != 'optionsTable') {
                $css = str_replace('.optionsTable', '.'.$cls, $css);
            }

            return $css;
        }

        public function arrayToXml($data, $xml_data)
        {
            foreach ($data as $key => $value) {
                if (is_numeric($key)) {
                    $key = 'item'.$key;
                }
                if (is_array($value)) {
                    $subnode = $xml_data->addChild($key);
                    if (isset($value['attributes']) && is_array($value['attributes'])) {
                        foreach ($value['attributes'] as $k => $v) {
                            $subnode->addAttribute($k, $v);
                        }
                        unset($value['attributes']);
                    }
                    $this->arrayToXml($value, $subnode);
                } else {
                    $xml_data->addChild($key, htmlspecialchars($value));
                }
            }
            return $xml_data;
        }

        public function magento_xml_config()
        {
            $data = array();
            foreach ($this->categories as $k => $v) {
                if (!empty($this->exclude_cat_vendor) && in_array($k, $this->exclude_cat_vendor)) {
                    continue;
                }

                $data[$k] = array();
            }

            $cnf = $this->getConfig();

            foreach ($cnf as $k => $v) {
                $cat = $v['category'];

                if (!isset($data[$cat]) || in_array($k, $this->exclude_opt_vendor)) {
                    continue;
                }
                $data[$cat][$k] = $this->jsVal($v['default'], true, false, true);
            }

            $xml_data = new SimpleXMLElement('<?xml version="1.0"?><axzoom_options></axzoom_options>');
            $xml_data = $this->arrayToXml($data, $xml_data);
            return $xml_data->asXML();
        }

        public function magento_xml_system()
        {
            $l = 'EN';
            $data = array();

            $data['general'] = array(
                'attributes' => array(
                    'translate' => 'label'
                ),
                'label' => 'About ',
                'frontend_type' => 'text',
                'sort_order' => 10,
                'show_in_default' => 1,
                'show_in_website' => 0,
                'show_in_store' => 0,
                'comment' => '
                    <![CDATA[
                        <p>AJAX-ZOOM is a multipurpose library for displaying (high resolution) images and 360°/3D spins. <br>
                            This Magento extension integrates only one particular implementation (example) from AJAX-ZOOM library into Magento. <br>
                            The independent example can be found here: 
                            <a target="_blank" href="http://www.ajax-zoom.com/examples/example32.php">
                                http://www.ajax-zoom.com/examples/example32.php 
                            </a><br>
                            There you will also find some subtle details about the options which you can configure below. <br>
                            The options below mainly refer to this one implementation / example. <br><br>
                            <b>However</b> AJAX-ZOOM has many other options which can be set manually in 
                            <code>/js/axzoom/axZm/zoomConfigCustom.inc.php</code> 
                            after <br> <code>elseif (&#36;_GET[\'example\'] == \'mouseOverExtension360Ver5\')</code>. 
                            You can also override any PHP options contained in 
                            <code>/js/axzoom/axZm/zoomConfig.inc.php</code> and 
                            <code>/js/axzoom/axZm/zoomConfigCustom.inc.php</code> by editing 
                            <code>/js/axzoom/zoomConfigCustomAZ.inc.php</code><br>
                        </p>
                        If you do not want to edit PHP files - most of the options can be also set in these  
                        formfields as JS plain object:
                        <ol style="margin-left: 25px; list-style: decimal">
                            <li><a href="javascript:void(0)" onclick="Fieldset.az_open(\'axzoom_options_general_settings\', \'axzoom_options_general_settings_azOptions\')">azOptions</a> - for 2D zoom</li>
                            <li><a href="javascript:void(0)" onclick="Fieldset.az_open(\'axzoom_options_general_settings\', \'axzoom_options_general_settings_azOptions360\')">azOptions360</a> - for 360/3D zoom</li>
                        </ol><br>
                        Depending on the template used you might need to adjust especially these two options:
                        <ol style="margin-left: 25px; list-style: decimal">
                            <li><a href="javascript:void(0)" onclick="Fieldset.az_open(\'axzoom_options_mouseover\', \'axzoom_options_mouseover_zoomWidth\')">zoomWidth</a> - width of the mouseover flyout window</li>
                            <li><a href="javascript:void(0)" onclick="Fieldset.az_open(\'axzoom_options_mouseover\', \'axzoom_options_mouseover_zoomHeight\')">zoomHeight</a> - height of the mouseover flyout window</li>
                        </ol><br>
                        <p>If you will be not able to adjust these options on your own please 
                            <a target="_blank" href="http://www.ajax-zoom.com/index.php?cid=contact">ask for support</a>
                        </p>
                        <p>Other useful / most common options are marked <span class="importantAZcolor">green</span> with this symbol: 
                            <span class="notice-msg importantAZoption"></span>
                        </p>
                    ]]>
                '
            );
            $data['actions'] = array(
                'attributes' => array(
                    'translate' => 'label'
                ),
                'label' => 'Actions',
                'frontend_type' => 'text',
                'sort_order' => 20,
                'show_in_default' => 1,
                'show_in_website' => 1,
                'show_in_store' => 1,
                'fields' => array(
                    'resetbutton' => array(
                        'label' => 'Reset all options to default values',
                        'comment' => '<![CDATA[
                            No data will be replaced or removed! 
                            It will only create new tables in case they are not present.
                            Should be performed after AJAX-ZOOM module update 
                            in case new tables are not created by the update script instantly.
                        ]]>',
                        'frontend_model' => 'zoom/adminhtml_system_config_form_resetbutton',
                        'sort_order' => 1,
                        'show_in_default' => 1,
                        'show_in_website' => 1,
                        'show_in_store' => 1
                    ),
                    'updatedatabase' => array(
                        'label' => 'Update database tables',
                        'comment' => '<![CDATA[
                            Reset all options except licenses to their default values
                        ]]>',
                        'frontend_model' => 'zoom/adminhtml_system_config_form_updatedatabase',
                        'sort_order' => 2,
                        'show_in_default' => 1,
                        'show_in_website' => 1,
                        'show_in_store' => 1
                    ),
                    'numberimages' => array(
                        'label' => 'Get number images',
                        'comment' => '<![CDATA[
                            Request total number of images
                        ]]>',
                        'frontend_model' => 'zoom/adminhtml_system_config_form_numberimages',
                        'sort_order' => 3,
                        'show_in_default' => 1,
                        'show_in_website' => 1,
                        'show_in_store' => 1
                    ),
                    'batchtool' => array(
                        'label' => 'Batch tool',
                        'comment' => '<![CDATA[
                            You do not necessarily need to use the AJAX-ZOOM batch tool, 
                            because if image tiles and other AJAX-ZOOM caches have not been generated yet, 
                            AJAX-ZOOM will process the images on-the-fly. 
                            Latest, when they appear at the frontend. 
                            However, if you have thousands of images, it is a good idea to batch process all existing images, 
                            which you plan to show over AJAX-ZOOM, 
                            before launching the new website or before enabling AJAX-ZOOM at frontend.
                        ]]>',
                        'frontend_model' => 'zoom/adminhtml_system_config_form_batchtool',
                        'sort_order' => 4,
                        'show_in_default' => 1,
                        'show_in_website' => 1,
                        'show_in_store' => 1
                    ),
                    'updateaz' => array(
                        'label' => 'Update AJAX-ZOOM',
                        'comment' => '<![CDATA[
                            Check if new AJAX-ZOOM (core files) version is available. 
                            It is located in /js/axzoom/axZm folder.
                        ]]>',
                        'frontend_model' => 'zoom/adminhtml_system_config_form_updateaz',
                        'sort_order' => 5,
                        'show_in_default' => 1,
                        'show_in_website' => 1,
                        'show_in_store' => 1
                    )
                )
            );
            $data['license'] = array(
                'attributes' => array(
                    'translate' => 'label'
                ),
                'label' => 'AJAX-ZOOM license(s)',
                'frontend_type' => 'text',
                'sort_order' => 30,
                'show_in_default' => 1,
                'show_in_website' => 1,
                'show_in_store' => 1,
                'fields' => array(
                    'lic' => array(
                        'label' => 'License',
                        'comment' => '<![CDATA[
                            <a href="http://www.ajax-zoom.com/index.php?cid=contact" target="_blank">
                                Ask for support</a>&nbsp;&nbsp;&nbsp;
                            <a href="http://www.ajax-zoom.com/index.php?cid=download#heading_3" target="_blank">
                                Buy a license</a>
                            <div style="margin-top: 10px; display: inline-block; font-size: 11px">
                                Please note: starting from AJAX-ZOOM v. 5.3.0 (core files) 
                                you can set AJAX-ZOOM to load the originally uploaded files 
                                instead of image tiles at frontend. 
                                This is an AJAX-ZOOM option - $zoom[\'config\'][\'simpleMode\'] 
                                and needs to be set in /js/axzoom/zoomConfigCustomAZ.inc.php file. 
                                If you are using "simple" license, this option is enabled instantly!!! 
                                Whether this option is enabled instantly or you have activated it intentionally, 
                                in order to this option to work properly, 
                                please open /js/pic/360/.htaccess file 
                                and remove "deny from all" line from this file. 
                                Do not delete the .htaccess file completely, 
                                as the file will be recreated on updates if it does not exist. 
                            </div>
                        ]]>',
                        'frontend_model' => 'zoom/adminhtml_system_config_fieldset_license',
                        'backend_model' => 'adminhtml/system_config_backend_serialized_array',
                        'sort_order' => 1,
                        'show_in_default' => 1,
                        'show_in_website' => 1,
                        'show_in_store' => 1
                    )
                )
            );

            $n = 0;
            $cat_sort_start = 40;
            foreach ($this->categories as $k => $v) {
                if (!empty($this->exclude_cat_vendor) && in_array($k, $this->exclude_cat_vendor)) {
                    continue;
                }
                $data[$k] = array();
                $data[$k]['attributes'] = array(
                    'translate' => 'label'
                );
                $data[$k]['label'] = $v['title'][$l];
                $data[$k]['frontend_type'] = 'text';
                $data[$k]['sort_order'] = $cat_sort_start + 10 * $n;
                $data[$k]['show_in_default'] = 1;
                $data[$k]['show_in_website'] = 1;
                $data[$k]['show_in_store'] = 1;
                $data[$k]['fields'] = array();
                $n++;
            }

            $n = 0;
            $cat_sort_start = 10;
            $prev_cat = '';
            $cnf = $this->getConfig();
            foreach ($cnf as $k => $v) {
                $cat = $v['category'];

                if (!isset($data[$cat]) || in_array($k, $this->exclude_opt_vendor)) {
                    continue;
                }

                if ($prev_cat != $cat) {
                    $n = 0;
                }

                $arr = array();
                if (isset($v['important']) && $v['important'] === true) {
                    $arr['label'] = '<![CDATA[<div class="notice-msg importantAZoption">'.$k.'</div>]]>';
                } else {
                    $arr['label'] = $k;
                }

                $v['comment'][$l] = trim($v['comment'][$l]);

                if (isset($v['isJsObject']) && $v['isJsObject'] === true) {
                    $arr['comment'] = '<![CDATA[
                    <div class="validation-advice">Attention: you are editing JavaScript object! 
                        Errors will lead to AJAX-ZOOM not working properly.</div>
                    '.$v['comment'][$l].'
                    ]]>';
                } elseif (isset($v['isJsArray']) && $v['isJsArray'] === true) {
                    $arr['comment'] = '<![CDATA[
                    <div class="validation-advice">Attention: you are editing JavaScript array! 
                        Errors will lead to AJAX-ZOOM not working properly.</div>
                    '.$v['comment'][$l].'
                    ]]>';
                } else {
                    $arr['comment'] = '<![CDATA['.$v['comment'][$l].']]>';
                }

                if ($v['display'] == 'switch') {
                    $arr['frontend_type'] = 'select';
                    $arr['source_model'] = 'axzoom/azsettings::yesno';
                } elseif ($v['display'] == 'select') {
                    $arr['frontend_type'] = 'select';
                    $arr['source_model'] = 'axzoom/azsettings::'.$k;
                } elseif ($v['display'] == 'textarea') {
                    $arr['frontend_type'] = 'textarea';
                } elseif ($v['display'] == 'text') {
                    $arr['frontend_type'] = 'text';
                }

                $arr['sort_order'] = $cat_sort_start + 10 * $n;
                $arr['show_in_default'] = 1;
                $arr['show_in_website'] = 1;
                $arr['show_in_store'] = 1;
                $data[$cat]['fields'][$k] = $arr;
                $prev_cat = $cat;
                $n++;
            }

            $xml_data = new SimpleXMLElement('<?xml version="1.0"?><groups></groups>');
            $xml_data = $this->arrayToXml($data, $xml_data);
            return $xml_data->asXML();
        }



        public function magento2_config()
        {
            $l = 'EN';
            $data = array();


            $n = 0;
            $cat_sort_start = 40;
            foreach ($this->categories as $k => $v) {
                if (!empty($this->exclude_cat_vendor) && in_array($k, $this->exclude_cat_vendor)) {
                    continue;
                }
                $data[$k] = array();
                //$data[$k]['attributes'] = array(
                //    'translate' => 'label'
                //);
                $data[$k]['id'] = $k;
                $data[$k]['label'] = $v['title'][$l];
                //$data[$k]['frontend_type'] = 'text';
                $data[$k]['sortOrder'] = $cat_sort_start + 10 * $n;
                $data[$k]['showInDefault'] = 1;
                $data[$k]['showInWebsite'] = 1;
                $data[$k]['showInStore'] = 1;
                $data[$k]['children'] = array();
                $n++;
            }

            $n = 0;
            $cat_sort_start = 10;
            $prev_cat = '';
            $cnf = $this->getConfig();
            foreach ($cnf as $k => $v) {
                $cat = $v['category'];

                if (!isset($data[$cat]) || in_array($k, $this->exclude_opt_vendor)) {
                    continue;
                }

                if ($prev_cat != $cat) {
                    $n = 0;
                }

                $arr = array();
                $arr['id'] = $k;

                if (isset($v['important']) && $v['important'] === true) {
                    $arr['label'] = '<div class="notice-msg importantAZoption">'.$k.'</div>';
                } else {
                    $arr['label'] = $k;
                }

                $v['comment'][$l] = trim($v['comment'][$l]);

                if (isset($v['isJsObject']) && $v['isJsObject'] === true) {
                    $arr['comment'] = '
                    <div class="validation-advice">Attention: you are editing JavaScript object! 
                        Errors will lead to AJAX-ZOOM not working properly.</div>
                    '.$v['comment'][$l].'
                    ';
                } elseif (isset($v['isJsArray']) && $v['isJsArray'] === true) {
                    $arr['comment'] = '
                    <div class="validation-advice">Attention: you are editing JavaScript array! 
                        Errors will lead to AJAX-ZOOM not working properly.</div>
                    '.$v['comment'][$l].'
                    ';
                } else {
                    $arr['comment'] = ''.$v['comment'][$l].'';
                }

                if ($v['display'] == 'switch') {
                    $arr['type'] = 'select';
                    $arr['source_model'] = 'Ax\Zoom\Model\Config\Source\Truefalse';
                } elseif ($v['display'] == 'select') {
                    $arr['type'] = 'select';
                    $arr['options'] = ['option' => $this->magento2_options($v['options'])];
                } elseif ($v['display'] == 'textarea') {
                    $arr['type'] = 'textarea';
                } elseif ($v['display'] == 'text') {
                    $arr['type'] = 'text';
                }

                $arr['sortOrder'] = $cat_sort_start + 10 * $n;
                $arr['showInDefault'] = 1;
                $arr['showInWebsite'] = 1;
                $arr['showInStore'] = 1;

                $arr['_elementType'] = 'field';
                $arr['path'] = 'axzoom_options/' . $v['category'];


                $data[$cat]['children'][$k] = $arr;
                $prev_cat = $cat;
                $n++;
            }

            return $data;
        }

        public function magento2_values()
        {
            $data = array();
            foreach ($this->categories as $k => $v) {
                if (!empty($this->exclude_cat_vendor) && in_array($k, $this->exclude_cat_vendor)) {
                    continue;
                }

                $data[$k] = array();
            }

            $cnf = $this->getConfig();

            foreach ($cnf as $k => $v) {
                $cat = $v['category'];

                if (!isset($data[$cat]) || in_array($k, $this->exclude_opt_vendor)) {
                    continue;
                }
                $data[$cat][$k] = $this->jsVal($v['default'], true, false, true);
            }

            return $data;
        }        

        public function magento2_options($options)
        {
            $result = [];
            foreach ($options as $option) {
                $result[] = ['label' => $option[1], 'value' => $option[0]];
            }

            return $result;
        }

    }
}
