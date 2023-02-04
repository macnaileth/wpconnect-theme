/*
 * Part of the wpc theme for wordpress
 * see licence header in ig-the-source.php
*/
/* 
    Created on : 16.01.2023, 10:48:00
    Author     : marconagel
*/
jQuery(document).ready(function($){
    
    //load up needed var for menu creation
    var structureString = $( '#wpc-menu-structure' ).val();
    console.log( '*** WPC menu structure string loaded: ' + ( structureString.length < 1 ? '- empty -' : structureString ) + ' ***' );
    
    //initalize limited input fields
    $('.wpc-input-limited').each(function() {
        $( this ).closest('td, .wpc-input-group').find( ".wpc-counter-num" ).text( $( this ).attr( "maxlength" ) - $( this ).val().length  );
    });
    //initalize color picker
    $( '.wpc-color-picker' ).wpColorPicker();
    //do counter
    $( ".wpc-input-limited" ).keyup(function() {
        $( this ).closest('td, .wpc-input-group').find( ".wpc-counter-num" ).text( $( this ).attr( "maxlength" ) - $( this ).val().length  );
    });
    //switch tab menu
    $( '.wpc-tab-btn' ).click(function() {
        if( !$( this ).hasClass('active') ) {
            $( '.wpc-tab-btn' ).removeClass('active');
            $( this ).addClass('active');

            $( '.wpc-settings' ).removeClass('active');      
            $( $( this ).attr( 'data-target' ) ).addClass('active');
        }  
    });
    //menu structure stuff
    $( '.wpc-create-menu' ).on( "click", function() {
        //create modal object
        if ( $( '#wpc_menu_modal' ).is(':visible') ) {
            $( '#wpc_menu_modal' ).hide(); 
        } else {
            $( '#wpc_menu_modal' ).show(); 
        }
    } );
    //close
    $( document ).on( "click", '.wpc-modal, .wpc-close', function(e) {
        if( e.target === document.getElementById("wpc_menu_modal") || $(event.target).hasClass('wpc-close') ) {
            $( '#wpc_menu_modal' ).hide(); 
        }  
    });
    //submission
    $( '.wpc-submit-menu' ).on( "click", function(e) {
        //level 1 menu
        if( $('.wpc-submit-menu').is('#wpc_submit_menu_lvl1') ) {
            console.log('I am level 1 submit');
            
        }
        //at the end, always hide modal
        $( '#wpc_menu_modal' ).hide();
    } );
});