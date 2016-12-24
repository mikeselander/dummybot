<?php

namespace DummyBot\Provider;

use DummyBot\Abstracts;

class HMCMB extends CMB1 {

	public __construct() {
		$this->provider = 'hmcmb';
	}

	public function is_active() {
		return ( class_exists( 'CMB_Field', false ) );
	}

	private function fields() {
		return [
			'text'				=> 'Text',
			'text_small' 		=> [ 'Text', 'short' ],
			'text_url'			=> 'URL',
			'url'				=> 'URL',
			'radio'				=> 'Radio',
			'checkbox'			=> [ 'Checkbox', 'single' ],
			'file'				=> [ 'Image', 'id' ],
			'image' 			=> [ 'Image', 'id' ],
			'wysiwyg'			=> 'WYSIWYG',
			'textarea'			=> [ 'Text', 'paragraphs' ],
			'textarea_code'		=> [ 'Text', 'code' ],
			'select'			=> 'Radio',
			'taxonomy_select'	=> 'Radio',
			'post_select'		=> 'Radio',
			'date'				=> [ 'Time', 'date' ],
			'date_unix'			=> [ 'Time', 'unix' ],
			'datetime_unix'		=> [ 'Time', 'unix' ],
			'time'				=> [ 'Time', 'time' ],
			'colorpicker'		=> 'Color',
			//'group'				=> '',
			//'gmap'				=> '',
			'number'			=> 'Number',
		];
	}
}
