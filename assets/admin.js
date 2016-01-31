jQuery(document).ready(function($) {

	jQuery( '.handle-test-data' ).on( 'click', function(){

		var todo = jQuery( this ).data( 'todo' ),
			cpt = jQuery( this ).data( 'cpt' );

		jQuery( this ).after( '<img src="<?php echo plugins_url( '../assets/images/loading.gif', __FILE__ ); ?>" class="loading-icon" style="height: 20px; margin-bottom: -4px; margin-left: 4px;">' );

		var data = {
			'action' : 'handle_test_data',
			'todo' : todo,
			'cptslug' : cpt,
			'nonce' : '<?php echo wp_create_nonce( 'handle-test-data' ); ?>'
		};

		if ( jQuery( this ).data( 'todo' ) == 'create' ){
			var count = Math.floor( ( Math.random() * 30 ) + 1 );
		} else {
			var count = 1;
		}

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

		jQuery( '.loading-icon' ).remove();

		if ( jQuery( this ).data( 'todo' ) == 'create' ){
			jQuery( '#status-updates' ).append( 'Creating ' + count + ' objects\n' );
		}

	});

});