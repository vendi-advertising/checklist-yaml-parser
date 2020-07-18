(function () {
    'use strict';                         //Force strict mode

    const

        MAGIC_CSS_CLASS_FOR_NA = 'na',
        MAGIC_CSS_CLASS_FOR_DONE = 'done',
        MAGIC_CSS_CLASS_FOR_NOPE = 'nope',

        MAGIC_VALUE_FOR_NA = 'n/a',
        MAGIC_VALUE_FOR_DONE = 'done',
        MAGIC_VALUE_FOR_NOPE = 'nope',

        getRowCssClass = ( item ) => {

            const
                done = document.querySelector( `input[type=radio][name="${item.name}"][value="${MAGIC_VALUE_FOR_DONE}"]` ),
                na = document.querySelector( `input[type=radio][name="${item.name}"][value="${MAGIC_VALUE_FOR_NA}"]` )
            ;

            if ( done.checked ) {
                return MAGIC_CSS_CLASS_FOR_DONE;
            }

            if ( na.checked ) {
                return MAGIC_CSS_CLASS_FOR_NA;
            }

            return MAGIC_CSS_CLASS_FOR_NOPE;
        },

        updatePercentage = ( item ) => {
            const
                parentSection = item.closest( '.section' ),
                rows = parentSection.querySelectorAll( 'li' ),
                holdingCell = parentSection.querySelector( '[data-role~="percentage-complete"]' ),
                hashes = []
            ;

            let
                total = 0,
                done = 0,
                na = 0
            ;

            rows
                .forEach(
                    ( row ) => {

                        row
                            .querySelectorAll( 'input[type=radio]' )
                            .forEach(
                                ( input ) => {
                                    hashes.push( input.name )
                                    total++;
                                    if ( input.checked ) {
                                        switch ( input.value ) {
                                            case MAGIC_VALUE_FOR_NA:
                                                na++;
                                                break;
                                            case MAGIC_VALUE_FOR_DONE:
                                                done++;
                                                break;
                                        }
                                    }
                                }
                            )
                    }
                )
            ;

            total = [ ...new Set( hashes ) ].length;

            const
                percentage = done / (total - na),
                percentageText = !percentage ? '0%' : percentage.toLocaleString( 'en', {
                    style: 'percent',
                    minimumFractionDigits: 0
                } )
            ;

            holdingCell.innerHTML = '';
            holdingCell.append( document.createTextNode( percentageText ) );

        },

        load = () => {

            // Handle the change event for all radio buttons (assumes there's no other
            // radio buttons except in sections).
            document
                .querySelectorAll( 'input[type=radio]' )
                .forEach(
                    ( item ) => {
                        item
                            .addEventListener(
                                'change',
                                ( evt ) => {
                                    const
                                        parentRow = item.closest( 'li' ),
                                        rowCssClass = getRowCssClass( item )
                                    ;

                                    window
                                        .ajax
                                        .post(
                                            window.VENDI_CHECKLIST_UPDATE_URL,
                                            {
                                                value: item.value,
                                                itemId: item.name,
                                            }
                                        );

                                    console.dir( evt );

                                    parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_DONE );
                                    parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_NA );
                                    parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_NOPE );
                                    parentRow.classList.add( rowCssClass );

                                    updatePercentage( item );
                                }
                            )
                        ;
                    }
                )
            ;
        },

        //Kick everything off
        init = () => {
            document.addEventListener( 'DOMContentLoaded', load );
            new Pusher( window.VENDI_CHECKLIST_APP_KEY, { authEndpoint: window.VENDI_CHECKLIST_AUTH_ENDPOINT } );
        }
    ;

    init();
}());
