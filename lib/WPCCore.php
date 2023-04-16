<?php
namespace lib;

/**
 * Description of WPCCore
 * Bundles all needed lib classes in the core. 
 * @author marconagel
 */
defined( 'ABSPATH' ) or die( 'Direct access not allowed!' );

/* Maximum length of JSON string containing menus. Default: 65535 chars */
define ('WPC_MAX_JSONSTR_LENGTH', 65535);

class WPCCore {
    //private vars
    protected $themeDir;
    protected $themeURI;
    protected $versionNum;
    
    //constructor
    public function __construct() {
        //include constants file
        
        //require libs
        require_once 'WPCBackend.php';
        require_once 'WPCUtilities.php';
        require_once 'WPCLandingPage.php';
        require_once 'WPCAPI.php';

        //setup vars
        $this->themeDir = get_template_directory();
        $this->themeURI = get_template_directory_uri();
        $this->versionNum = wp_get_theme()->get( 'Version' );
        
        //start up backend
        new \lib\WPCBackend();
        //start api routes
        new \lib\WPCAPI();
        
        //load textdomain
        add_action( 'after_setup_theme', array( $this, 'wpc_language_setup' ) );
        //load frontend fonts
        add_action( 'wp_head', array( $this, 'wpc_fr_inline_css' ) );
        //load favicon stuff for frontend
        add_action( 'wp_head', array( $this, 'wpc_fr_icons' ) );
        //load frontend css & scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'wpc_fr_scripts' ) ); 
        //load backend css & scripts
        add_action( 'admin_enqueue_scripts', array( $this, 'wpc_be_scripts' ) );     
    }
    /**
     * wpc_language_setup()
     * --------------------
     * Load translations
     */
    public function wpc_language_setup() {
        load_theme_textdomain('tsu-wpconnect-theme', $this->themeDir . '/languages');
    }  
    /**
     * wpc_fr_inline_css()
     * --------------------
     * Load frontend webfonts
     */    
    public function wpc_fr_inline_css() {
        //get frontend style vars
        
        //fonts
        $headline_font = WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-font');
        $content_font = WPCUtilities::wpc_get_theme_option('wpc-fr-cnt-font');
        
        $lp_settings = [
            "bodybg-color" => WPCUtilities::wpc_get_theme_option('wpc-fr-bg-color'),
            "bodytxt-color" => WPCUtilities::wpc_get_theme_option('wpc-fr-txt-color'),
            "bodyhead-color" => WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-color'),
            "container-color" => WPCUtilities::wpc_get_theme_option('wpc-fr-cbg-color'),
            "buttonbg-color" => WPCUtilities::wpc_get_theme_option('wpc-fr-btn-color'),
            "buttontxt-color" => WPCUtilities::wpc_get_theme_option('wpc-fr-btt-color'),
            "headline-font" => !empty( $headline_font ) ? $headline_font : $this->themeURI . '/fonts/LeckerliOne.woff',
            "content-font" => !empty( $content_font ) ? $content_font : $this->themeURI . '/fonts/FengardoNeue.woff'
        ];
        ?>
            <style type="text/css">
                @font-face {
                    font-family: 'Content Text';
                    src: url('<?php echo $lp_settings["content-font"]; ?>') format('woff');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }

                @font-face {
                    font-family: 'Headline Text';
                    src: url('<?php echo $lp_settings["headline-font"]; ?>') format('woff');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }
                body {
                    background-color: <?php echo $lp_settings["bodybg-color"]; ?>;
                    color: <?php echo $lp_settings["bodytxt-color"]; ?>;
                }
                .container {
                    background-color: <?php echo $lp_settings["container-color"]; ?>;
                }   
                .h1 {
                    color: <?php echo $lp_settings["bodyhead-color"]; ?>;
                }
                .wpc-redirect-button {
                    color: <?php echo $lp_settings["buttontxt-color"]; ?>;
                    background-color: <?php echo $lp_settings["buttonbg-color"]; ?>;  
                    border-color: <?php echo $lp_settings["buttonbg-color"]; ?>;
                }
            </style>
        <?php
    }
    /**
     * wpc_fr_scripts()
     * ------------------
     * Load frontend style & script
     */
    public function wpc_fr_scripts() {
        //enque stuff
        wp_enqueue_style( 'wpc-base-style', $this->themeURI . '/style.css', array(), $this->versionNum );
        wp_enqueue_style( 'wpc-fronted-style', $this->themeURI . '/css/wpc-landing.css', array(), $this->versionNum  );       
    }  
    /**
     * wpc_be_scripts()
     * ------------------
     * 
     * Load admin style & script
     * @global type $pagenow
     */
    public function wpc_be_scripts() {    
        //look up where we are now
        global $pagenow;
        //enque script and style if needed on page
        if( $pagenow == 'admin.php' ){
            if ( $_GET['page'] == 'wpc_settings' ) {
                wp_enqueue_script('wpc-admin-default-js', $this->themeURI . '/js/wpc-adminjs.js', array( 'wp-color-picker' ), false, true);
                wp_enqueue_style( 'wpc-admin-style', $this->themeURI . '/css/wpc-admin.css', array(), $this->versionNum  ); 
            } 
            if ($_GET['page'] == 'wpc_settings') {   
                //load colorpicker
                wp_enqueue_script( 'wp-color-picker' );
                wp_enqueue_style( 'wp-color-picker' );
            }
        }     
    }
    /**
     * wpc_fr_icons()
     * ----------------
     * Adds a ton of different icons to your website
     */
    public function wpc_fr_icons() {
                    $favs = [
                        '16x16' => WPCUtilities::wpc_get_theme_option('wpc-fr-16x16'),
                        '32x32' => WPCUtilities::wpc_get_theme_option('wpc-fr-32x32'),
                        '96x96' => WPCUtilities::wpc_get_theme_option('wpc-fr-96x96')
                    ];
        if( !empty($favs['16x16']) ) :
        ?>  
            <link rel="icon" type="image/png" href="data:image/png;base64, <?php echo WPCUtilities::wpc_encodeimage_b64( $favs['16x16'] ); ?>" sizes="16x16">
        <?php endif; 
        if( !empty($favs['32x32']) ) :
        ?>
            <link rel="icon" type="image/png" href="data:image/png;base64, <?php echo WPCUtilities::wpc_encodeimage_b64( $favs['32x32'] ); ?>" sizes="32x32">
        <?php endif; 
        if( !empty($favs['96x96']) ) :
        ?>        
            <link rel="icon" type="image/png" href="data:image/png;base64, <?php echo WPCUtilities::wpc_encodeimage_b64( $favs['96x96'] ); ?>" sizes="96x96">        
        <?php endif;   
    }
    
}
