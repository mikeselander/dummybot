jQuery(document).ready(function($) {

	// Add our onClick event to the buttons
	jQuery( '.handle-test-data' ).on( 'click', function(){

		var todo = jQuery( this ).data( 'todo' ),
			cpt = jQuery( this ).data( 'cpt' );

		// Setup data on our help
		var data = {
			'action' : 'handle_test_data',
			'todo' : todo,
			'cptslug' : cpt,
			'nonce' : test_content.nonce
		};

		// If we're creating, and not deleting choose how many objects to create
		if ( jQuery( this ).data( 'todo' ) == 'create' ){
			var count = Math.floor( ( Math.random() * 30 ) + 1 );
		} else {
			var count = 1;
		}

		// Loop through our count
		for( var i=1; i<=count; i++ ){

			jQuery.post( ajaxurl, data, function(response) {}).done(function(response){

				if ( todo == 'create' ){
					countedResponse = count + '. ' + response;
				} else {
					countedResponse = response;
				}

				jQuery( '#status-updates' ).append( countedResponse );

			});

		}

		// Print data to the box
		if ( jQuery( this ).data( 'todo' ) == 'create' ){
			jQuery( '#status-updates' ).append( 'Creating ' + count + ' objects\n' );
		}

	});

});