<?php
namespace testContent;

/**
 * Handling Ajax return and data
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Ajax{

	/**
	 * reporting
	 * Reporting class instance.
	 *
	 * @var object
	 * @access private
	 */
	private $reporting;

	public function hooks(){

		$this->reporting = new Reporting;
		add_action( 'wp_ajax_handle_test_data', array( $this, 'handle_test_data_callback' ) );

	}

	/**
	 * Ajax callback function for triggering the creation & deletion of test data.
	 *
	 * @see wp_ajax filter, $this->add_menu_item, $this->creation_routing
	 */
	public function handle_test_data_callback() {

		$action		= $_REQUEST['todo'];
		$nonce		= $_REQUEST['nonce'];

		// Verify that we have a proper logged in user and it's the right person
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'handle-test-data' ) ){
			return;
		}

		if ( $action == 'delete' ){

			$this->deletion_routing( $_REQUEST );

		} elseif ( $action == 'create' ){

			$this->creation_routing( $_REQUEST );

		}

		die();

	}


	/**
	 * Choose which type of creation needs to be accomplished and route through
	 * the correct class.
	 */
	private function creation_routing( $data ){

		$type = 'testContent\Types\\' . ucwords( $data['type'] );
		$object = new $type();
		$return = $object->create_objects( $data['slug'], $data['connection'], true, 1 );

		$clean = $this->reporting->create_report( $return );

		echo $clean;

	}


	/**
	 * Choose which type of deletion needs to be accomplished and route through
	 * the correct method of Delete.
	 */
	private function deletion_routing( $data ){

		$delete_content = new Delete;

		if ( $data['type'] == 'all' ){

			$return = $delete_content->delete_all_test_data();

		} else {

			$return = $delete_content->delete_objects( $data );

		}

		$clean = $this->reporting->create_report( $return );

		echo $clean;

	}


}
