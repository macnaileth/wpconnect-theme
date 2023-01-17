<?php
namespace lib;

/**
 * Helper class containing static functions to create the frontend landing page
 *
 * Mainly uses the static functions from wpexplorers helpful page on how to build settings pages in wp:
 * https://www.wpexplorer.com/wordpress-theme-options/ - Kudos to AJ Clarke for the article
 * 
 * @author marconagel
 */

class WPCLandingPage {
    //put your code here
    public static function wpc_create_lp( $logoimg, $uri, $type = 'SUCCESS' ) {
        
        if ($type === "SUCCESS") {
            ?>
                <div class="container">
                    <div class="row center">
                        <div class="column">
                            <img class="wpc-logo-large" src="<?php echo $logoimg; ?>">
                        </div>
                        <div class="column">
                            <h1><?php 
                                    echo !empty(WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-text')) ? 
                                            WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-text') : 
                                            esc_html__( 'Please go on...', 'tsu-wpconnect-theme' ); 
                                ?>
                            </h1>
                            <p><?php esc_html_e('You arrived at the pages of', 'tsu-wpconnect-theme'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('We regret that this is not the correct page. To access our content, click the button below.', 'tsu-wpconnect-theme'); ?></p>
                            <a class="wpc-redirect-button" href="<?php echo $uri ?>"><?php esc_html_e('Continue to', 'tsu-wpconnect-theme'); ?> <?php bloginfo('name'); ?></a>
                        </div>
                    </div>
                </div>
            <?php
        } else if ($type === "ERROR") {
            ?>
                <div class="container">
                    <div class="row center">
                        <div class="column">
                            <img class="wpc-logo-large" src="<?php echo $logoimg; ?>">
                        </div>
                        <div class="column">
                            <h1><?php 
                                    echo !empty(WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-text')) ? 
                                            WPCUtilities::wpc_get_theme_option('wpc-fr-hdl-text') : 
                                            esc_html__( 'Please go on...', 'tsu-wpconnect-theme' ); 
                                ?>
                            </h1>
                            <p><?php esc_html_e('You arrived at the pages of', 'tsu-wpconnect-theme'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('We regret that this is not the correct page.', 'tsu-wpconnect-theme'); ?></p>
                        </div>
                    </div>
                </div>
            <?php            
        } else {
            echo esc_html__('If you hit this screen, something regarding the web frontend was configured in a very wrong way. There was no landing page type defined. Contact site admin:', 'tsu-wpconnect-theme') . ' ' . get_bloginfo('name'); 
        }
    }
}
