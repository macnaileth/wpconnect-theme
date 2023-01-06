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
            // put your code here
            echo '<h1>Hello World!</h1>';
            wp_footer();
        ?>
    </body>
</html>
