<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Color extends Abstracts\Data {
	protected function data( $field ) {
		return '#' . str_pad( dechex( mt_rand( 0, 0xFFFFFF ) ), 6, '0', STR_PAD_LEFT );
	}
}
