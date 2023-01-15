<?php
namespace lib;

/**
 * Description of WPCCore
 * Bundles all needed lib classes in the core. 
 * @author marconagel
 */
class WPCCore {
    //private vars
    protected $themeDir;
    protected $themeURI;
    protected $versionNum;
    
    //constructor
    public function __construct() {
        //require libs
        require_once 'WPCBackend.php';
        require_once 'WPCUtilities.php';
        require_once 'WPCLandingPage.php';

        //setup vars
        $this->themeDir = get_template_directory();
        $this->themeURI = get_template_directory_uri();
        $this->versionNum = wp_get_theme()->get( 'Version' );
        
        //start up backend
        new \lib\WPCBackend();
        
        //load textdomain
        add_action( 'after_setup_theme', array( $this, 'wpc_language_setup' ) );
        //load frontend fonts
        add_action( 'wp_head', array( $this, 'wpc_fr_inline_css' ) );
        //load frontend css & scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'wpc_fr_scripts' ) ); 
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
        ?>
            <style type="text/css">
                @font-face {
                    font-family: 'Fengardo Neue';
                    src: url('<?php echo $this->themeURI ?>/fonts/FengardoNeue.woff2') format('woff2'),
                        url('<?php echo $this->themeURI ?>/fonts/FengardoNeue.woff') format('woff');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }

                @font-face {
                    font-family: 'LeckerliOne';
                    src: url('<?php echo $this->themeURI ?>/fonts/LeckerliOne.woff2') format('woff2'),
                        url('<?php echo $this->themeURI ?>/fonts/LeckerliOne.woff') format('woff');
                    font-weight: normal;
                    font-style: normal;
                    font-display: swap;
                }
                body {
                    background-color: #0F595D;
                    color: #262D2E;
                }
                .container {
                    background-color: #ffffff;
                }   
                .h1 {
                    color: #0A282A;
                }
                .wpc-redirect-button {
                    color: #fff;
                    background-color: #0F595D;  
                    border-color: #0F595D;
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
    
}
