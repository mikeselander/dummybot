<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Radio extends Abstracts\Data {
	protected function data( $field ) {
		if ( empty( $field['options'] ) ) {
			return;
		}

		// Grab a random item out of the array and return the key.
		$new_val = array_slice( $field['options'], rand( 0, count( $field['options'] ) ), 1 );
		return key( $new_val );
	}
}
