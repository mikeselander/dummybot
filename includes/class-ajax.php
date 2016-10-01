<?php
namespace DummyPress;

/**
 * Handling Ajax return and data
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Mike Selander
 */
class Ajax {

	/**
	 * reporting
	 * Reporting class instance.
	 *
	 * @var object
	 * @access private
	 */
	private $reporting;

	/**
	 * plugin
	 * Plugin class instance.
	 *
	 * @var object
	 */
	private $plugin;

	/**
	 * action
	 * Name of the action we want to use in our AJAX calls
	 *
	 * @var string
	 */
	private $action;


	/**
	 * Instantiate any WP hooks that need to be fired.
	 */
	public function hooks() {

		$this->reporting    = new Reporting;
		$this->action       = 'handle_test_data';

		add_action( "wp_ajax_{$this->action}", array( $this, 'handle_ajax' ) );
		add_filter( 'option_active_plugins', array( $this, 'ajax_exclude_plugins' ) );

	}


	/**
	 * Set a reference to the main plugin instance.
	 *
	 * @param $plugin Plugin instance.
	 * @return Ajax instance
	 */
	public function set_plugin( $plugin ) {

		$this->plugin = $plugin;
		return $this;

	}


	/**
	 * Turn outside plugins off during our AJAX calls to speed everything up.
	 *
	 * Having a lot of plugins running slows down an AJAX request, this function
	 * turns all other plugins off temporarliy while the AJAX requests is running.
	 *
	 * https://deliciousbrains.com/excluding-wordpress-plugins-loading-specific-ajax-requests/
	 *
	 * @param array $plugins All active plugins.
	 * @return array Whitelisted plugins.
	 */
	public function ajax_exclude_plugins( $plugins ) {

		if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX || ! isset( $_POST['action'] ) || false === strpos( $_POST['action'], $this->action ) ) {
			return $plugins;
		}

		foreach( $plugins as $key => $plugin ) {

			if ( false !== strpos( $plugin, $this->plugin->definitions->slug ) ) {
				continue;
			}

			unset( $plugins[$key] );
		}

		return $plugins;

	}


	/**
	 * Ajax callback function for triggering the creation & deletion of test data.
	 *
	 * @see wp_ajax filter, $this->add_menu_item, $this->creation_routing
	 */
	public function handle_ajax() {

		$action		= $_REQUEST['todo'];
		$nonce		= $_REQUEST['nonce'];

		// Verify that we have a proper logged in user and it's the right person
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'handle-test-data' ) ) {
			return;
		}

		if ( $action == 'delete' ) {

			$this->deletion_routing( $_REQUEST );

		} elseif ( $action == 'create' ) {

			$this->creation_routing( $_REQUEST );

		}

		die();

	}


	/**
	 * Choose which type of creation needs to be accomplished and route through
	 * the correct class.
	 */
	private function creation_routing( $data ) {

		$type = 'DummyPress\Types\\' . ucwords( $data['type'] );
		$object = new $type();
		$return = $object->create_objects( $data['slug'], $data['connection'], true, 1 );

		$clean = $this->reporting->create_report( $return );

		echo $clean;

	}


	/**
	 * Choose which type of deletion needs to be accomplished and route through
	 * the correct method of Delete.
	 */
	private function deletion_routing( $data ) {

		$delete_content = new Delete;

		if ( $data['type'] == 'all' ) {

			$return = $delete_content->delete_all_test_data();

		} else {

			$return = $delete_content->delete_objects( $data );

		}

		$clean = $this->reporting->create_report( $return );

		echo $clean;

	}


}
