<?php
namespace testContent\Types;
use testContent\Abstracts as Abs;
use testContent\TestContent as TestContent;
use testContent\Delete as Delete;


/**
 * Class to build test data for terms.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Term extends Abs\Type{

	protected $type = 'term';

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
	 * @param boolean $echo Whether or not to echo. Optional.
	 * @param int $num Optional. Number of posts to create.
	 */
	public function create_objects( $slug, $connection, $echo = false, $num = '' ){

		// If we're missing a custom post type id - don't do anything
		if ( empty( $slug ) ){
			return;
		}

		// If we forgot to put in a quantity, make one for us
		if ( empty( $num ) ){
			$num = rand( 5, 30 );
		}

		// Create test terms
		for( $i = 0; $i < $num; $i++ ){

			$return = $this->create_test_object( $slug );

			if ( $echo === true ){
				echo \json_encode( $return );
			}

		}

	}


	/**
	 * Creates the individual test data object.
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
	 */
	private function create_test_object( $slug ){

		// Get a random title
		$title = apply_filters( "tc_{$slug}_term_title", TestContent::title() );

		$return = wp_insert_term(
			$title,
			$slug,
			apply_filters( "tc_{$slug}_term_arguments", array(
					'description'=> TestContent::title(),
					'slug' => sanitize_title( $title ),
				)
			)
		);

		// Then, set a test content flag on the new post for later deletion
		add_term_meta( $return['term_id'], 'evans_test_content', '__test__', true );

		// Check if we have errors and return them or created message
		if ( is_wp_error( $return ) ){
			error_log( $return->get_error_message() );
			return $return;
		} else {
			return array(
				'type'		=> 'created',
				'object'	=> 'term',
				'tid'		=> $return['term_id'],
				'taxonomy'	=> $slug,
				'link_edit'	=> admin_url( '/edit-tags.php?action=edit&taxonomy='.$slug.'&tag_ID='.$return['term_id'] ),
				'link_view'	=> get_term_link( $return['term_id'] )
			);
		}

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

		// Query for our terms
		$args = array(
		    'hide_empty' => false,
		    'meta_query' => array(
		        array(
		           'key'       => 'evans_test_content',
		           'value'     => '__test__',
		           'compare'   => '='
		        )
		    )
		);

		$terms = get_terms( $slug, $args );

		if ( !empty( $terms ) ){

			$events = array();

			foreach ( $terms as $term ){

				if ( $echo === true ){
					$events[] = array(
						'type'		=> 'deleted',
						'pid'		=> $term->term_id,
						'post_type'	=> $slug,
						'link'		=> ''
					);
				}

				// Delete our term
				wp_delete_term( $term->term_id, $slug );

			}

			$taxonomy = get_taxonomy( $slug );

			$events[] = array(
				'type'		=> 'general',
				'message'	=> __( 'Deleted', 'otm-test-content' ) . ' ' . $taxonomy->labels->name
			);

			echo \json_encode( $events );

		}

	}

}