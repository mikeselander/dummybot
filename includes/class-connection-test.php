<?php
namespace DummyPress;

/**
 * ConnectionTest
 *
 * A more intelligent check to see if we can connect to Splashbase or not.
 *
 * This class checks whether or not we can connect to the Internet, and
 * if we can, whether we can connect to Splashbase itself. This is used by
 * our admin notice function to check whether or not we should display a notice
 * to users warning them of issues with Splashbase.
 *
 * The purpose of this is to avoid useless bug-hunting when images don't work.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Mike Selander
 */
class ConnectionTest {

	/**
	 * Run all of our connection tests.
	 *
	 * @see check_admin_page, check_airplane_mode, check_internet, check_splashbase
	 *
	 * @return boolean Status of connection to Internet/Splashbase.
	 */
	public function test() {

		/*
		 * Make sure that we're looking at the correct admin page
		 */
		if ( ! $this->check_admin_page() ) {
			return;
		}

		/*
		 * Test #1 - Check for Airplane Mode plugin status
		 */
		if ( ! $this->check_airplane_mode() ) {
			return false;
		}

		/*
		 * Test #2 - Check Internet connection in general
		 */
		if ( ! $this->check_internet() ) {
			return false;
		}

		/*
		 * Test #3 - Check External URL itself (Splashbase here)
		 */
		if ( ! $this->check_external_url( 'http://www.splashbase.co/api/v1/images/' ) ) {
			return false;
		}

		// We've made it this far, looks like everything checks out OK!
		return true;

	}


	/**
	 * Check to make sure that we're only running this check in the correct place.
	 *
	 * We only want these (relatively)expensive checks to run on a single admin
	 * page, so we need to run some checks first and verify that we're on the
	 * right screen.
	 *
	 * @access private
	 *
	 * @global object $current_screen Current admin screen info.
	 *
	 * @return boolean Whether or not we're in the right place.
	 */
	private function check_admin_page() {
		global $current_screen;

		// Only run if we're in the admin page
		if ( ! is_admin() ) {
			return false;
		}

		// Get the current admin screen & verify that we're on the right one
		// before continuing.
		if ( isset ( $current_screen ) && 'tools_page_create-test-data' != $current_screen->base ) {
			return false;
		}

		$last_uri_bit = explode( '=', $_SERVER['REQUEST_URI'] );
		if ( 'create-test-data' != end( $last_uri_bit ) ) {
			return false;
		}

		return true;

	}


	/**
	 * Check if we have Airplane Mode and if it's turned on or not.
	 *
	 * @access private
	 *
	 * @see get_site_option
	 *
	 * @return boolean Connected or not.
	 */
	private function check_airplane_mode() {

		if ( class_exists( 'Airplane_Mode_Core' ) ) {
			// Is airplane mode active?
			$airplane_mode = get_site_option( 'airplane-mode' );

			if ( $airplane_mode === 'on' ) {
				return false;
			}
		}

		return true;

	}


	/**
	 * Attempt to open a socket to a popular, compact to check overall connectivity.
	 *
	 * @access private
	 *
	 * @see fsockopen, fsockclose
	 *
	 * @return boolean Connected or not.
	 */
	private function check_internet() {

		// Attempt to open a socket connection to Google
		$connected = @fsockopen( "www.google.com", 80 );

		if ( ! $connected ) {
			return false;
		}

		// Close out our 1st test
		fclose( $connected );

		return true;

	}


	/**
	 * Check an external API to see if it's reachable or not.
	 *
	 * @access private
	 *
	 * @see wp_remote_get
	 *
	 * @param string $url External URL to attempt to reach.
	 * @return boolean Connected or not.
	 */
	private function check_external_url( $url ) {

		$test_url = esc_url( $url );
		$response = wp_remote_get( $test_url );

		if ( ! is_array( $response ) ) {
			return false;
		}

		return true;

	}

}
