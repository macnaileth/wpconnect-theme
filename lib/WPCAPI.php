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
        //menus and config
        register_rest_route( $namespace, '/' . $base . '/structure/', array(
          'methods' => 'GET',
          'callback' => array($this,'wpc_output_site_structure'),
          'permission_callback' => '__return_true',
          ) 
        );    
        //resolve content via tag
        register_rest_route( $namespace, '/' . $base . '/tagwise/(?P<id>\d+)', array(
          'methods' => 'GET',
          'callback' => array($this,'wpc_output_taglist'),
          'permission_callback' => '__return_true',
          'args' => array(
              'id' => array(
                'validate_callback' => function( $param, $request, $key ) {
                  return is_numeric( $param );
                },
                'required' => true        
              ),),
          ) 
        );
        //resolve content via category
        register_rest_route( $namespace, '/' . $base . '/catwise/(?P<id>\d+)', array(
          'methods' => 'GET',
          'callback' => array($this,'wpc_output_catlist'),
          'permission_callback' => '__return_true',
          'args' => array(
              'id' => array(
                'validate_callback' => function( $param, $request, $key ) {
                  return is_numeric( $param );
                },
                'required' => true        
              ),),
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
 
    /**
     * wpc_output_taglist ()
     * wrapper callback function - list content using specific tag and return to API
     * 
     * @return string the response for use in the api
     */
    public function wpc_output_taglist ( $data ) {
        $response = [];       
        
        $response[ 'status' ] = [ 'code' => 200, 'msg' => 'ok', 'notice' => esc_html__('Content per tag successfully requested.', 'tsu-wpconnect-theme') ];      
        
        $tag = get_tag( $data[ 'id' ] );
        $response[ 'tag' ] = $tag != null ? get_tag( $data[ 'id' ] ) : [ 'notice' => esc_html__('The requested tag does not exist. ID used: ', 'tsu-wpconnect-theme') . $data[ 'id' ] ];
        
        $response[ 'posts' ] = $this->wpc_getposts_listby( $data[ 'id' ] );
        if ( empty( $response[ 'posts' ] ) ) { unset( $response[ 'posts' ] ); } 
        
        return $response;           
    }
    
    /**
     * wpc_output_catlist ()
     * wrapper callback function - list content using specific tag and return to API
     * 
     * @return string the response for use in the api
     */
    public function wpc_output_catlist ( $data ) {
        $response = [];
        
        $response['status'] = [ 'code' => 200, 'msg' => 'ok', 'notice' => esc_html__('Content per category successfully sent.', 'tsu-wpconnect-theme') ];
        
        //TODO: Add code
        
        return $response;        
    }  
    /**
     * wpc_getposts_listby( $criteria = 'tag' )
     * 
     * gets a list of posts by tag or category. 
     * 
     * @param string $needle string / id  (tag or category) to search for
     * @param string $criteria tag | category default: tag.
     * @return array array of objects found, empty if nothing has been found
     */
    private function wpc_getposts_listby( $needle, $criteria = 'tag' ) {
        $return = [];
        
        $tag = get_tag( $needle );
        
        if ( $tag != null ) {         
  
            $query = new \WP_Query( array(
                'tag' => $tag->slug,
                'post_type' => array( 'post', 'page' )
            ) );  
            //get content           
            $pages = $query->posts;
            //loop
            foreach($pages as $page) {    
                if ( has_tag( $tag->name, $page->ID ) ) {                  
                    //got tag
                    array_push( $return, $this->wpc_create_entry( $page ) );
                }
            } 
            
        }
        
        return $return;
    }
    /**
     * wpc_create_entry ( $post )
     * 
     * creates a short list entry for a post fetched via WP_Query
     * 
     * @param type $post
     * @return array
     */
    private function wpc_create_entry ( $post ) {
        
        $return =   [ 
                        'id' => $post->ID,
                        'date' => $post->post_date,
                        'date_gmt' => $post->post_date_gmt,
                        'guid' => [ 'rendered' => $post->guid ],
                        'modified' => $post->post_modified, 
                        'modified_gmt' => $post->post_modified_gmt, 
                        'slug' => $post->post_name, 
                        'type' => $post->post_type, 
                        'status' => $post->post_status,
                        'author' => $post->post_author, 
                        'title' => [ 'rendered' => $post->post_title ],
                        'excerpt' => [ 'rendered' => $post->post_excerpt ],
                        'featured_media' => [ 
                                                'thumb' => get_the_post_thumbnail_url( $post->ID, 'thumbnail' ),
                                                'medium' => get_the_post_thumbnail_url( $post->ID, 'medium' ),
                                                'large' => get_the_post_thumbnail_url( $post->ID, 'large' ),
                                            ],
                        'full' => get_home_url() . '/wp-json/wp/v2/posts/' . $post->ID
                    ];
        
        return $return;
    }
    
}
