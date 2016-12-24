<?php

namespace DummyBot\Provider;

use DummyBot\Abstracts;

class CMB1 extends Abstracts\Provider {

	public __construct() {
		$this->provider = 'cmb1';
	}

	public function is_active() {
		return ( class_exists( 'cmb_Meta_Box_field', false ) );
	}

	private function get_the_fields( $object_type ) {
		$fields = [];

		// Get all metaboxes from CMB2 library
		$all_metaboxes = apply_filters( 'cmb_meta_boxes', array() );

		// Loop through all possible sets of metaboxes
		foreach ( $all_metaboxes as $metabox_array ) {

			// If the custom post type ID matches this set of fields, set & stop
			if ( in_array( $object_type, $metabox_array['pages'] ) ) {

				$fields = array_merge( $fields, $metabox_array['fields'] );
			}
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
			'text_money'                       => 'Number',
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
