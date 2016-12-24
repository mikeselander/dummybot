<?php

namespace DummyBot\Abstracts;

abstract class Provider {

	private $provider;

	abstract public function is_active();

	public function get_fields( $type, $object_type ) {
		$fields = $this->get_the_fields( $object_type );

		return array_map( $fields, function( $field ){
			// Whitelist field type.
			if ( ! isset( $this->fields()[ $field['type'] ] ) ) {
				return;
			}

			$field['callable'] = $this->fields()[ $field['type'] ];
			$field['provider'] = $this->provider;

			return $field;
		} );
	};

	abstract public function get_the_fields( $object_type );

	abstract public function fields();

}
