<?php
namespace testContent\Types;
use testContent as Main;
use testContent\TestContent as TestContent;
use testContent\Delete as Delete;
use testContent\Abstracts as Abs;


/**
 * Class to build test data for custom post types.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class User extends Abs\Type{

	protected $type = 'user';

	/**
	 * Create test data posts.
	 *
	 * This is where the magic begins. We accept a cpt id (slug) and potntially
	 * a number of posts to create. We then fetch the supports & metaboxes
	 * for that cpt and feed them into a function to create each post individually.
	 *
	 * @access private
	 *
	 * @see $this->get_cpt_supports, $this->get_metaboxes, $this->create_test_object
	 *
	 * @param string $slug a custom post type ID.
	 * @param boolean $connection Whether or not we're connected to the Internet.
	 * @param boolean $echo Whether or not to echo. Optional.
	 * @param int $num Optional. Number of posts to create.
	 */
	public function create_objects( $slug, $connection, $echo = false, $num = '' ){

		// If we're missing a custom post type id - don't do anything
		if ( empty( $slug ) ){
			return;
		}

		// Set our connection status for the rest of the methods
		$this->connected = $connection;

		// If we forgot to put in a quantity, make one for us
		if ( empty( $num ) ){
			$num = rand( 5, 30 );
		}

		// Create test posts
		for( $i = 0; $i < $num; $i++ ){

			$return = $this->create_test_object( $slug );

			if ( $echo === true ){
				echo \json_encode( $return );
			}

		}

	}


	/**
	 * Creates the individual test data post.
	 *
	 * Create individual posts for testing with. Gathers basic information such
	 * as title, content, thumbnail, etc. and inserts them with the post. Also
	 * adds metaboxes if applicable .
	 *
	 * @access private
	 *
	 * @see TestContent, wp_insert_post, add_post_meta, update_post_meta, $this->random_metabox_content
	 *
	 * @param string $slug a custom post type ID.
	 * @param array $supports Features that the post type supports.
	 * @param array $supports All CMB2 metaboxes attached to the post type.
	 */
	private function create_test_object( $slug ){
		$return = '';

		if ( !is_user_logged_in() ){
			return false;
		}

		$name = apply_filters( "tc_{$slug}_user_name", TestContent::name() );

		// First, insert our post
		$userdata = array(
			'user_pass'			=> "tc_{$slug}_user_email", TestContent::title( 1 ),
			'user_login'		=> strtolower( $name['first'] . $name['last'] ),
			'user_email'		=> apply_filters( "tc_{$slug}_user_email", TestContent::email( true ) ),
			'display_name'		=> strtolower( $name['first'] . $name['last'] ),
			'first_name'		=> $name['first'],
			'last_name'			=> $name['last'],
			'description'		=> TestContent::title(),
			'user_registered'	=> date( 'Y-m-d H:i:s' ),
			'role'				=> $slug,
		);

		error_log( $slug );

		// Insert the user
		$user_id = wp_insert_user( apply_filters( "tc_{$slug}_user_arguments", $userdata ) );

		// Then, set a test content flag on the new post for later deletion
		add_user_meta( $user_id, 'evans_test_content', '__test__', true );

		// Check if we have errors and return them or created message
		if ( is_wp_error( $user_id ) ){
			error_log( $user_id->get_error_message() );
			return $user_id;
		} else {
			return array(
				'type'		=> 'created',
				'object'	=> 'user',
				'pid'		=> $user_id,
				'link_edit'	=> admin_url( '/user-edit.php?user_id=2' . $user_id ),
			);
		}

	}

	/**
	 * Delete test data posts.
	 *
	 * This function will search for all posts of a particular post type ($slug)
	 * and delete them all using a particular cmb flag that we set when creating
	 * the posts. Validates the user first.
	 *
	 * @see WP_Query, wp_delete_post
	 *
	 * @param string $slug a custom post type ID.
	 * @param boolean $echo Whether or not to echo the result
	 */
	public function delete( $slug, $echo = false ){

		$delete =  new Delete;

		// Make sure that the current user is logged in & has full permissions.
		if ( !$delete->user_can_delete() ){
			return;
		}

		// Check that $cptslg has a string.
		if ( empty( $slug ) ){
			return;
		}

		// Find our test data by the unique flag we set when we created the data
		$query = array(
			'post_type' 		=> $slug,
			'posts_per_page'	=> 500,
			'meta_query' 		=> array(
				array(
					'key'     => 'evans_test_content',
					'value'   => '__test__',
					'compare' => '=',
				),
			),
		);

		$objects = new \WP_Query( $query );

		if ( $objects->have_posts() ){

			$events = array();

			while ( $objects->have_posts() ) : $objects->the_post();

				// Find any media associated with the test post and delete it as well
				$this->delete_associated_media( get_the_id() );

				if ( $echo === true ){
					$events[] = array(
						'type'		=> 'deleted',
						'pid'		=> get_the_id(),
						'post_type'	=> get_post_type( get_the_id() ),
						'link'		=> ''
					);
				}

				// Force delete the post
				wp_delete_post( get_the_id(), true );

			endwhile;

			$obj = get_post_type_object( $slug );

			$events[] = array(
				'type'		=> 'general',
				'message'	=> __( 'Deleted', 'otm-test-content' ) . ' ' . $obj->labels->all_items
			);

			echo \json_encode( $events );

		}

	}

}
