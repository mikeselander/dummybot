<?php
namespace testContent;

/**
 * Class for handling CMB data
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class MetaboxTypes{

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
							'extras' => (object) array(
								'chars'	 => $field_detail['maxlength'],
								'max'	 => $field_detail['max'],
								'min'	 => $field_detail['min'],
							),
							'source' =>'acf',
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

}
