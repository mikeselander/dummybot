<?php
namespace DummyBot\Abstracts;

/**
 * Class to generate a type for the admin page.
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Mike Selander
 */
abstract class Type {

	/**
	 * Registers the type with the rest of the plugin
	 */
	public function get_type( $object_type ) {
		return [
			'core_fields' => $this->get_core_fields( $object_type ),
			'meta_fields' => $this->get_meta_fields( $object_type ),
		];
	}

	abstract public function insert();

	abstract public function delete();

	abstract public function update();

	abstract public function get_core_fields();

	public function get_meta_fields( $object_type ) {
		$providers = $this->get_providers();
		$fields   = [];

		if ( empty( $providers ) ) {
			return [];
		}

		foreach ( $providers as $name => $instance ) {
			$fields = array_merge( $fields, $instance->get_fields( $this->type, $object_type ) );
		}

		return $fields;
	};

	public function get_providers() {
		$providers = Master::providers();
		$active    = [];

		// If we have no providers, bail now.
		if ( empty( $providers ) ) {
			return;
		}

		foreach ( $providers as $type => $class ) {
			if ( ! is_callable( [ $class, 'is_active' ) ) {
				continue;
			}

			$instance = new $class;
			if ( $instance->is_active() ) {
				$active[ $type ] => $class;
			}
		}

		return $active;
	}

}
