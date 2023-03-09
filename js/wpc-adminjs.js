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
    //storage for selected menu
    var actualMenu = '';
    //current size of JSON string
    var curJSONSize = structureString.length;
    var progressBar = wpcProgressBar( curJSONSize, maxJSONSize );  
    
    //render the stuff - if not empty
    if ( structureString !== '' ) {
        $( '.wpc-no-menu' ).hide();
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
        $( '#wpc_menuJSON_stat' ).show();
        
        //set bar
        $( '#wpc_menuJSON_stat .wpc-menubar.full' ).css("width", progressBar.full + "%");
        $( '#wpc_menuJSON_stat .wpc-menubar.empty' ).css("width", progressBar.empty + "%");
        
        //hide all site parts used to add items and menus if json string length is exceeded
        if ( curJSONSize >= maxJSONSize ){
            $( '.wpc-create-menu, .wpc-item-add' ).hide();   
        }
        
    }
    
    console.log( '*** WPC menu structure string loaded (' + curJSONSize + ' of ' + maxJSONSize + ' chars): ' + ( structureString.length < 1 ? '- empty -' : structureString ) + ' ***' );
    
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
    $( '.wpc-create-menu' ).on( "click", function(e) {
        e.preventDefault();
        //create modal object
        if ( $( '#wpc_menu_modal' ).is(':visible') ) {
            $( '#wpc_menu_modal, #wpc_modal_lvl1' ).hide(); 
        } else {
            $( '#wpc_menu_modal, #wpc_modal_lvl1' ).show(); 
            $( '#wpc_modal_lvl2' ).hide(); 
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
        
        //allow only if we have chars left            
        if ( curJSONSize < maxJSONSize ) {

            //get id
            let submitID = $( this ).attr('id');

            //level 1 menu
            if( submitID === 'wpc_submit_menu_lvl1' ) {     
                structureString = menuStructure.addMenu( $( '#wpc_input_menu_lvl1' ).val() );
                console.log( '*** WPC: Menu added to structure ***' );
            }
            //level 2 menu item
            if( submitID === 'wpc_submit_menu_lvl2' ) {  
                //TODO: Add menu item
                let itemName = $( "#wpc_item_name" ).val();
                let itemType = $( "#wpc_item_type" ).val();

                let itemID = -1;
                //get correct selector value
                if ( itemType == 0 ){
                    itemID = $( "#wpc_page_id" ).val();
                } else if ( itemType == 1 ){
                    itemID = $( "#wpc_post_id" ).val();
                } else if ( itemType == 2 ){
                    itemID = $( "#wpc_cat_id" ).val();
                } else if ( itemType == 3 ){
                    itemID = $( "#wpc_tag_id" ).val();
                } else if ( itemType == 4 ){
                    itemID = $( "#wpc_clink_id" ).val();
                } 

                structureString = menuStructure.addItem( actualMenu, item = { 'name': itemName, 'type': itemType, 'id': itemID, 'order': 0 } );
            }
            //at the end, always hide modal and update structure string
            $( '#wpc_menu_modal' ).hide();
            $( '.wpc-no-menu' ).hide();
            $( '#wpc-menu-structure' ).val( structureString );

            //refresh structure and render anew
            $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
            //reset input
            $( '#wpc_input_menu_lvl1' ).val('');

            //set bar
            curJSONSize = structureString.length;    
            progressBar = wpcProgressBar( curJSONSize, maxJSONSize );
            $( '#wpc_menuJSON_stat .wpc-menubar.full' ).css("width", progressBar.full + "%");
            $( '#wpc_menuJSON_stat .wpc-menubar.empty' ).css("width", progressBar.empty + "%");
            
        } else {
            console.log( '*** WPC menu structure string maximum length exceeded (' + curJSONSize + ' of ' + maxJSONSize + ' chars). No more data can be added! ***' );
        }
        
    } );
    //removal - toplevel
    $( document ).on( "click", '.wpc-toplvl-menu .wpc-menu-label .wpc-remove', function() {
        //get node to remove
        let node = $( this ).parent().parent().attr('id');

        //remove from input string
        structureString = menuStructure.removeMenu( node );
        //update input field
        $( '#wpc-menu-structure' ).val( structureString );
        //visually remove menu from ui
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
        
        //set bar
        curJSONSize = structureString.length;    
        progressBar = wpcProgressBar( curJSONSize, maxJSONSize );
        $( '#wpc_menuJSON_stat .wpc-menubar.full' ).css("width", progressBar.full + "%");
        $( '#wpc_menuJSON_stat .wpc-menubar.empty' ).css("width", progressBar.empty + "%");
        
        //show add menu / item if reqs met
        if ( curJSONSize < maxJSONSize ){
            $( '.wpc-create-menu, .wpc-item-add' ).show();   
        }        
        
    });
    //removal - item
    $( document ).on( "click", '.wpc-toplvl-menu .wpc-items .wpc-remove', function() {
        //get node to remove
        let menu = $( this ).parent().parent().parent().parent().attr('id');
        let item = $( this ).parent().attr('id');
        //remove from input string
        structureString = menuStructure.removeItem( menu, item );
        //update input field
        $( '#wpc-menu-structure' ).val( structureString );
        //visually remove menu from ui
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );
        
        //set bar
        curJSONSize = structureString.length;    
        progressBar = wpcProgressBar( curJSONSize, maxJSONSize );
        $( '#wpc_menuJSON_stat .wpc-menubar.full' ).css("width", progressBar.full + "%");
        $( '#wpc_menuJSON_stat .wpc-menubar.empty' ).css("width", progressBar.empty + "%");  
        
        //show add menu / item if reqs met
        if ( curJSONSize < maxJSONSize ){
            $( '.wpc-create-menu, .wpc-item-add' ).show();   
        }          
    });
    //appending items: Display the modal
    $( document ).on( "click", '.wpc-toplvl-menu .wpc-menu-item-add', function(e) {
        
        e.preventDefault();
        
        actualMenu = $( this ).parent().parent().parent().attr('id');
        
        if ( $( '#wpc_menu_modal' ).is(':visible') ) {
            $( '#wpc_menu_modal, #wpc_modal_lvl2' ).hide(); 
        } else {
            $( '#wpc_menu_modal, #wpc_modal_lvl2' ).show(); 
            $( '#wpc_modal_lvl1' ).hide(); 
        }        
    });
    //selecting menu item type in modal
    $( document ).on( "change", '#wpc_item_type', function() {
        let itemType = this.value;
        if( itemType === '0' ) {
            $( '#wpc_select_page' ).show();
            $( '#wpc_select_post, #wpc_select_cat, #wpc_select_tag, #wpc_input_link' ).hide();
        } else if ( itemType === '1' ) {
            $( '#wpc_select_post' ).show();
            $( '#wpc_select_cat, #wpc_select_tag, #wpc_input_link, #wpc_select_page' ).hide();            
        } else if ( itemType === '2' ) {
            $( '#wpc_select_cat' ).show();
            $( '#wpc_select_tag, #wpc_input_link, #wpc_select_page, #wpc_select_post' ).hide();            
        } else if ( itemType === '3' ) {
            $( '#wpc_select_tag' ).show();
            $( '#wpc_input_link, #wpc_select_page, #wpc_select_post, #wpc_select_cat' ).hide();            
        } else if ( itemType === '4' ) {
            $( '#wpc_input_link' ).show();
            $( '#wpc_select_page, #wpc_select_post, #wpc_select_cat, #wpc_select_tag' ).hide();            
        }
    });
    //sorting items up and down
    $( document ).on( "click", '.wpc-item .wpc-item-up, .wpc-item .wpc-item-down', function() { 
        
        //get item id
        let itemID = $( this ).parent().parent().attr('id');
        let menuID = $( this ).parent().parent().parent().parent().parent().attr('id');
        
        //check if we should move up or down
        if ( $( this ).hasClass('wpc-item-up') ) {
            structureString = menuStructure.shiftItem( itemID, menuID, 'UP' ); 
        } else if ( $( this ).hasClass('wpc-item-down') ) {
            structureString = menuStructure.shiftItem( itemID, menuID, 'DOWN' );
            
        } else {
            //something went wrong
            console.log( '*** WPC ERROR: Menu item ' + itemID + ' could not be moved in menu ' + menuID + '! ***' );
        }
        //update input field
        $( '#wpc-menu-structure' ).val( structureString );
        //rerender
        $( '#wpc_menu_structure_container' ).html( menuStructure.renderStructure() );        
    });
    //edit menu/item names
    $( document ).on( "click", '.wpc-menu-edit, .wpc-item-edit', function() {
        
        let parentID = '';
        let nameVal = $( this ).parent().children('.wpc-name').text();
        
        //hide name container and create edit box wpc-edit-name
        $('.wpc-name').show();
        $('.wpc-edit-name').hide();
        $( this ).parent().children('.wpc-name').hide();
        $( this ).parent().children('.wpc-edit-name').show().focus();
        
        if ( $( this ).hasClass('wpc-menu-edit') ) {
            parentID = $( this ).parent().parent().parent().attr('id');
            
        } else if ( $( this ).hasClass('wpc-item-edit') ) {
            parentID = $( this ).parent().parent().attr('id');
        }       
        console.log( 'Parent: ' + parentID + ', Name: ' + nameVal );
        
    });
});

