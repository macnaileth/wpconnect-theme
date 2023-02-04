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
        
        if ( $level == 1 ){
            ?>
                <div class="wpc-modal-content">
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
        
    }
}
