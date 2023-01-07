<?php
namespace lib;

/**
 * Description of WPCBackend
 * 
 * Class to invoke the backend of the theme e.g. the settings page.
 * Will allow different settings to be made and display the backend menu
 *
 * @author marconagel
 */
class WPCBackend {
    //constructor
    public function __construct() {
        //add backend panes for admins
        if ( is_admin() ) {
            add_action('admin_menu', array($this, 'wpc_add_menu_items'));
        }
    }

    /**
     * add_menu_items()
     * Adds items to the administrative pane of the ig management plugin Dashboard
     */
    public function wpc_add_menu_items() {
        //main page
        add_menu_page(esc_html__( 'Settings', 'tsu-wpconnect-theme' ), esc_html__( 'WPConnect', 'tsu-wpconnect-theme' ), 'edit_pages',
                'wpc_settings', array($this, 'wpc_theme_settings'), '', '25');
    } 
    /*
     * wpc_theme_settings()
     * *******************
     * Display settings for theme
     */
    public function wpc_theme_settings() { 
        //load needed libs
        require_once 'WPCUtilities.php';
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php echo esc_html__( 'WPC theme settings page', 'tsu-wpconnect-theme' ); ?></h1>
            <hr class="wp-header-end">
            <p><?php echo esc_html__( 'Settings page to create a settings file, a redirector and setup the WPC API endpoint.', 'tsu-wpconnect-theme' ); ?></p>   
            <p><b><?php echo esc_html__( 'WPC Version: ', 'tsu-wpconnect-theme' ) . wp_get_theme('tsu-wpconnect-theme')->get( 'Version' ); ?></b></p>
        </div>
        <?php        
    }    
}