//separate this in production
function wpcProgressBar ( currentValue, maxValue ) {
    let barFull = currentValue / ( maxValue / 100 );
    let barEmpty = 100 - barFull;  
    
    return { full: barFull, empty: barEmpty};   
};

//separate this in production
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
    
    removeItem = ( menuName, itemName ) => {  
        
        //get item position and reorder after deletion
        let itemPos = this.innerStruct[ menuName ][ itemName ].order;
        
        delete this.innerStruct[ menuName ][ itemName ];
        
        //reorder
        Object.keys( this.innerStruct[ menuName ] ).forEach( ( key ) => { 
            if ( key !== 'name' ) { 
                if ( this.innerStruct[ menuName ][ key ].order > itemPos ) {
                    this.innerStruct[ menuName ][ key ].order -= 1;
                }
            }
        });        
          
        return JSON.stringify( this.innerStruct ); 
    }
    
    addItem = ( menuName, item = { 'name': '', 'type': 0, 'id': -1, 'order': 0 } ) => {  
        //count items and append order last
        let appendOrder = 0;
        Object.keys( this.innerStruct[ menuName ] ).forEach( ( key ) => { 
            if ( key !== 'name' ) { 
                appendOrder += 1;
            }
        });
        item.order = appendOrder;        
        //type always valid, so we have to check for id and base menu
        if ( menuName !== '' && item.id != -1 && item.name !== '' ) {      
            //check if item exists, if so, only change
            if ( this.innerStruct[ menuName ].hasOwnProperty('item-' + item.type + '-' + item.id + '-' + menuName) ){
                 this.innerStruct[ menuName ][ 'item-' + item.type + '-' + item.id + '-' + menuName ].name = item.name;
                 console.log( '*** WPC: Menu item ' + item.name + ' changed! ***' );
            } else {
                this.innerStruct[ menuName ][ 'item-' + item.type + '-' + item.id + '-' + menuName ] = item;
                console.log( '*** WPC: Menu item ' + item.name + ' appended to ' + menuName + ' ***' );
            }
            return JSON.stringify( this.innerStruct ); 
        } else {
            console.log( '*** WPC ERROR: Menu item NOT appended to ' + menuName + ' ***' );
            return JSON.stringify( this.innerStruct );   
        }
    }
    //shift can be UP or DOWN
    shiftItem = ( itemName, menuName, shift = 'UP' ) => {
        
        //get item order property
        let curPos = this.innerStruct[ menuName ][ itemName ].order;
        let shiftPos = shift === 'UP' ? curPos - 1 : shift === 'DOWN' ? curPos + 1 : curPos;

        let shiftMax = this.innerStruct[ menuName ].hasOwnProperty('name') ? Object.keys( this.innerStruct[ menuName ] ).length - 1 : Object.keys( this.innerStruct[ menuName ] ).length;
        
        //only move if not zero or smaller and smaller than max num
        if( shiftPos >= 0 && shiftPos < shiftMax ){
            Object.keys( this.innerStruct[ menuName ] ).forEach( ( key, index ) => { 
                if ( key !== 'name' ) {
                    let itemPos = this.innerStruct[ menuName ][ key ].order;
                    if ( itemPos == shiftPos ) {
                        this.innerStruct[ menuName ][ key ].order = curPos;
                        this.innerStruct[ menuName ][ itemName ].order = shiftPos;
                    }
                }
            });            
        }
        return JSON.stringify( this.innerStruct );
    }
    
    //TODO: Write function
    editName = ( name, node ) => {
        
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
        
        Object.keys( orderedOutput ).forEach( (key) => {
            
            let name = orderedOutput[key].name === undefined || orderedOutput[key].name === '' ? key : orderedOutput[key].name + ' (' + key + ')';
            
            htmlStr += `<div class="wpc-toplvl-menu" id="${ key }">
                            <div class="wpc-menu-label">
                                <span class="wpc_menu-name">
                                    <span class="wpc-menu-edit">${ this.renderSVG( 'EDIT', { fill: '#fff', stroke: '#fff', strokeWidth: '1', scale: '10' } ) }</span>
                                    <span class="wpc-name">${ name }</span>
                                    <input type="text" class="wpc-edit-name" maxlength="50" name="wpc_item_name" value="${ orderedOutput[key].name === undefined || orderedOutput[key].name === '' ? '' : orderedOutput[key].name }" style="display:none" data-item-id="${ key }">
                                </span>
                                <span class="wpc-remove">&times;</span>
                            </div>
                            <div class="wpc-menu-body"> 
                                <div class="wpc-items">
                                    ${ this.renderItems( orderedOutput[key] ) }
                                </div>
                            </div>
                            <div class="wpc-menu-bottom">           
                                <div class="wpc-item-add">
                                    <a href="#" class="wpc-menu-item-add">${ this.renderSVG() }</a>
                                </div>
                            </div>
                        </div>
                        `;
        });
        
        return htmlStr;
    }
    
    renderItems = ( object ) => {
  
        let htmlArr = [];
        
        Object.keys( object ).forEach( ( key ) => {   
            if ( key !== 'name' ) {
                let posKey = object[key].order;
                htmlArr[posKey] = `
                            <div class="wpc-item" id="${ key }" data-item-order="${ object[key].order }">
                                <span class="wpc-item-name"><span class="wpc-item-up">&#x2B06;</span>
                                <span class="wpc-item-down">&#x2B07;</span> 
                                <span class="wpc-item-edit">${ this.renderSVG( 'EDIT', { fill: '#3c434a', stroke: '#3c434a', strokeWidth: '1', scale: '10' } ) }</span>
                                <input type="text" class="wpc-edit-name" maxlength="50" name="wpc_item_name" value="${ object[key].name }" style="display:none" data-item-id="${ key }">
                                <span class="wpc-name">${ object[key].name }</span> ${ object[key].type == 4 ? '<span class="wpc-link-text">| <a target="_blank" rel="noopener noreferrer" href="//' + this.cleanLink( object[key].id ) + '">' + object[key].id + '</a></span>' : '' }</span>
                                ${ this.renderItemIcon( object[key].type, object[key].id ) }
                                <span class="wpc-remove">&times;</span>
                            </div>           
                        `; 
            }    
        });
        
        return htmlArr.join('');
    }  
    
    cleanLink = ( url ) => {
        //remove protocol if there is
        let cleanURI = url.replace(/(^\w+:|^)\/\//, '');      
        //check if is link
        if ( cleanURI.lastIndexOf('.') === -1 ){
            return '';
        } else {
            return cleanURI;
        }
        
    }
    
    renderItemIcon = ( itemType, itemID ) => {
        
        let itemPrefs = 'undef';
        
        if ( itemType == 0 ){
            itemPrefs = 'Page';
        } else if ( itemType == 1 ){
            itemPrefs = 'Post';
        } else if ( itemType == 2 ){
            itemPrefs = 'Category';
        } else if ( itemType == 3 ){
            itemPrefs = 'Tag';
        } else if ( itemType == 4 ){
            itemPrefs = 'Link';
        }
        
        return `<span class="wpc-item-type wpc-item-${ itemPrefs.toLowerCase() }">
                    <span class="wpc-item-title">${ itemPrefs }</span>
                    ${ itemType == 4 ? '' : '<span class="wpc-item-id">' + itemID + '</span>' }
                </span>`;
               
    }
    
    
    renderSVG = ( type = 'PLUS', prefs = { fill: '#8c8f94', stroke: '#8c8f94', strokeWidth: '1', scale: '30' } ) => { 
        
        let baseWidth = 30;
        let scaleFactor = prefs.scale / baseWidth;
        
        if (type === 'PLUS'){
            return `
                <svg height="${ prefs.scale }" width="${ prefs.scale }">
                    <g transform="scale(${ scaleFactor })">
                        <polygon points="0,12 12,12 12,0 17,0 17,12 30,12 30,17 17,17 17,30 12,30 12,17 0,17" style="fill:${ prefs.fill };stroke:${ prefs.stroke };stroke-width:${ prefs.strokeWidth }" />
                    </g>
                </svg>           
            `;
        } else if (type === 'EDIT'){
            return `
                <svg height="${ prefs.scale }" width="${ prefs.scale }">
                    <g transform="scale(${ scaleFactor })">
                        <polygon points="0,5 5,0 23,18 18,23 0,5" style="fill:${ prefs.fill };stroke:${ prefs.stroke };stroke-width:${ prefs.strokeWidth }" />
                        <polygon points="27,22 30,30 22,27 20,25 25,20" style="fill:${ prefs.fill };stroke:${ prefs.stroke };stroke-width:${ prefs.strokeWidth }" />
                    </g>
                </svg>           
            `;            
        }
        return false;
    }
    
};