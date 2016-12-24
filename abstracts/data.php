<?php

namespace DummyBot\Abstracts;

abstract class Data {

	public static function get( $field, $method = '' ) {
	 	if ( ! empty( $method ) && ! is_callable( __CLASS__, $method ) ) {
			return;
		}

		if ( empty( $method ) ) {
			return $this->data( $field );
		}

		return $this->$method( $field );
	}

	abstract protected data( $field );
}
