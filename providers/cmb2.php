<?php

namespace DummyBot\Provider;

use DummyBot\Abstracts;

class CMB2 extends Abstracts\Provider {

	public __construct() {
		$this->provider = 'cmb2';
	}

	public function is_active() {
		return ( class_exists( 'CMB2', false ) );
	}

	public function get_the_fields( $object_type ) {
		return array_merge( $this->get_classic_fields( $object_type ), $this->get_new_fields( $object_type ) );
	}

	private function get_classic_fields( $object_type ) {
		$fields = [];

		// Get all metaboxes from CMB2 library
		$all_metaboxes = apply_filters( 'cmb2_meta_boxes', array() );

		// Loop through all possible sets of metaboxes added the old way
		foreach ( $all_metaboxes as $metabox_array ) {

			// If the custom post type ID matches this set of fields, set & stop
			if ( in_array( $object_type, $metabox_array['object_types'] ) ) {
				$fields = array_merge( $fields, $metabox_array['fields'] );
			}
		}

		return $fields;
	}

	private function get_new_fields( $object_type ) {
		$fields = [];

		// Loop through all metaboxes added the new way
		foreach ( \CMB2_Boxes::get_all() as $cmb ) {

			$types = ( is_string( $cmb->meta_box['object_types'] ) ) ? [ $cmb->meta_box['object_types'] ] : $cmb->meta_box['object_types'];

			if ( ! in_array( $object_type, $cmb->meta_box['object_types'] ) ) {
				continue;
			}

			$fields = array_merge( $fields, $cmb->meta_box['fields'] );
		}

		return $fields;
	}

	private function fields() {
		return [
			'text'                             => 'Text',
			'text_small'                       => [ 'Text', 'short' ],
			'text_medium'                      => 'Text',
			'text_email'                       => 'Email',
			'text_url'                         => 'URL',
			'text_money'                       => 'number',
			'textarea'                         => [ 'Text', 'paragraphs' ],
			'textarea_small'                   => [ 'Text', 'paragraphs' ],
			'textarea_code'                    => [ 'Text', 'code' ],
			'text_time'                        => [ 'Time', 'time' ],
			'select_timezone'                  => [ 'Time', 'timezone' ],
			'text_date'                        => [ 'Time', 'date' ],
			'text_date_timestamp'              => [ 'Time', 'unix' ],
			'text_datetime_timestamp'          => [ 'Time', 'datetime' ],
			'text_datetime_timestamp_timezone' => [ 'Time', 'datetimezone' ],
			'hidden'                           => 'Text',
			'colorpicker'                      => 'Color',
			'radio'                            => 'Radio',
			'radio_inline'                     => 'Radio',
			'taxonomy_radio'                   => 'Radio',
			'taxonomy_radio_inline'            => 'Radio',
			'select'                           => 'Radio',
			'taxonomy_select'                  => 'Radio',
			'checkbox'                         => 'Radio',
			'multicheck'                       => 'Checkboxes',
			'taxonomy_multicheck'              => 'Checkboxes',
			'taxonomy_multicheck_inline'       => 'Checkboxes',
			'wysiwyg'                          => 'WYSIWYG',
			'file'                             => [ 'Image', 'id' ],
			//'file_list'                        => ,
			'oembed'                           => 'OEmbed',
			//'group'                            => ,
		];
	}
}
