(function () {
    'use strict';                         //Force strict mode

    const

        MAGIC_CSS_CLASS_FOR_NA = 'na',
        MAGIC_CSS_CLASS_FOR_DONE = 'done',
        MAGIC_CSS_CLASS_FOR_NOTHING = '',

        MAGIC_VALUE_FOR_NA = 'n/a',
        MAGIC_VALUE_FOR_DONE = 'done',

        getRowCssClass = ( item ) => {
            return Array.prototype.slice.call( document.querySelectorAll( `input[type=radio][name=${item.name}]` ) )
                        .reduce(
                            ( x, obj ) => {
                                if ( obj.checked ) {
                                    return obj.value === MAGIC_VALUE_FOR_NA ? MAGIC_CSS_CLASS_FOR_NA : MAGIC_CSS_CLASS_FOR_DONE;
                                }

                                return x;
                            },
                            MAGIC_CSS_CLASS_FOR_NOTHING
                        );
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
            document
                .querySelectorAll( 'input[type=radio]' )
                .forEach(
                    ( item ) => {
                        item
                            .addEventListener(
                                'change',
                                () => {
                                    const
                                        parentRow = item.closest( 'li' ),
                                        rowCssClass = getRowCssClass( item )
                                    ;

                                    parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_DONE );
                                    parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_NA );
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
        }
    ;

    init();
}());
