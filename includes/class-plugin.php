<?php
namespace testContent;

/**
 * Class to build test data for custom post types.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Plugin{

	/**
	 * Plugin definitions.
	 *
	 * @var object
	 */
	protected $definitions;


	/**
	 * Retrieve the plugin definitions from the main plugin directory.
	 *
	 * @return object Defitions
	 */
	public function get_definitions() {
		return $this->definitions;
	}


	/**
	 * Set the plugin basename.
	 *
	 * @param  string $basename Relative path from the main plugin directory.
	 * @return string
	 */
	public function set_definitions( $definitions ) {
		$this->definitions = $definitions;
		return $this;
	}


	/**
	 * Register hook function.
	 *
	 * @param  object $provider Hook provider.
	 * @return $this
	 */
	public function register_hooks( $provider ) {
		if ( method_exists( $provider, 'set_plugin' ) ) {
			$provider->set_plugin( $this );
		}

		$provider->hooks();
		return $this;
	}

}
