<?php
namespace testContent\Abstracts;

/**
 * Class to generate a type for the admin page.
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Old Town Media
 */
abstract class Type{

	/**
	 * type
	 * Type of objects we'll be dealing with i.e.: post or term.
	 *
	 * @var string
	 * @access protected
	 */
	protected $type;


	/**
	 * Registers the type with the rest of the plugin
	 */
	public function register_type(){

		add_action( 'tc_types', 'set_type' );

	}


	/**
	 * Sets the type in the type array for use by the rest of the plugin.
	 *
	 * @param array $types Original types array
	 * @return array Modified types array with our current type
	 */
	public function set_type( $types ){

		$types[] = $this->type;
		return $types;

	}

}
