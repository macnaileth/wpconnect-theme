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
    
    //create new handler object
    const menuStructure = new wpcStructure( structureString );  
    
    //render the stuff - if not empty
    if ( structureString !== '' ) {
        $( '.wpc-no-menu' ).hide();
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
    }
    
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
    $( '.wpc-submit-menu' ).on( "click", function() {
   
        //level 1 menu
        if( $('.wpc-submit-menu').is('#wpc_submit_menu_lvl1') ) {     
            structureString = menuStructure.addMenu( $( '#wpc_input_menu_lvl1' ).val() );

            console.log( '*** WPC: Menu added to structure ***' );
        }
        //at the end, always hide modal and update structure string
        $( '#wpc_menu_modal' ).hide();
        $( '.wpc-no-menu' ).hide();
        $( '#wpc-menu-structure' ).val( structureString );
        
        //refresh structure and render anew
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
        //reset input
        $( '#wpc_input_menu_lvl1' ).val('');
        
    } );
    //removal - toplevel
    $( document ).on( "click", '.wpc-toplvl-menu .wpc-remove', function() {
        //get node to remove
        let node = $( this ).parent().parent().attr('id');

        //remove from input string
        structureString = menuStructure.removeMenu( node );
        //update input field
        $( '#wpc-menu-structure' ).val( structureString );
        //visually remove menu from ui
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
    });
});

class wpcStructure {

    constructor( structure ) {
        //create json object from retrieved string
        this.innerStruct = structure == null || structure === '' ? {} : JSON.parse(structure); 
    }
    
    addMenu = ( menuName ) => {
        //append node to structure json
        let counter = Object.keys(this.innerStruct).length;
        
        if ( counter === 0 ){
            this.innerStruct['menu-' + 0] = menuName === '' ? {} : { name: menuName };
        } else {
            for (let i = 0; i <= counter; i++) {
                //insert prop if needed, otherwise append
                if(!this.innerStruct.hasOwnProperty('menu-' + ( i ))){
                    this.innerStruct['menu-' + ( i )] = menuName === '' ? {} : { name: menuName };
                    break;
                }
            }
        }
        return JSON.stringify( this.innerStruct );   
    }
    
    removeMenu = ( menuName ) => {  
        delete this.innerStruct[ menuName ];
        return JSON.stringify( this.innerStruct ); 
    }
    
    renderStructure = () => {
        //display the structure on screen
        let htmlStr = '';
        
        //order before output
        const orderedOutput = Object.keys( this.innerStruct ).sort().reduce(
            (obj, key) => { 
                obj[key] = this.innerStruct[key]; 
                return obj;
                }, 
                {}
            );
        
        Object.keys( orderedOutput ).forEach( (key, index) => {
            
            let name = orderedOutput[key].name === undefined || orderedOutput[key].name === '' ? key : orderedOutput[key].name + ' (' + key + ')';
            
            htmlStr += `<div class="wpc-toplvl-menu" id="${ key }">
                            <div class="wpc-menu-label">
                                <span class="wpc_menu-name">${ name }</span>
                                <span class="wpc-remove">&times;</span>
                            </div>
                            <div class="wpc-menu-body">
                                <a class="wpc-menu-item-add">+</a>
                            </div>
                        </div>
                        `;
        });
        
        return htmlStr;
    }
    
};