jQuery(document).ready(function($) {

	function scrollToBottom( div ){
		div.scrollTop = div.scrollHeight;
	}

	// Add our onClick event to the buttons
	jQuery( '.handle-test-data' ).on( 'click', function(){

		var todo = jQuery( this ).data( 'todo' ),
			slug = jQuery( this ).data( 'slug' ),
			type = jQuery( this ).data( 'type' ),
			qty  = jQuery( '#quantity-adjustment' ).val();

		// Setup data on our help
		var data = {
			'action' : 'handle_test_data',
			'todo' : todo,
			'slug' : slug,
			'type' : type,
			'nonce' : test_content.nonce
		};

		// If we're creating, and not deleting choose how many objects to create
		if ( jQuery( this ).data( 'todo' ) == 'create' ){
			if ( qty != 0 ){
				var count = qty;
			} else {
				var count = Math.floor( ( Math.random() * 30 ) + 1 );
			}
		} else {
			var count = 1;
		}

		// Assign variable to handle AJAX numbering
		var innerCount = 1;

		// Loop through our count
		for( var i=1; i<=count; i++ ){

			jQuery.post(
				ajaxurl,
				data,
				function(response) {}).done(function(response){

				var parsed = JSON.parse( response );

				if ( todo == 'create' ){

					// Assemble different strings if post or term
					if ( parsed.object == 'post' ){
						var type = parsed.post_type,
							id = parsed.pid;
					} else if( parsed.object == 'term' ){
						var type = parsed.taxonomy,
							id = parsed.tid;
					}

					jQuery( '#status-updates' ).append( innerCount + ': Created ' + type + ' ' + id + ': ' + '<a href="' + parsed.link_edit + '">Edit</a> | ' + '<a href="' + parsed.link_view + '">View</a>\n' );

					// Re-up our number & scroll to bottom
					innerCount++;
					scrollToBottom( document.getElementById( 'status-updates' ) );

				} else {

					count = parsed.length;

					for( i=0; i<count; i++ ){
						if ( parsed[i].type == 'deleted' ){
							jQuery( '#status-updates' ).append( innerCount + ': Deleted ' + parsed[i].post_type + ' ' + parsed[i].pid + '\n' );
						} else {
							jQuery( '#status-updates' ).append( parsed[i].message + '\n' );
						}

						// Re-up our number & scroll to bottom
						innerCount++;
						scrollToBottom( document.getElementById( 'status-updates' ) );
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