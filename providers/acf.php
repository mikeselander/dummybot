<?php

namespace DummyBot\Provider;

use DummyBot\Abstracts;

class ACF extends Abstracts\Provider {

	public __construct() {
		$this->provider = 'acf';
	}

	public function is_active() {
		return ( class_exists( 'acf', false ) );
	}

	public function get_fields( $type, $object_type ) {

	};
}
