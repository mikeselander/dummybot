<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Number extends Abstracts\Data {
	protected function data( $field ) {
		$min = 1;
		$max = 10000000;

		if ( 'acf' == $field['source'] && ! empty( $field['extras']->min ) ) {
			$min = $field['extras']->min;
		}

		if ( 'acf' == $field['source'] && ! empty( $field['extras']->max ) ) {
			$max = $field['extras']->max;
		}

		return mt_rand( $min, $max );
	}
}
