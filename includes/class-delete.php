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
	public function delete_all_test_data( $echo = false ){

		if ( !$this->user_can_delete() ){
			return;
		}

		// Loop through all post types and remove any test data
		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		foreach ( $post_types as $post_type ) :

			$data = array(
				'type'	=> 'post',
				'slug'	=> $post_type->name
			);

		    $this->delete_objects( $echo, $data );

		endforeach;

		// Loop through all taxonomies and remove any data
		$taxonomies = get_taxonomies();
		foreach ( $taxonomies as $tax ) :

			$data = array(
				'type'	=> 'term',
				'slug'	=> $tax
			);

		    $this->delete_objects( $echo, $data );

		endforeach;

		// Loop through all user roles and remove any data
		$users = new Users;
		foreach ( $users->get_roles() as $role ) :

			$data = array(
				'type'	=> 'user',
				'slug'	=> $role['slug']
			);

		    $this->delete_objects( $echo, $data );

		endforeach;

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
	 * @param string $slug a custom post type ID.
	 * @param boolean $echo Whether or not to echo the result
	 */
	public function delete_objects( $echo = false, $data ){

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

		$object->delete( $slug, $echo );

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
