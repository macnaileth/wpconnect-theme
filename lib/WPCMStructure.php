<?php
namespace lib;

/**
 * WPCMStructure
 * Class to handle the menu structure stuff, mainly input forms,
 * in connection with the javascript handlers.
 *
 * @author marconagel
 */
class WPCMStructure {

    public function __construct() {
        //require libs
        require_once 'WPCUtilities.php';
    }
    
    public function wpc_modal_form( $level = 1 ) {
        
        //base menu addition
        if ( $level == 1 ){
            ?>
                <div class="wpc-modal-content" id="wpc_modal_lvl1">
                    <div class="wpc-modal-head">
                        <div class="wpc-col">
                            <h2><?php esc_html_e( 'Setup a base menu', 'tsu-wpconnect-theme' ); ?></h2>
                        </div>
                        <div class="wpc-col">
                            <h2><a class="wpc-close">&times;</a></h2>
                        </div>
                    </div>
                    <div class="wpc-modal-body">
                        <div class="wpc-input-group">
                            <label for="wpc_input_menu_lvl1"><?php esc_html_e('Name for menu', 'tsu-wpconnect-theme'); ?></label>
                            <input type="text" class="wpc-input-limited" maxlength="50" name="wpc_input_menu_lvl1" id="wpc_input_menu_lvl1" value=""> 
                            <?php WPCUtilities::wpc_charcounter(); ?>
                        </div>
                        <p><?php esc_html_e( 'Here you can setup your base level menu structures for being outputted via the api. You can fill this structure with different menu items.', 'tsu-wpconnect-theme' ); ?></p>
                    </div>
                    <div class="wpc-modal-foot">
                        <button type="button" id="wpc_submit_menu_lvl1" class="button button-primary wpc-submit-menu"><?php esc_html_e('Create & close', 'tsu-wpconnect-theme'); ?></button>
                        <button type="button" class="button button-secondary wpc-close"><?php esc_html_e('Cancel', 'tsu-wpconnect-theme'); ?></button>
                    </div>
                </div>
            <?php
        }
        //item addition and edit
        if ( $level == 2 ){
            ?>
                <div class="wpc-modal-content" id="wpc_modal_lvl2">
                    <div class="wpc-modal-head">
                        <div class="wpc-col">
                            <h2><?php esc_html_e( 'Add item to menu', 'tsu-wpconnect-theme' ); ?></h2>
                        </div>
                        <div class="wpc-col">
                            <h2><a class="wpc-close">&times;</a></h2>
                        </div>
                    </div>
                    <div class="wpc-modal-body">
                        <div class="wpc-input-group">
                            <label for="wpc_item_name"><?php esc_html_e('Name for item', 'tsu-wpconnect-theme'); ?></label>
                            <input type="text" class="wpc-input-limited" maxlength="50" name="wpc_item_name" id="wpc_item_name" value=""> 
                            <?php WPCUtilities::wpc_charcounter(); ?>
                        </div>
                        <div class="wpc-input-group wpc-row">
                            <div class="wpc-col">
                                <label for="wpc_item_type"><?php esc_html_e('Item type', 'tsu-wpconnect-theme'); ?></label>
                                <select id="wpc_item_type" name="wpc_item_type">
                                    <option value="0"><?php esc_html_e('Page', 'tsu-wpconnect-theme'); ?></option>
                                    <option value="1"><?php esc_html_e('Post', 'tsu-wpconnect-theme'); ?></option>
                                    <option value="2"><?php esc_html_e('Category', 'tsu-wpconnect-theme'); ?></option>
                                    <option value="3"><?php esc_html_e('Tag', 'tsu-wpconnect-theme'); ?></option>
                                    <option value="4"><?php esc_html_e('Custom link', 'tsu-wpconnect-theme'); ?></option>
                                </select>
                            </div>
                            <div class="wpc-col">
                                <label for="wpc_page_id"><?php esc_html_e('Pick a page', 'tsu-wpconnect-theme'); ?></label>
                                <select id="wpc_page_id" name="wpc_page_id">
                                    <?php echo WPCUtilities::wpc_ddoptions(); ?>
                                </select>   
                            </div>
                            <div class="wpc-col">
                                <label for="wpc_post_id"><?php esc_html_e('Pick a post', 'tsu-wpconnect-theme'); ?></label>
                                <select id="wpc_post_id" name="wpc_post_id">
                                    <?php echo WPCUtilities::wpc_ddoptions('POSTS'); ?>
                                </select>   
                            </div>
                            <div class="wpc-col">
                                <label for="wpc_cat_id"><?php esc_html_e('Pick a category', 'tsu-wpconnect-theme'); ?></label>
                                <select id="wpc_cat_id" name="wpc_cat_id">
                                    <?php echo WPCUtilities::wpc_ddoptions('CATEGORIES'); ?>
                                </select>    
                            </div>
                            <div class="wpc-col">
                                <label for="wpc_tag_id"><?php esc_html_e('Pick a tag', 'tsu-wpconnect-theme'); ?></label>
                                <select id="wpc_tag_id" name="wpc_tag_id">
                                    <?php echo WPCUtilities::wpc_ddoptions('TAGS'); ?>
                                </select>      
                            </div>
                        </div>                        
                        <p><?php esc_html_e( 'Add items to menu. These can be pages, blog posts or custom links', 'tsu-wpconnect-theme' ); ?></p>
                    </div>
                    <div class="wpc-modal-foot">
                        <button type="button" id="wpc_submit_menu_lvl2" class="button button-primary wpc-submit-menu"><?php esc_html_e('Create & close', 'tsu-wpconnect-theme'); ?></button>
                        <button type="button" class="button button-secondary wpc-close"><?php esc_html_e('Cancel', 'tsu-wpconnect-theme'); ?></button>
                    </div>
                </div>
            <?php
        }        
    }
}
