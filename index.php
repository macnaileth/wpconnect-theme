<?php
/*
 * Landing Page of the wpconnect theme. This page just simply stops all users here and allows to redirect to the APP Page, which can be located somewhere else.
 * This is just a simple page, where no content is delivered.
 * 
 */
//we need to die if no ABSPATH defined
if (!defined('ABSPATH')) { die("Forbidden."); }

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

            if ( $redirect == 'on' && !empty($redirectURI) && preg_match("/^$regex$/i", $redirectURI) ) {                             
                
                $resolvedURI = strpos($redirectURI, "http://") === false && strpos($redirectURI, "https://") === false ? "http://" . $redirectURI : $redirectURI;     
                
                echo '<script>window.location.replace("' . $resolvedURI . '");</script>';
                
            } else {    
                // put your code here
                echo '<h1>Hello World!</h1>';
            }
            wp_footer();
        ?>
    </body>
</html>
