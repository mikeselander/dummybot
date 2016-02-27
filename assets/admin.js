jQuery(document).ready(function($) {

	// Add our onClick event to the buttons
	jQuery( '.handle-test-data' ).on( 'click', function(){

		var todo = jQuery( this ).data( 'todo' ),
			cpt = jQuery( this ).data( 'cpt' ),
			type = jQuery( this ).data( 'type' );

		// Setup data on our help
		var data = {
			'action' : 'handle_test_data',
			'todo' : todo,
			'cptslug' : cpt,
			'type' : type,
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

				var parsed = JSON.parse( response );

				if ( todo == 'create' ){
					jQuery( '#status-updates' ).append( 'Created ' + parsed.post_type + ' ' + parsed.pid + ': ' + parsed.link + '\n' );
				} else {

					count = parsed.length;

					for( i=0; i<count; i++ ){
						if ( parsed[i].type == 'deleted' ){
							jQuery( '#status-updates' ).append( 'Deleted ' + parsed[i].post_type + ' ' + parsed[i].pid + '\n' );
						} else {
							jQuery( '#status-updates' ).append( parsed[i].message + '\n' );
						}
					}

				}

			});

		}

		// Print data to the box
		if ( jQuery( this ).data( 'todo' ) == 'create' ){
			jQuery( '#status-updates' ).append( 'Creating ' + count + ' objects\n' );
		}

	});

});