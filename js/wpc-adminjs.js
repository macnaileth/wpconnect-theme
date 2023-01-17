/*
 * Part of the wpc theme for wordpress
 * see licence header in ig-the-source.php
*/
/* 
    Created on : 16.01.2023, 10:48:00
    Author     : marconagel
*/
jQuery(document).ready(function($){
    //initalize limited input fields
    $('.wpc-input-limited').each(function() {
        $( this ).closest('td').find( ".wpc-counter-num" ).text( $( this ).attr( "maxlength" ) - $( this ).val().length  );
    });
    //initalize color picker
    $( '.wpc-color-picker' ).wpColorPicker();
    //do counter
    $( ".wpc-input-limited" ).keyup(function() {
        $( this ).closest('td').find( ".wpc-counter-num" ).text( $( this ).attr( "maxlength" ) - $( this ).val().length  );
    });
});