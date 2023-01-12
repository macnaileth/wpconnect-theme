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
            add_action('admin_init', array($this, 'wpc_register_settings'));
        }
    }

    /**
     * add_menu_items()
     * Adds items to the administrative pane of the ig management plugin Dashboard
     */
    public function wpc_add_menu_items() {
        //main page
        add_menu_page(esc_html__( 'Settings', 'tsu-wpconnect-theme' ), esc_html__( 'WPConnect', 'tsu-wpconnect-theme' ), 'manage_options',
                'wpc_settings', array($this, 'wpc_theme_settings'), '', '25');
    } 
    /**
     * wpc_register_settings()
     * Register theme settings
     */    
    public static function wpc_register_settings() {
        register_setting('wpc_options', 'wpc_options', array('WPCUtilities', 'wpc_sanitize'));
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
                <hr />
                <form method="post" action="options.php">

                    <?php settings_fields('wpc_options'); ?>
                    <?php do_settings_sections( 'wpc_options' ); ?>
                        <table class="form-table wpc-admin-settings-table">

                            <?php // Checkbox example ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Redirect', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc_redirect'); ?>
                                    <input type="checkbox" name="wpc_options[wpc_redirect]" <?php checked($value, 'on'); ?>> <?php esc_html_e('Redirect directly instead of hitting Splashscreen.', 'tsu-wpconnect-theme'); ?>
                                </td>
                            </tr>

                            <?php // Text input example ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Redirect URL', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc_redirect_uri'); ?>
                                    <input type="text" name="wpc_options[wpc_redirect_uri]" value="<?php echo esc_attr($value); ?>">
                                </td>
                            </tr>

                            <?php // Select example ?>
                            <tr valign="top" class="wpex-custom-admin-screen-background-section">
                                <th scope="row"><?php esc_html_e('Select Example', 'text-domain'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('select_example'); ?>
                                    <select name="theme_options[select_example]">
                                        <?php
                                        $options = array(
                                            '1' => esc_html__('Option 1', 'text-domain'),
                                            '2' => esc_html__('Option 2', 'text-domain'),
                                            '3' => esc_html__('Option 3', 'text-domain'),
                                        );
                                        foreach ($options as $id => $label) {
                                            ?>
                                            <option value="<?php echo esc_attr($id); ?>" <?php selected($value, $id, true); ?>>
                                            <?php echo strip_tags($label); ?>
                                            </option>
                                       <?php } ?>
                                    </select>
                                </td>
                            </tr>

                        </table>

                    <?php submit_button(); ?>

                </form>            
        </div><!-- .wrap -->
        <?php        
    }    
}
