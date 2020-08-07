import Pusher from 'pusher-js/with-encryption';

export default function ( global ) {
    const

        document = global.document,

        MAGIC_CSS_CLASS_FOR_NA = 'na',
        MAGIC_CSS_CLASS_FOR_DONE = 'done',
        MAGIC_CSS_CLASS_FOR_NOPE = 'nope',
        MAGIC_CSS_CLASS_FOR_REMOTE_CHANGE = 'remote',

        MAGIC_CSS_CLASS_FOR_INFO = 'info',
        MAGIC_CSS_CLASS_FOR_NOTES = 'notes',

        MAGIC_VALUE_FOR_NA = 'na',
        MAGIC_VALUE_FOR_DONE = 'done',
        MAGIC_VALUE_FOR_NOPE = 'nope',

        MAGIC_ATTRIBUTE_SHOW_ID_NOTE_SELECTOR = 'data-show-hide-notes',
        MAGIC_ATTRIBUTE_SHOW_ID_NOTE_ID = 'data-item-notes-id',


        MAGIC_ATTRIBUTE_NAME_DATA_ROLE = 'data-role',
        MAGIC_ATTRIBUTE_NAME_DATA_TARGET = 'data-target',
        MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_TYPE = 'data-entity-type',
        MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_ID = 'data-entity-id',
        MAGIC_ATTRIBUTE_NAME_DATA_LAST_ENTRY_ID = 'data-last-entry-id',

        MAGIC_ATTRIBUTE_ROLE_VALUE_NEW_NOTE = 'new-note',
        MAGIC_ATTRIBUTE_ROLE_VALUE_MODAL_NEW_NOTE = 'new-note-modal',

        LEFT_BRACKET = '[',
        RIGHT_BRACKET = ']',
        CONTAINS = '~=',
        QUOTE = '"',

        LB = LEFT_BRACKET,
        RB = RIGHT_BRACKET,
        C = CONTAINS,
        Q = QUOTE,

        MAGIC_SELECTOR_NEW_NOTE_BUTTONS = LB + MAGIC_ATTRIBUTE_NAME_DATA_ROLE + C + Q + MAGIC_ATTRIBUTE_ROLE_VALUE_NEW_NOTE + Q + RB,
        MAGIC_SELECTOR_MODAL = LB + MAGIC_ATTRIBUTE_NAME_DATA_ROLE + C + Q + MAGIC_ATTRIBUTE_ROLE_VALUE_MODAL_NEW_NOTE + Q + RB,

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

        setRowValue = ( item, value, entryId, isRemoteChange, instanceId ) => {
            const
                parentRow = item.tagName === 'LI' ? item : item.closest( 'li' ),
                isDuplicate = window.VENDI_INSTANCE_ID === instanceId
            ;

            parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_DONE );
            parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_NA );
            parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_NOPE );
            parentRow.classList.remove( MAGIC_CSS_CLASS_FOR_REMOTE_CHANGE );
            parentRow.classList.add( value );
            parentRow.classList.toggle( MAGIC_CSS_CLASS_FOR_REMOTE_CHANGE, !isDuplicate && true === isRemoteChange );
            parentRow.setAttribute( MAGIC_ATTRIBUTE_NAME_DATA_LAST_ENTRY_ID, entryId )

            updatePercentage( item );
        },

        setupRadios = () => {

            // Handle the change event for all radio buttons (assumes there's no other
            // radio buttons except in sections).
            document
                .querySelectorAll( 'input[type=radio]' )
                .forEach(
                    ( radioButton ) => {
                        radioButton
                            .addEventListener(
                                'change',
                                ( evt ) => {
                                    const
                                        rowCssClass = getRowCssClass( radioButton ),
                                        isRemoteChange = evt.detail && evt.detail.source && 'remote' === evt.detail.source,
                                        entryId = isRemoteChange ? evt.detail.entryId : null,
                                        instanceId = isRemoteChange ? evt.detail.instanceId : null
                                    ;

                                    if ( !isRemoteChange ) {
                                        window
                                            .ajax
                                            .post(
                                                window.VENDI_CHECKLIST_UPDATE_URL,
                                                {
                                                    value: radioButton.value,
                                                    itemId: radioButton.name,
                                                    instanceId: window.VENDI_INSTANCE_ID,
                                                },
                                                ( responseText, x ) => {
                                                    if ( 200 !== x.status ) {
                                                        console.dir( x );
                                                        console.dir( responseText );
                                                        window.alert( 'An unknown error occurred. Call Chris.' );
                                                    }
                                                }
                                            );
                                    }

                                    setRowValue( radioButton, rowCssClass, entryId, isRemoteChange, instanceId );
                                }
                            )
                        ;

                        // Call this once to setup percentages on page load, too
                        updatePercentage( radioButton );
                    }
                )
            ;
        },

        handleNoteToggle = ( evt, forceOpen ) => {
            const
                obj = evt.currentTarget,
                selector = `[${MAGIC_ATTRIBUTE_SHOW_ID_NOTE_ID}="${obj.getAttribute( MAGIC_ATTRIBUTE_SHOW_ID_NOTE_SELECTOR )}"`
            ;

            document
                .querySelectorAll( selector )
                .forEach(
                    ( el ) => {
                        const
                            ch = el.clientHeight,
                            sh = el.scrollHeight,
                            isCollapsed = true === forceOpen ? true : !ch,
                            noHeightSet = !el.style.height
                        ;
                        el.style.height = (isCollapsed || noHeightSet ? sh : 0) + 'px';
                        if ( noHeightSet ) {
                            return handleNoteToggle( { currentTarget: obj }, true === forceOpen ? forceOpen : null );
                        }
                    }
                )
            ;
        },

        setupNotes = () => {
            document
                .querySelectorAll( `[${MAGIC_ATTRIBUTE_SHOW_ID_NOTE_SELECTOR}]` )
                .forEach(
                    ( el ) => {
                        el.addEventListener( 'click', handleNoteToggle );
                    }
                )
            ;
        },

        getModal = () => {
            return document.querySelector( MAGIC_SELECTOR_MODAL );
        },

        getOrCreateModal = () => {
            return getModal();
        },

        setupNewNoteLinks = () => {
            document
                .querySelectorAll( MAGIC_SELECTOR_NEW_NOTE_BUTTONS )
                .forEach(
                    ( button ) => {
                        button
                            .addEventListener(
                                'click',
                                ( evt ) => {
                                    evt.stopPropagation();
                                    const
                                        modal = getOrCreateModal(),
                                        itemIdField = modal.querySelector( '#item-id' )
                                    ;
                                    document.documentElement.classList.add( 'modal-visible' );
                                    itemIdField.value = button.getAttribute( MAGIC_ATTRIBUTE_NAME_DATA_TARGET );
                                }
                            )
                        ;
                    }
                )
            ;
        },

        setupModalClickListener = () => {
            const
                modal = document.querySelector( MAGIC_SELECTOR_MODAL ),
                modalContent = modal.firstElementChild,
                button = modal.querySelector( 'button' )
            ;

            document
                .addEventListener(
                    'click',
                    ( evt ) => {
                        if ( document.documentElement.classList.contains( 'modal-visible' ) ) {
                            if ( !modalContent.contains( evt.target ) ) {
                                document.documentElement.classList.remove( 'modal-visible' );
                            }
                        }
                    }
                )
            ;

            button
                .addEventListener(
                    'click',
                    ( evt ) => {

                        evt.preventDefault();

                        button.disabled = true;

                        const
                            item = modal.querySelector( '#item-id' ),
                            note = modal.querySelector( '#note-text' ),
                            itemId = item.value,
                            noteText = note.value
                        ;

                        window
                            .ajax
                            .post(
                                window.VENDI_CHECKLIST_NOTE_URL,
                                {
                                    noteText: noteText,
                                    itemId: itemId,
                                },
                                () => {
                                    item.value = '';
                                    note.value = '';
                                    document.documentElement.classList.remove( 'modal-visible' );
                                    button.disabled = false;
                                }
                            )
                        ;
                    }
                )
            ;
        },

        setupPusher = () => {
            Pusher.logToConsole = true;

            const
                me = window.VENDI_USER_ID,
                pusher = new Pusher(
                    window.VENDI_CHECKLIST_APP_KEY,
                    {
                        cluster: window.VENDI_CHECKLIST_CLUSTER,
                        authEndpoint: window.VENDI_CHECKLIST_AUTH_ENDPOINT,
                    }
                ),
                channel = pusher.subscribe( 'private-' + window.VENDI_CHECKLIST_ID )
            ;

            channel
                .bind(
                    window.VENDI_STATUS_CHANGE_EVENT,
                    ( data ) => {
                        const
                            realData = JSON.parse( data ),
                            checkListElement = document.querySelector( `[${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_TYPE}~=checklist][${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_ID}="${realData.checklist}"]` ),
                            itemElement = checkListElement.querySelector( `[${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_TYPE}~=item][${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_ID}="${realData.item}"]` ),
                            radioButton = itemElement.querySelector( `input[type=radio][value=${realData.entryValue}]` ),
                            customEvent = new CustomEvent( 'change', {
                                detail: {
                                    source: 'remote',
                                    entryId: realData.entryId,
                                    instanceId: realData.instanceId,
                                }
                            } )
                        ;
                        radioButton.checked = true;
                        radioButton.dispatchEvent( customEvent );
                    }
                )
            ;

            channel
                .bind(
                    window.VENDI_NEW_NOTE_EVENT,
                    ( data ) => {
                        const
                            realData = JSON.parse( data ),
                            checkListElement = document.querySelector( `[${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_TYPE}~=checklist][${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_ID}="${realData.checklist}"]` ),
                            itemElement = checkListElement.querySelector( `[${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_TYPE}~=item][${MAGIC_ATTRIBUTE_NAME_DATA_ENTITY_ID}="${realData.item}"]` ),
                            noteContainer = itemElement.querySelector( '[data-role="notes"]' ),
                            newNoteListItem = noteContainer.querySelector( '[data-role="new-note-item"]' ),
                            infoButton = itemElement.querySelector( `[data-show-hide-notes="${realData.item}"]` ),
                            li = document.createElement( 'li' )
                        ;
                        li.appendChild( document.createTextNode( realData.noteText ) );
                        li.setAttribute( 'data-entity-type', 'note' );
                        li.setAttribute( 'data-entity-id', realData.noteId );

                        noteContainer.insertBefore( li, newNoteListItem );
                        infoButton.setAttribute( 'data-note-count', realData.newNoteCountForItem )
                        infoButton.classList.add( 'with-notes' );

                        handleNoteToggle(
                            { currentTarget: infoButton },
                            true
                        );

                        console.dir( realData );
                    }
                )
            ;


        },

        load = () => {
            setupModalClickListener();
            setupRadios();
            setupNotes();
            setupNewNoteLinks();
            setupPusher();
        },

        //Kick everything off
        init = () => {
            document.addEventListener( 'DOMContentLoaded', load );
            new Pusher( window.VENDI_CHECKLIST_APP_KEY, { authEndpoint: window.VENDI_CHECKLIST_AUTH_ENDPOINT } );
        }
    ;

    init();
};
