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
defined( 'ABSPATH' ) or die( 'Direct access not allowed!' );

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

            // Checkbox redirect
            if (!empty($options['wpc_redirect'])) {
                $options['wpc_redirect'] = 'on';
            } else {
                unset($options['wpc_redirect']); // Remove from options if not checked
            }

            // Checkbox button
            if (!empty($options['wpc_rebutton'])) {
                $options['wpc_rebutton'] = 'on';
            } else {
                unset($options['wpc_rebutton']); // Remove from options if not checked
            }            

            // Input redirect wpc-fr-image
            if (!empty($options['wpc_redirect'])) {   
                $options['wpc_redirect'] = sanitize_text_field($options['wpc_redirect']);
            } else {
                unset($options['wpc_redirect']); // Remove from options if empty or invalid
            }

            // Input logo image
            if (!empty($options['wpc-fr-image'])) {   
                $options['wpc-fr-image'] = sanitize_text_field($options['wpc-fr-image']);
            } else {
                unset($options['wpc-fr-image']); // Remove from options if empty or invalid
            }     
            
            // Favicon 16x16
            if (!empty($options['wpc-fr-16x16'])) {   
                $options['wpc-fr-16x16'] = sanitize_text_field($options['wpc-fr-16x16']);
            } else {
                unset($options['wpc-fr-16x16']); // Remove from options if empty or invalid
            }   
            
            // Favicon 32x32
            if (!empty($options['wpc-fr-32x32'])) {   
                $options['wpc-fr-32x32'] = sanitize_text_field($options['wpc-fr-32x32']);
            } else {
                unset($options['wpc-fr-32x32']); // Remove from options if empty or invalid
            }    
            
            // Favicon 96x96
            if (!empty($options['wpc-fr-96x96'])) {   
                $options['wpc-fr-96x96'] = sanitize_text_field($options['wpc-fr-96x96']);
            } else {
                unset($options['wpc-fr-96x96']); // Remove from options if empty or invalid
            }   
            
            // Menu structure hidden field
            if (!empty($options['wpc-menu-structure'])) {   
                if ( $options['wpc-menu-structure'] < WPC_MAX_JSONSTR_LENGTH ) {
                    $options['wpc-menu-structure'] = sanitize_text_field($options['wpc-menu-structure']);
                }
            } else {
                unset($options['wpc-menu-structure']); // Remove from options if empty or invalid
            }             

            // Headline Font URL
            if (!empty($options['wpc-fr-hdl-font'])) {   
                $options['wpc-fr-hdl-font'] = sanitize_text_field($options['wpc-fr-hdl-font']);
            } else {
                unset($options['wpc-fr-hdl-font']); // Remove from options if empty or invalid
            }             
            
            // Content Font URL
            if (!empty($options['wpc-fr-cnt-font'])) {   
                $options['wpc-fr-cnt-font'] = sanitize_text_field($options['wpc-fr-cnt-font']);
            } else {
                unset($options['wpc-fr-cnt-font']); // Remove from options if empty or invalid
            }               
            
            // Input frontend headline
            if (!empty($options['wpc-fr-hdl-text'])) {   
                $options['wpc-fr-hdl-text'] = sanitize_text_field($options['wpc_fr_hdl_text']);
            } else {
                $options['wpc-fr-hdl-text'] = esc_html__( 'Please go on...', 'tsu-wpconnect-theme' ); // set default text
            }        

            // textfield frontend
            if (!empty($options['wpc-fr-cnt-text'])) {   
                $options['wpc-fr-cnt-text'] = sanitize_text_field($options['wpc-fr-cnt-text']);
            } else {
                $options['wpc-fr-cnt-text'] = esc_html__('You arrived at the pages of', 'tsu-wpconnect-theme') . ' ' . get_bloginfo('name') . '. ' . esc_html__('We regret that this is not the correct page. To access our content, click the button below.', 'tsu-wpconnect-theme');  // set default text
            }               
            
            // Input frontend bg color
            if (!empty($options['wpc-fr-bg-color'])) {   
                $options['wpc-fr-bg-color'] = sanitize_text_field($options['wpc-fr-bg-color']);
            } else {
                $options['wpc-fr-bg-color'] = '#0F595D'; // set default color
            }   
            
            // Input frontend body text color
            if (!empty($options['wpc-fr-txt-color'])) {   
                $options['wpc-fr-txt-color'] = sanitize_text_field($options['wpc-fr-bg-color']);
            } else {
                $options['wpc-fr-txt-color'] = '#262D2E'; // set default color
            }   
            
            // Input frontend headline text color
            if (!empty($options['wpc-fr-hdl-color'])) {   
                $options['wpc-fr-hdl-color'] = sanitize_text_field($options['wpc-fr-hdl-color']);
            } else {
                $options['wpc-fr-hdl-color'] = '#262D2E'; // set default color
            }  
            
            // Input content container bg color
            if (!empty($options['wpc-fr-cbg-color'])) {   
                $options['wpc-fr-cbg-color'] = sanitize_text_field($options['wpc-fr-cbg-color']);
            } else {
                $options['wpc-fr-cbg-color'] = '#ffffff'; // set default color
            }  
            
            // Input button bg color
            if (!empty($options['wpc-fr-btn-color'])) {   
                $options['wpc-fr-btn-color'] = sanitize_text_field($options['wpc-fr-btn-color']);
            } else {
                $options['wpc-fr-btn-color'] = '#0F595D'; // set default color
            }       
            
            // Input button text color
            if (!empty($options['wpc-fr-btt-color'])) {   
                $options['wpc-fr-btt-color'] = sanitize_text_field($options['wpc-fr-btn-color']);
            } else {
                $options['wpc-fr-btt-color'] = '#fff'; // set default color
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
    /**
     * wpc_charcounter()
     * 
     * adds a charcounting label to a input field
     * input field must have a maxlength attribute
     * and the class wpc-input-limited
     */
    public static function wpc_charcounter() {        
        ?>
            <label class="wpc-word-counter wpc-label-small" for="wpc-fr-hdl-text">
                <span class="wpc-label-txt"><?php echo esc_html__('Remaining chars', 'tsu-wpconnect-theme') . ': '; ?></span>
                <span class="wpc-counter-num"></span>
            </label>
        <?php
        
    }
    /**
     * wpc_fileexists( $link )
     * 
     * function to check whether a file behind a link exists or not
     * returns true if existing, false if not.
     * 
     * @param string $link  Link/url to check
     * @return boolean 
     */
    public static function wpc_fileexists( $link ) {
        $file = $link;
        if (!empty($link)){
            $file_headers = @get_headers($file);
            if(!$file_headers || strpos($file_headers[0], '404')) {
                return false;
            }
            else {
                return true;
            }   
        } else {
            return false;
        }
    }
    /**
     * wpc_imagelink( $linknum, $size = 'full' )
     * 
     * function to return an image according to input: if a link is used,
     * it is checked if existing and returned if so. If numeric input e.g.
     * WordPress Image ID is used, the image is retrieved from media lib and
     * returned.
     * 
     * @param string $linknum   URL or image ID.
     * @param string $size      parameter for image output. WordPress standard formats are:
     *                          Thumbnail | Medium | Medium Large | Large | Full [default]
     * @return string           Returns link on success, empty string on error 
     */
    public static function wpc_imagelink( $linknum, $size = 'full' ) {
        
            $display_image = '';

            //check if image is what it should be
            if(is_numeric($linknum)){
                //wordpress image id
                $image_arr = wp_get_attachment_image_src($linknum, $size);  
                $display_image = $image_arr[0];

            } else {
                //link - check if existing
                if( self::wpc_fileexists($linknum) ){
                    $display_image = $linknum;
                }    
            } 
            return $display_image;
    }
    /**
     * wpc_encodeimage_b64( $link )
     * -----------------------------
     * 
     * encodes a linked image base64 to be directly embedded
     * 
     * @param string $link to image to encode
     * @return string $data base64 encoded data
     */
    public static function wpc_encodeimage_b64( $link ) {
        
        $img = file_get_contents( $link );
        $data = base64_encode($img);

        // return output
        return $data;  
        
    }
    /**
     * wpc_ddoptions( $type = 'PAGES' )
     * ---------------------------------
     * 
     * returns specificly wordpress pages or posts as dropdown options with id as value
     * based on the example in codex: https://developer.wordpress.org/reference/functions/get_pages/
     * 
     * @param string $type Can be set to PAGES | POSTS | CATEGORIES - Default is PAGES
     * @return string returns either a html options string for use in a <select>-element or empty if nothing was retrieved
     * 
     */
    public static function wpc_ddoptions( $type = 'PAGES' ) {
        
        $options = '';
        
        if ( $type == 'PAGES' ){
            $pages = get_pages(); 

            foreach ( $pages as $page ) {
                  $option = '<option value="' . $page->ID . '">';
                  $option .= esc_html( $page->post_title );
                  $option .= '</option>';
                  $options .= $option;
            } 
        }else if ( $type == 'POSTS' ){
            $posts = get_posts(); 

            foreach ( $posts as $post ) {
                  $option = '<option value="' . $post->ID . '">';
                  $option .= esc_html( $post->post_title );
                  $option .= '</option>';
                  $options .= $option;
            } 
        }else if ( $type == 'CATEGORIES' ) {
            $cats = get_categories( [ 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false ] ); 

            foreach ( $cats as $cat ) {
                  $option = '<option value="' . $cat->term_id . '">';
                  $option .= esc_html( $cat->name );
                  $option .= '</option>';
                  $options .= $option;
            }             
        }else if ( $type == 'TAGS' ) {
            $tags = get_tags( [ 'orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false ] ); 

            foreach ( $tags as $tag ) {
                  $option = '<option value="' . $tag->term_id . '">';
                  $option .= esc_html( $tag->name );
                  $option .= '</option>';
                  $options .= $option;
            }             
        }
        
        return $options;
    }
    
}
