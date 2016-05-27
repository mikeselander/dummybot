<?php
namespace testContent;

/**
 * Class for handling CMB data
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class MetaboxValues{

	/**
	 * Assigns the proper testing data to a custom metabox.
	 *
	 * Swaps through the possible types of CMB2 supported fields and
	 * insert the appropriate data based on type & id.
	 * Some types are not yet supported due to low frequency of use.
	 *
	 * @see TestContent, add_post_meta
	 *
	 * @param int $post_id Single post ID.
	 * @param array $cmb custom metabox array from CMB2.
	 */
	public function random_metabox_content( $post_id, $cmb, $connected ){
		$value = '';

		// First check that our post ID & cmb array aren't empty
		if ( empty( $cmb ) || empty( $post_id ) ){
			return;
		}

		// Fetch the appropriate type of data and return
		switch( $cmb['type'] ){

			case 'text':
			case 'text_small':
			case 'text_medium':

				// If phone is in the id, fetch a phone #
				if ( stripos( $cmb['id'], 'phone' ) ){
					$value = TestContent::phone();

				// If email is in the id, fetch an email address
				} elseif ( stripos( $cmb['id'], 'email' ) ){
					$value = TestContent::email();

				// If time is in the id, fetch a time string
				} elseif ( stripos( $cmb['id'], 'time' ) ){
					$value = TestContent::time();

				// Otherwise, just a random text string
				} else {
					$value = TestContent::title( rand( 10, 50 ) );
				}

				break;

			case 'text_url':

				$value = TestContent::link();

				break;

			case 'text_email' :
			case 'email':

				$value = TestContent::email();

				break;

			case 'number' :

				$value = rand( 1, 10000000 );

				break;

			case 'text_time':

				$value = TestContent::time();

				break;

			case 'select_timezone':

				$value = TestContent::timezone();

				break;

			case 'text_date':

				$value = TestContent::date( 'm/d/Y' );

				break;

			case 'text_date_timestamp':
			case 'text_datetime_timestamp':

				$value = TestContent::date( 'U' );

				break;

			// case 'text_datetime_timestamp_timezone': break;

			case 'text_money':

				$value = rand( 0, 100000 );

				break;

			case 'test_colorpicker':

				$value = '#' . str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );

				break;

			case 'textarea':
			case 'textarea_small':
			case 'textarea_code':

				$value = TestContent::plain_text();

				break;

			case 'select':
			case 'radio_inline':
			case 'radio':

				// Grab a random item out of the array and return the key
				$new_val = array_slice( $cmb['options'], rand( 0, count( $cmb['options'] ) ), 1 );
				$value = key( $new_val );

				break;

			// case 'taxonomy_radio': break;
			// case 'taxonomy_select': break;
			// case 'taxonomy_multicheck': break;

			case 'checkbox':

				// 50/50 odds of being turned on
				if ( rand( 0, 1 ) == 1 ){
					$value = 'on';
				}

				break;

			case 'multicheck':

				$new_option = array();

				// Loop through each of our options
				foreach ( $cmb['options'] as $key => $value ){

					// 50/50 chance of being included
					if ( rand( 0, 1 ) ){
						$new_option[] = $key;
					}

				}

				$value = $new_option;

				break;

			case 'wysiwyg':

				$value = TestContent::paragraphs();

				break;

			case 'file':

				if ( true == $connected ){
					$value = TestContent::image( $post_id );
				}

				break;

			// case 'file_list': break;

			case 'oembed':

				$value = TestContent::oembed();

				break;

		}

		// Value must exist to attempt to insert
		if ( ! empty( $value ) && ! is_wp_error( $value ) ){

			$this->update_meta( $post_id, $value, $cmb );

		// If we're dealing with a WP Error object, just return the message for debugging
		} elseif ( is_wp_error( $value ) ){

			return $value->get_error_message();

		}

	} // end random_metabox_content


	/**
	 * Update the metabox with new data.
	 *
	 * @access private
	 *
	 * @see add_post_meta
	 *
	 * @param int $post_id Post ID.
	 * @param string $value Value to add into the database.
	 * @param array $cmb SMB data.
	 */
	private function update_meta( $post_id, $value, $cmb ){

		$type 	= $cmb['type'];
		$id		= $cmb['id'];
		$value = apply_filters( "tc_{$type}_metabox", $value );	// Filter by metabox type
		$value = apply_filters( "tc_{$id}_metabox", $value ); // Filter by metabox ID

		// Files must be treated separately - they use the attachment ID
		// & url of media for separate cmb values.
		if ( $cmb['type'] != 'file' ){
			add_post_meta( $post_id, $cmb['id'], $value, true );
		} else {
			add_post_meta( $post_id, $cmb['id'].'_id', $value, true );
			add_post_meta( $post_id, $cmb['id'], wp_get_attachment_url( $value ), true );
		}

		// Add extra, redundant meta. Because, why not have two rows for the price of one?
		if ( isset( $cmb['source'] ) && $cmb['source'] === 'acf' ){
			add_post_meta( $post_id, '_' . $cmb['id'], $cmb['key'], true );
		}

	}

}
