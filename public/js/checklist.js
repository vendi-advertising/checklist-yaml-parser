(function () {
    'use strict';                         //Force strict mode

    const

        MAGIC_CSS_CLASS_FOR_NA = 'na',
        MAGIC_CSS_CLASS_FOR_DONE = 'done',
        MAGIC_CSS_CLASS_FOR_NOTHING = '',

        getRowValue = ( item ) => {
            return Array.prototype.slice.call( document.querySelectorAll( `input[type=radio][name=${item.name}]` ) )
                        .reduce(
                            ( x, obj ) => {
                                if ( obj.checked ) {
                                    return obj.value === 'n/a' ? MAGIC_CSS_CLASS_FOR_NA : MAGIC_CSS_CLASS_FOR_DONE;
                                }

                                return x;
                            },
                            MAGIC_CSS_CLASS_FOR_NOTHING
                        );
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
                                        parent = item.closest( 'li' ),
                                        value = getRowValue( item )
                                    ;

                                    parent.classList.remove( MAGIC_CSS_CLASS_FOR_DONE );
                                    parent.classList.remove( MAGIC_CSS_CLASS_FOR_NA );
                                    parent.classList.add( value );
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
