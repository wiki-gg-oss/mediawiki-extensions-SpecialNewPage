mw.hook( 'htmlform.enhance' ).add( $root => {
	const
        complexWidget = mw.widgets.ComplexTitleInputWidget.static.infuse( $root.find( '#mw-input-wptitle' ) ),
        titleWidget = complexWidget.title,
        namespaceInput = complexWidget.namespace,
        submitButton = OO.ui.infuse( $root.find( '.mw-htmlform-submit' ) ),
        beforeSubmitText = document.createElement( 'p' );

    function updateSubmitText() {
        const namespaceId = namespaceInput.getValue(),
            title = mw.Title.makeTitle( namespaceId, titleWidget.getValue() );

        if ( !title ) {
            beforeSubmitText.style.display = 'none';
            submitButton.setDisabled( true );
            return;
        }

        const
            nsMsg = `extnewpage-newpagetext-${namespaceInput.getValue()}`,
            fallbackMsg = 'extnewpage-newpagetext-fallback',
            msg = mw.message(
                mw.messages.exists( nsMsg ) ? nsMsg : fallbackMsg,
                title.getPrefixedText()
            );
        beforeSubmitText.innerHTML = msg.parse();
        beforeSubmitText.style.display = 'block';
        submitButton.setDisabled( false );
    }

    titleWidget.on( 'change', updateSubmitText );
    namespaceInput.on( 'change', updateSubmitText );
    
    beforeSubmitText.style.display = 'none';
    $root.find( '.mw-htmlform-submit-buttons' ).before( beforeSubmitText );
} );
