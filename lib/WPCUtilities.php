<?php
namespace lib;

/**
 * Helper class containing static functions used in the theme 
 *
 * Mainly uses the static functions from wpexplorers helpful page on how to build settings pages in wp:
 * https://www.wpexplorer.com/wordpress-theme-options/ - Kudos to AJ Clarke for the article
 * 
 * @author marconagel
 */
class WPCUtilities {
    
    /**
     * wpc_get_theme_options()
     * Returns all theme options
     *
     */
    public static function wpc_get_theme_options() {
        return get_option('wpc_options');
    }

    /**
     * wpc_get_theme_option($id)
     * Returns single theme option
     *
     */
    public static function wpc_get_theme_option($id) {
        $options = self::wpc_get_theme_options();
        if (isset($options[$id])) {
            return $options[$id];
        }
    }

    /**
     * wpc_sanitize($options) 
     * function to sanitize and secure theme options
     * 
     * @param type $options
     * @return type
     */
    public static function wpc_sanitize($options) {

        // If we have options lets sanitize them
        if ($options) {

            // Checkbox
            if (!empty($options['wpc_redirect'])) {
                $options['wpc_redirect'] = 'on';
            } else {
                unset($options['wpc_redirect']); // Remove from options if not checked
            }

            // Input
            if (!empty($options['wpc_redirect'])) {   
                $options['wpc_redirect'] = sanitize_text_field($options['wpc_redirect']);
            } else {
                unset($options['wpc_redirect']); // Remove from options if empty or invalid
            }

            // Select
            if (!empty($options['select_example'])) {
                $options['select_example'] = sanitize_text_field($options['select_example']);
            }
        }

        // Return sanitized options
        return $options;
    }

}
