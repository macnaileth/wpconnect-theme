<?php
/*
 * Landing Page of the wpconnect theme. This page just simply stops all users here and allows to redirect to the APP Page, which can be located somewhere else.
 * This is just a simple page, where no content is delivered.
 * 
 */
//we need to die if no ABSPATH defined
if (!defined('ABSPATH')) { die("Forbidden."); }
//get path and uri
$dir = lib\WPCUtilities::wpc_dirs();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php wp_head(); ?>
    </head>
    <body <?php body_class(); ?>>       
        <?php
            wp_body_open();
            //if we have the option for redirect set, do a direct redirect instead of loading stuff at all window.location.replace("http://www.w3schools.com");
            $redirect = lib\WPCUtilities::wpc_get_theme_option('wpc_redirect');
            $redirectURI = lib\WPCUtilities::wpc_get_theme_option('wpc_redirect_uri');
            
            //check with regex
            $regex = "((https?|ftp)\:\/\/)?";
            $regex .= "([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?";
            $regex .= "([a-z0-9-.]*)\.([a-z]{2,3})";
            $regex .= "(\:[0-9]{2,5})?";
            $regex .= "(\/([a-z0-9+\$_-]\.?)+)*\/?";
            $regex .= "(\?[a-z+&\$_.-][a-z0-9;:@&%=+\/\$_.-]*)?";
            $regex .= "(#[a-z_.-][a-z0-9+\$_.-]*)?";
            
            $resolvedURI = '';

            if ( !empty($redirectURI) && preg_match("/^$regex$/i", $redirectURI) ) {                             
                
                $resolvedURI = strpos($redirectURI, "http://") === false && strpos($redirectURI, "https://") === false ? "http://" . $redirectURI : $redirectURI;     
                
                if ( $redirect == 'on' ) {
                    //redirect via JS
                    echo '<script>window.location.replace("' . $resolvedURI . '");</script>';
                    //set no script tag for users with js disabled
                    echo '<noscript>' 
                        . esc_html__(
                                'Normally, you should be redirected. It seems that you have Javascript disabled in your browser or your browser cannot handle script. You will find our app here: ', 
                                'tsu-wpconnect-theme'
                                ) 
                        . '<a href="' . $resolvedURI . '">' . $resolvedURI . '</a>' 
                        . '</noscript>';
                
                } else {
                    //success, no redirection
                    lib\WPCLandingPage::wpc_create_lp( $dir['uri'] . '/img/wpconnect2023.svg', $resolvedURI, 'SUCCESS');
                    //log it
                    lib\WPCUtilities::wpc_console_log( esc_html__('*** Landing page mounted successfully! ***', 'tsu-wpconnect-theme') );
                    
                }
                
            } else {   
                //error resolving uri
                lib\WPCLandingPage::wpc_create_lp( $dir['uri'] . '/img/wpconnect2023.svg', $resolvedURI, 'ERROR');
                //log it also
                lib\WPCUtilities::wpc_console_log( esc_html__('*** Landing page mounting error: Redirect/Application-URL not defined! ***', 'tsu-wpconnect-theme') );                
                
            }
  
            wp_footer();
        ?>
    </body>
</html>
