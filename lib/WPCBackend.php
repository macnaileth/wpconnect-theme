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
            
            <h1><?php echo esc_html__( 'WPC theme settings page', 'tsu-wpconnect-theme' ); ?></h1>
            <hr class="wp-header-end">
            <p><?php echo esc_html__( 'Settings page to create a settings file, a redirector and setup the WPC API endpoint.', 'tsu-wpconnect-theme' ); ?></p>   
            <p><b><?php echo esc_html__( 'WPC Version: ', 'tsu-wpconnect-theme' ) . wp_get_theme('tsu-wpconnect-theme')->get( 'Version' ); ?></b></p>
                <hr />
                <form method="post" action="options.php">

                    <?php settings_fields('wpc_options'); ?>
                    <?php do_settings_sections( 'wpc_options' ); ?>
                        <h1 class="wp-heading-inline"><?php echo esc_html__( 'Basic Setup', 'tsu-wpconnect-theme' ); ?></h1>
                        <table class="form-table wpc-admin-settings-table">

                            <?php // Redirect Yes/No ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Redirect', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc_redirect'); ?>
                                    <input type="checkbox" name="wpc_options[wpc_redirect]" <?php checked($value, 'on'); ?>> <?php esc_html_e('Redirect directly instead of hitting Splashscreen.', 'tsu-wpconnect-theme'); ?>
                                </td>
                            </tr>

                            <?php // Redirect URL ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Redirect URL', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc_redirect_uri'); ?>
                                    <input type="text" class="wpc-input-limited" maxlength="80" name="wpc_options[wpc_redirect_uri]" value="<?php echo esc_attr($value); ?>">
                                    <?php WPCUtilities::wpc_charcounter(); ?>
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
                        <hr />
                        <h1 class="wp-heading-inline"><?php echo esc_html__( 'Landing/Redirect page settings', 'tsu-wpconnect-theme' ); ?></h1>
                        <table class="form-table wpc-admin-lpsetup-table">
                            <?php // Headline text ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Headline text', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-text'); ?>
                                    <input type="text" class="wpc-input-limited" maxlength="50" name="wpc_options[wpc-fr-hdl-text]" id="wpc-fr-hdl-text" value="<?php echo !empty($value) ? esc_attr($value) : esc_html__( 'Please go on...', 'tsu-wpconnect-theme' ); ?>">
                                    <?php WPCUtilities::wpc_charcounter(); ?>
                                </td>
                            </tr>                            
                            <?php // Body BG Color ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Body background color', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-bg-color'); ?>
                                    <input name="wpc_options[wpc-fr-bg-color]" id="wpc-fr-bg-color" type="text" class="wpc-color-picker" value="<?php echo !empty($value) ? esc_attr($value) : '#0F595D'; ?>" 
                                           size="50" aria-required="false" data-default-color="<?php echo '#0F595D'; ?>">
                                </td>
                            </tr>   
                            <?php // Body Text Color ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Body text color', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-txt-color'); ?>
                                    <input name="wpc_options[wpc-fr-txt-color]" id="wpc-fr-txt-color" type="text" class="wpc-color-picker" value="<?php echo !empty($value) ? esc_attr($value) : '#262D2E'; ?>" 
                                           size="50" aria-required="false" data-default-color="<?php echo '#262D2E'; ?>">
                                </td>
                            </tr> 
                            <?php // Body Headline h1 Color ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Body headline color', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-color'); ?>
                                    <input name="wpc_options[wpc-fr-hdl-color]" id="wpc-fr-hdl-color" type="text" class="wpc-color-picker" value="<?php echo !empty($value) ? esc_attr($value) : '#0A282A'; ?>" 
                                           size="50" aria-required="false" data-default-color="<?php echo '#0A282A'; ?>">
                                </td>
                            </tr>   
                            <?php // Center Container BG Color ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Content container background color', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-cbg-color'); ?>
                                    <input name="wpc_options[wpc-fr-cbg-color]" id="wpc-fr-cbg-color" type="text" class="wpc-color-picker" value="<?php echo !empty($value) ? esc_attr($value) : '#ffffff'; ?>" 
                                           size="50" aria-required="false" data-default-color="<?php echo '#ffffff'; ?>">
                                </td>
                            </tr>      
                            <?php // Link Button background & border color ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Button color', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-btn-color'); ?>
                                    <input name="wpc_options[wpc-fr-btn-color]" id="wpc-fr-btn-color" type="text" class="wpc-color-picker" value="<?php echo !empty($value) ? esc_attr($value) : '#0F595D'; ?>" 
                                           size="50" aria-required="false" data-default-color="<?php echo '#0F595D'; ?>">
                                </td>
                            </tr>   
                            <?php // Link Button text color ?>
                            <tr valign="top">
                                <th scope="row"><?php esc_html_e('Button text color', 'tsu-wpconnect-theme'); ?></th>
                                <td>
                                    <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-btt-color'); ?>
                                    <input name="wpc_options[wpc-fr-btt-color]" id="wpc-fr-btt-color" type="text" class="wpc-color-picker" value="<?php echo !empty($value) ? esc_attr($value) : '#fff'; ?>" 
                                           size="50" aria-required="false" data-default-color="<?php echo '#fff'; ?>">
                                </td>
                            </tr>                            
                        </table>
                    <?php submit_button(); ?>

                </form>            
        </div><!-- .wrap -->
        <?php        
    }    
}
