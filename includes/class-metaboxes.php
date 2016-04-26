<?php
namespace testContent;

/**
 * Class for handling CMB data
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Metaboxes{

	/**
	 * Decide which cmb library to try and loop to get our metaboxes.
	 *
	 * Due to supporting multiple CMB libraries, we need to check which library
	 * is used on our site and then run the appropriate function. Currently
	 * supported libraries are CMB2 & Custom Metaboxes and Fields.
	 *
	 * @see get_cmb2_metaboxes, get_cmb1_metaboxes
	 *
	 * @param string $slug Post Type slug ID.
	 * @return array Fields to fill in for our post object.
	 */
	public function get_metaboxes( $slug ){

		$fields = array();

		// CMB2
		if ( class_exists( 'CMB2', false ) ) {
			$fields = $this->get_cmb2_metaboxes( $slug );
		}

		// Custom Metaboxes and Fields (CMB1)
		if ( class_exists( 'cmb_Meta_Box', false ) ) {
			$fields = $this->get_cmb1_metaboxes( $slug );
		}

		// Return our array
		return $fields;

	}


	/**
	 * Gets all CMB2 custom metaboxes associated with a post type.
	 *
	 * Loops through all custom metabox fields registered with CMB2 and
	 * looks through them for matches on the given post type ID. Returns a single
	 * array of all boxes associated with the post type.
	 *
	 * @access private
	 *
	 * @see cmb2_meta_boxes
	 *
	 * @param string $slug a custom post type ID.
	 * @return array Array of fields.
	 */
	private function get_cmb2_metaboxes( $slug ){

		$fields = array();

		// Get all metaboxes from CMB2 library
		$all_metaboxes = apply_filters( 'cmb2_meta_boxes', array() );

		// Loop through all possible sets of metaboxes added the old way
		foreach ( $all_metaboxes as $metabox_array ){

			// If the custom post type ID matches this set of fields, set & stop
			if ( in_array( $slug, $metabox_array['object_types'] ) ) {

				// If this is the first group of fields, simply set the value
				// Else, merge this group with the previous one
				if ( empty( $fields ) ){
					$fields = $metabox_array['fields'];
				} else {
					$fields = array_merge( $fields, $metabox_array['fields'] );
				}
			}

		}

		// Loop through all metaboxes added the new way
		foreach ( \CMB2_Boxes::get_all() as $cmb ) {

			// Create the default
			$match = false;

			// Establish correct cmb types
			if ( is_string( $cmb->meta_box['object_types'] ) ){
				if ( $cmb->meta_box['object_types'] == $slug ){
					$match = true;
				}
			} else {
				if ( in_array( $slug, $cmb->meta_box['object_types'] ) ){
					$match = true;
				}
			}

			if ( $match !== true ){
				continue;
			}

			if ( empty( $fields ) ){
				$fields = $cmb->meta_box['fields'];
			} else {
				$fields = array_merge( $fields, $cmb->meta_box['fields'] );
			}

		}

		return $fields;

	}


	/**
	 * Gets all CMB1 custom metaboxes associated with a post type.
	 *
	 * Loops through all custom metabox fields registered with CMB2 and
	 * looks through them for matches on the given post type ID. Returns a single
	 * array of all boxes associated with the post type.
	 *
	 * @access private
	 *
	 * @see cmb_meta_boxes
	 *
	 * @param string $slug a custom post type ID.
	 * @return array Array of fields.
	 */
	private function get_cmb1_metaboxes( $slug ){

		$fields = array();

		// Get all metaboxes from CMB2 library
		$all_metaboxes = apply_filters( 'cmb_meta_boxes', array() );

		// Loop through all possible sets of metaboxes
		foreach ( $all_metaboxes as $metabox_array ){

			// If the custom post type ID matches this set of fields, set & stop
			if ( in_array( $slug, $metabox_array['pages'] ) ) {

				// If this is the first group of fields, simply set the value
				// Else, merge this group with the previous one
				if ( empty( $fields ) ){
					$fields = $metabox_array['fields'];
				} else {
					$fields = array_merge( $fields, $metabox_array['fields'] );
				}
			}

		}

		return $fields;

	}


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

			case 'text_email':

				$value = TestContent::email();

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
		if ( !empty( $value ) && !is_wp_error( $value ) ){

			// Files must be treated separately - they use the attachment ID
			// & url of media for separate cmb values
			if ( $cmb['type'] != 'file' ){
				add_post_meta( $post_id, $cmb['id'], $value, true );
			} else {
				add_post_meta( $post_id, $cmb['id'].'_id', $value, true );
				add_post_meta( $post_id, $cmb['id'], wp_get_attachment_url( $value ), true );
			}

		// If we're dealing with a WP Error object, just return the message for debugging
		} elseif ( is_wp_error( $value ) ){
			error_log( $value->get_error_message() );
			return $value->get_error_message();
		}

	} // end random_metabox_content

}
