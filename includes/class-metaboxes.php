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
		$cmb2_fields = $cmb_fields = $acf_fields = array();

		// CMB2
		if ( class_exists( 'CMB2', false ) ) {
			$cmb2_fields = $this->get_cmb2_metaboxes( $slug );
		}

		// Custom Metaboxes and Fields (CMB1)
		if ( class_exists( 'cmb_Meta_Box', false ) ) {
			$cmb_fields = $this->get_cmb1_metaboxes( $slug );
		}

		// Advanced Custom Fields (ACF Free)
		if ( class_exists( 'acf', false ) ) {
			$acf_fields = $this->get_acf_free_metaboxes( $slug );
		}

		// Return our array
		return array_merge( $cmb2_fields, $cmb_fields, $acf_fields );

	}


	/**
	 * Gets the metaboxes assigned to a custom post type in ACF.
	 *
	 * @access private
	 *
	 * @see get_all_acf_field_groups, is_acf_field_in_post_type
	 *
	 * @param string $slug Post type.
	 * @return array Fields array.
	 */
	private function get_acf_free_metaboxes( $slug ){

		$fields = array();

		// This damn plugin. Is. A. Freaking. Nightmare.
		$fieldsets = $this->get_all_acf_field_groups();

		// Return empty array if there are no fieldsets at all
		if ( empty( $fieldsets ) ){
			return $fields;
		}

		// Loop through each fieldset for possible matches
		foreach ( $fieldsets as $fieldset ){

			if ( $this->is_acf_field_in_post_type( $slug, $fieldset ) ){

				// If this is the first group of fields, simply set the value
				// Else, merge this group with the previous one
				if ( empty( $fields ) ){
					$fields = $fieldset->fields;
				} else {
					$fields = array_merge( $fields, $fieldset->fields );
				}

			}

		}

		return $fields;

	}


	/**
	 * Check if a group of fields is in a custom post type.
	 *
	 * @access private
	 *
	 * @param string $slug Post type slug.
	 * @param object $fieldset Fieldset group.
	 * @return boolean Whether or not the grouping is assigned to the post type.
	 */
	private function is_acf_field_in_post_type( $slug, $fieldset ){

		// Make sure we have something to parse
		if ( empty( $fieldset ) ){
			return false;
		}

		// Loop through the rules to check for post type matches
		foreach ( $fieldset->rules as $rule ){
			if ( $rule['param'] === 'post_type' && $rule['value'] === $slug ){
				return true;
			}
		}

		// Everything passed, yay!
		return false;

	}


	/**
	 * Loop through and retrive all acf cpts and cmb.
	 *
	 * ACF stores their data in custom post types and unfortunately named cmbs.
	 * Therefore, we have to loop through all acfs and sort through the mish-mash
	 * of messy data and make something clean of it.
	 *
	 * @access private
	 *
	 * @return array All acf fieldsets.
	 */
	private function get_all_acf_field_groups(){
		$info = $rules = $fields = array();

		$args = array(
			'post_type'		=> 'acf',
			'posts_per_page'=> 500
		);

		$objects = new \WP_Query( $args );

		if ( $objects->have_posts() ) :
			while ( $objects->have_posts() ) : $objects->the_post();

				$data = get_metadata( 'post', get_the_id() );

				foreach ( $data['rule'] as $rule ){
					$rules[] = unserialize( $rule );
				}

				foreach ( $data as $key => $value ){
					if ( substr( $key, 0, 6 ) == 'field_' ) :
						$field_detail = unserialize( $value[0] );
						$fields[] = array(
							'key'	 => $field_detail['key'],
							'type'	 => $field_detail['type'],
							'name'	 => $field_detail['label'],
							'id'	 => $field_detail['name'],
							'source' =>'acf'
						);
					endif;
				}

				$info[] = (object) array(
					'rules'		=> $rules,
					'fields'	=> $fields
				);

			endwhile;
		endif;

		return $info;

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

		// Add extra, redundant meta. Because, why not have rows for the price of one?
		if ( isset( $cmb['source'] ) && $cmb['source'] === 'acf' ){
			add_post_meta( $post_id, '_' . $cmb['id'], $cmb['key'], true );
		}

	}

}
