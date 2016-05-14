<?php
namespace testContent;

/**
 * Class to load hooks and set/get base plugin definitions.
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
	 * Set the plugin definitions.
	 *
	 * @param  object $definitions Information about the plugin
	 * @return object $this
	 */
	public function set_definitions( $definitions ) {

		$this->definitions = $definitions;
		return $this;

	}


	/**
	 * Register hook function.
	 *
	 * @param  object $provider Hook provider.
	 * @return object $this
	 */
	public function register_hooks( $provider ) {

		if ( method_exists( $provider, 'set_plugin' ) ) {
			$provider->set_plugin( $this );
		}

		$provider->hooks();
		return $this;

	}


	/**
	 * Register hook function.
	 *
	 * @param  object $provider Hook provider.
	 * @return object $this
	 */
	public function register_view( $provider ) {

		$provider->register_view();
		return $this;

	}


	/**
	 * Register hook function.
	 *
	 * @param  object $provider Hook provider.
	 * @return object $this
	 */
	public function register_type( $provider ) {

		$provider->register_type();
		return $this;

	}


	/**
	 * Load the textdomain for this plugin if translation is available
	 *
	 * @see load_plugin_textdomain
	 */
	public function load_textdomain() {
	    load_plugin_textdomain( 'otm-test-content', FALSE, basename( dirname( $this->definitions->file ) ) . '/languages/' );
	}

}
