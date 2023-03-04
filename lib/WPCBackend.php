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
    //vars
    private $dir;
    //constructor
    public function __construct() {
        //reuqire libs
        require_once 'WPCMStructure.php';
        //get uris
        $this->dir = WPCUtilities::wpc_dirs();
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
        <script>
            //max size of JSON string
            const maxJSONSize = <?php echo WPC_MAX_JSONSTR_LENGTH; ?>;
        </script> 
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
                        </table>
                        <div class="wpc-settings-buttons">
                            <a href="#" class="wpc-tab-btn active" data-target="#wpc_settings_frontend">
                                <?php echo esc_html__( 'Landing page', 'tsu-wpconnect-theme' ); ?>
                            </a>  
                            <a href="#" class="wpc-tab-btn" data-target="#wpc_settings_structure">
                                <?php echo esc_html__( 'Structure', 'tsu-wpconnect-theme' ); ?>
                            </a>                             
                            <a href="#" class="wpc-tab-btn" data-target="#wpc_settings_api">
                                <?php echo esc_html__( 'API settings', 'tsu-wpconnect-theme' ); ?>
                            </a>                               
                        </div>
                        <div class="wpc-settings-container">
                            <div class="wpc-settings active" id="wpc_settings_frontend">
                                <h1 class="wp-heading-inline"><?php echo esc_html__( 'Landing/Redirect page settings', 'tsu-wpconnect-theme' ); ?></h1>
                                <table class="form-table wpc-admin-lpsetup-table">

                                    <?php // image link or uri ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Logo or other image', 'tsu-wpconnect-theme'); ?></th>
                                        <td>
                                            <?php 
                                                $value = WPCUtilities::wpc_get_theme_option('wpc-fr-image'); 
                                                $display_image = WPCUtilities::wpc_imagelink($value, 'Medium');  
                                            ?>
                                            <img class="wpc-logo-be" src="<?php echo $display_image === '' ? $this->dir['uri'] . '/img/wpconnect2023.svg': $display_image; ?>">
                                            <br />
                                            <input type="text" class="wpc-input-limited" maxlength="150" name="wpc_options[wpc-fr-image]" id="wpc-fr-image" value="<?php echo !empty($value) ? esc_attr($value) : $this->dir['uri'] . '/img/wpconnect2023'; ?>">
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                            <br />
                                            <p><?php esc_html_e('You can either use a link to an image or the numeric id of the image used in the WordPress media library.', 'tsu-wpconnect-theme'); ?></p>
                                        </td>
                                    </tr> 
                                    
                                    <?php 
                                        // favicons 16x16, 32x32, 96x96 
                                        $favs = [
                                                    '16x16' => WPCUtilities::wpc_get_theme_option('wpc-fr-16x16'),
                                                    '32x32' => WPCUtilities::wpc_get_theme_option('wpc-fr-32x32'),
                                                    '96x96' => WPCUtilities::wpc_get_theme_option('wpc-fr-96x96')
                                                ];
                                    ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Favicon for size', 'tsu-wpconnect-theme'); ?> 16x16</th>
                                        <td>
                                            <input type="text" class="wpc-input-limited" maxlength="150" name="wpc_options[wpc-fr-16x16]" id="wpc-fr-16x16" value="<?php echo esc_attr($favs['16x16']); ?>">
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                        </td>
                                    </tr>                                        
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Favicon for size', 'tsu-wpconnect-theme'); ?> 32x32</th>
                                        <td>                                        
                                            <input type="text" class="wpc-input-limited" maxlength="150" name="wpc_options[wpc-fr-32x32]" id="wpc-fr-32x32" value="<?php echo esc_attr($favs['32x32']); ?>">
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                        </td>  
                                    </tr>                                        
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Favicon for size', 'tsu-wpconnect-theme'); ?> 96x96</th>
                                        <td>                                         
                                            <input type="text" class="wpc-input-limited" maxlength="150" name="wpc_options[wpc-fr-96x96]" id="wpc-fr-96x96" value="<?php echo esc_attr($favs['96x96']); ?>">
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                            <br />                                                
                                            <p><?php esc_html_e('Linked image will be encoded base64 on the fly - keep them small. Always use .png!', 'tsu-wpconnect-theme'); ?></p>
                                            <p><i><?php esc_html_e('You do not need to fill in all image sizes. Blank fields will not be used.', 'tsu-wpconnect-theme'); ?></i></p>
                                        </td>
                                    </tr>                                    

                                    <?php // Headline text ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Headline text', 'tsu-wpconnect-theme'); ?></th>
                                        <td>
                                            <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-text'); ?>
                                            <input type="text" class="wpc-input-limited" maxlength="50" name="wpc_options[wpc-fr-hdl-text]" id="wpc-fr-hdl-text" value="<?php echo !empty($value) ? esc_attr($value) : esc_html__( 'Please go on...', 'tsu-wpconnect-theme' ); ?>">
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                        </td>
                                    </tr> 

                                    <?php // content text ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Content text', 'tsu-wpconnect-theme'); ?></th>
                                        <td>
                                            <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-cnt-text'); ?>
                                            <textarea id="wpc-fr-cnt-text" name="wpc_options[wpc-fr-cnt-text]" class="wpc-input-limited wpc-tarea-noresize" rows="4" cols="50" maxlength="250"><?php echo !empty($value) ? esc_attr($value) : esc_html__('You arrived at the pages of', 'tsu-wpconnect-theme') . ' ' . get_bloginfo('name')  . '. ' .  esc_html__('We regret that this is not the correct page. To access our content, click the button below.', 'tsu-wpconnect-theme'); ?></textarea>
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                        </td>
                                    </tr> 

                                    <?php // Button Yes/No ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Redirection button', 'tsu-wpconnect-theme'); ?></th>
                                        <td>
                                            <?php $value = WPCUtilities::wpc_get_theme_option('wpc_rebutton'); ?>
                                            <input type="checkbox" name="wpc_options[wpc_rebutton]" <?php checked($value, 'on'); ?>> <?php esc_html_e('Button to redirect to the url where your app resides.', 'tsu-wpconnect-theme'); ?>
                                        </td>
                                    </tr>    

                                    <?php // Headline font ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('URL to the headline font in .woff format', 'tsu-wpconnect-theme'); ?></th>
                                        <td>
                                            <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-font'); ?>
                                            <input type="text" class="wpc-input-limited" maxlength="150" name="wpc_options[wpc-fr-hdl-font]" id="wpc-fr-hdl-font" value="<?php echo !empty($value) ? esc_attr($value) : $this->dir['uri'] . '/fonts/LeckerliOne.woff'; ?>">
                                            <?php WPCUtilities::wpc_charcounter(); ?>
                                        </td>
                                    </tr>  

                                    <?php // Content font ?>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('URL to the content font in .woff format', 'tsu-wpconnect-theme'); ?></th>
                                        <td>
                                            <?php $value = WPCUtilities::wpc_get_theme_option('wpc-fr-cnt-font'); ?>
                                            <input type="text" class="wpc-input-limited" maxlength="150" name="wpc_options[wpc-fr-cnt-font]" id="wpc-fr-cnt-font" value="<?php echo !empty($value) ? esc_attr($value) : $this->dir['uri'] . '/fonts/FengardoNeue.woff'; ?>">
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
                            </div>
                            <div class="wpc-settings" id="wpc_settings_structure">
                                <h1 class="wp-heading-inline"><?php echo esc_html__( 'Menu structure setup', 'tsu-wpconnect-theme' ); ?></h1>  
                                <?php 
                                    // Menu structure
                                    $menu = WPCUtilities::wpc_get_theme_option('wpc-menu-structure'); 
                                    if ( empty($menu) ) : ?>
                                        <p class="wpc-no-menu">
                                            <i><?php esc_html_e('No menu structure found. To start creating a menu structure for your content useable via API access click the button below.', 'tsu-wpconnect-theme'); ?></i>
                                        </p>
                                    <?php endif;
                                    if ( strlen($menu) >= WPC_MAX_JSONSTR_LENGTH ) : ?>
                                        <div class="wpc-warning danger">
                                            <strong><?php echo esc_html__( 'Warning', 'tsu-wpconnect-theme' ); ?>: </strong><?php echo esc_html__( 'JSON String length exceeded. No more menus or items could be added.', 'tsu-wpconnect-theme' ); ?>
                                        </div>
                                    <?php endif; ?> 
                                    <div id="wpc_menuJSON_stat" style="display: none;">
                                        <div class="wpc-headline"><?php echo esc_html__( 'Characters left:', 'tsu-wpconnect-theme' ); ?><br></div>
                                        <div class="wpc-flex">
                                            <div class="wpc-menubar full" style="width:50%;"></div>
                                            <div class="wpc-menubar empty" style="width:50%;"></div>
                                        </div>
                                    </div>
                                    <div id="wpc_menu_structure_container"></div>   
                                    <button type="button" class="thickbox button wpc-create-menu wpc_lvl_1_menu"><?php esc_html_e('Add menu structure', 'tsu-wpconnect-theme'); ?></button>  
                                <input type="hidden" class="wpc-hidden-input" name="wpc_options[wpc-menu-structure]" id="wpc-menu-structure" value="<?php echo esc_attr($menu); ?>">                                  
                            </div>                            
                            <div class="wpc-settings" id="wpc_settings_api">
                                <h1 class="wp-heading-inline"><?php echo esc_html__( 'API settings', 'tsu-wpconnect-theme' ); ?></h1>                                
                            </div>
                        </div>
                    <?php submit_button(); ?>

                </form>            
        </div><!-- .wrap -->

        <div id="wpc_menu_modal" class="wpc-modal" style="display: none">
            <div class="wpc-modal-window">   
                <?php 
                    $modalContent = new WPCMStructure();

                    $modalContent->wpc_modal_form( 1 );
                    
                    $modalContent->wpc_modal_form( 2 );

                ?>    
            </div>
        </div>
        
        <?php        
    }    
}
