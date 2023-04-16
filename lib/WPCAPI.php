<?php
namespace lib;

/**
 * Description of WPCAPI
 * 
 * Class to output the data to the WordPres REST API. Adds an endpoint
 *
 * @author marconagel
 */
defined( 'ABSPATH' ) or die( 'Direct access not allowed!' );

class WPCAPI {
    
    public function __construct() {       
        //load needed libs
        require_once 'WPCUtilities.php';           
        //init routes
        add_action( 'rest_api_init', array( $this, 'wpc_register_routes' ) );         
    }
    
    /**
     * wpc_register_routes
     * 
     * registers new rest routes to the REST API. 
     * Following routes are registered:
     * 
     * - /tsu-wpconnect/v1/site/structure/ => returns site structure
     * 
     */
    public function wpc_register_routes () {

        $version = '1';
        $namespace = 'tsu-wpconnect/v' . $version;
        $base = 'site';        
        
        register_rest_route( $namespace, '/' . $base . '/structure/', array(
          'methods' => 'GET',
          'callback' => array($this,'wpc_output_site_structure'),
          'permission_callback' => '__return_true',
          ) 
        );           
    }
    
    public function wpc_output_site_structure () {
        $response = [];
        
        $response['status'] = [ 'code' => 200, 'msg' => 'ok', 'notice' => esc_html__('Site structure successfully sent.', 'tsu-wpconnect-theme') ];
        $response['info'] = [ 
                                        'theme' => wp_get_theme()->get( 'Name' ),
                                        'version' => wp_get_theme()->get( 'Version' ),
                                        'how-to' => esc_html__('You can use the structured data to build your frontend, website or app on it. See below supported menu item types', 'tsu-wpconnect-theme'),
                                        'item-types' => [ '0 - Page', '1 - Post', '2 - Category', '3 - Tag', '4 - Link' ]
                                    ];
        $menuString = WPCUtilities::wpc_get_theme_option('wpc-menu-structure'); 
        $response['menus'] = json_decode( $menuString );
        
        return $response;
    }
}
