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
    /**
     * wpc_dirs()
     * 
     * @return associative array path: template dirctory, uri: template directory uri
     */
    public static function wpc_dirs() {
        return [ "path" => get_template_directory(), "uri" => get_template_directory_uri() ];
    }
    /**
     * wpc_console_log($output, $with_script_tags = true)
     * Thanks to: Kim Sia // https://stackify.com/how-to-log-to-console-in-php/
     * 
     * @param string $output string to be logged to console
     * @param type $with_script_tags [default: true] boolean: weather to enclose js console.log within <script> tags or not
     */
    public static function wpc_console_log($output, $with_script_tags = true) {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }   

}
