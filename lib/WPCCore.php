<?php
namespace lib;

/**
 * Description of WPCCore
 * Bundles all needed lib classes in the core. 
 * @author marconagel
 */
class WPCCore {
    //private vars
    private $themeDir;
    private $themeURI;
    
    //constructor
    public function __construct() {
        //require libs
        require_once 'WPCBackend.php';
        require_once 'WPCUtilities.php';

        //setup vars
        $this->themeDir = get_template_directory();
        $this->themeURI = get_template_directory_uri();
        
        //start up backend
        new \lib\WPCBackend();
        
        //load textdomain
        add_action('after_setup_theme', array( $this, 'wpc_language_setup' ) );
    }
    /**
     * Load translations
     */
    public function wpc_language_setup(){
        load_theme_textdomain('tsu-wpconnect-theme', $this->themeDir . '/languages');
    }    
    
}
