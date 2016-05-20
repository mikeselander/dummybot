<?php
namespace testContent;
use testContent\Views\Users as Users;

/**
 * Class to handle deletion of test data for the plugin.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Delete{

	/**
	 * Delete all test content created ever.
	 *
	 * @access private
	 */
	public function delete_all_test_data(){
		$return = '';

		if ( ! $this->user_can_delete() ){
			return;
		}

		$types = apply_filters( 'tc-types', array() );

		if ( !empty( $types ) ){

			foreach ( $types as $type ){

				$class = 'testContent\Types\\' . ucwords( $type );
				$object = new $class();

				$return .= $object->delete_all();

			}

		}

		return $return;

	}

	/**
	 * Delete test data terms.
	 *
	 * This function will search for all terms of a particular taxonomy ($slug)
	 * and delete them all using a particular term_meta flag that we set when creating
	 * the posts. Validates the user first.
	 *
	 * @see WP_Query, wp_delete_post
	 *
	 * @param string $data Information about the type.
	 */
	public function delete_objects( $data ){

		// Make sure that the current user is logged in & has full permissions.
		if ( !$this->user_can_delete() ){
			return;
		}

		if ( empty( $data ) ){
			return;
		}

		$type = 'testContent\Types\\' . ucwords( $data['type'] );
		$slug = $data['slug'];

		$object = new $type();

		return $object->delete( $slug );

	}


	/**
	 * Run some checks to make sure that our user is allowed to delete data.
	 *
	 * @see is_user_logged_in, current_user_can
	 */
	public function user_can_delete(){

		// User must be logged in
		if ( !is_user_logged_in() ){
			return false;
		}

		// User must have editor priveledges, at a minimum
		if ( !current_user_can( 'delete_others_posts' ) ){
			return false;
		}

		// We passed all the checks, hooray!
		return true;

	}


}
