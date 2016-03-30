<?php
namespace testContent;

/**
 * Class to build test data for custom post types.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Delete{

	/**
	 * Delete test data posts.
	 *
	 * This function will search for all posts of a particular post type ($slug)
	 * and delete them all using a particular cmb flag that we set when creating
	 * the posts. Validates the user first.
	 *
	 * @access private
	 *
	 * @see WP_Query, wp_delete_post
	 *
	 * @param string $slug a custom post type ID.
	 */
	public function delete_posts( $slug, $echo = false ){

		// Check that $cptslg has a string.
		// Also make sure that the current user is logged in & has full permissions.
		if ( empty( $slug ) || !is_user_logged_in() || !current_user_can( 'delete_posts' ) ){
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


	/**
	 * Find and delete attachments associated with a post ID.
	 *
	 * This function finds each attachment that is associated with a post ID
	 * and deletes it completely from the site. This is to prevent leftover
	 * random images from sitting on the site forever.
	 *
	 * @access private
	 *
	 * @see get_attached_media, wp_delete_attachment
	 *
	 * @param int $pid a custom post type ID.
	 */
	private function delete_associated_media( $pid ){

		if ( !is_int( $pid ) ){
			return;
		}

		// Get our images
		$media = get_attached_media( 'image', $pid );

		if ( !empty( $media ) ){

			// Loop through the media & delete each one
			foreach ( $media as $attachment ){
				wp_delete_attachment( $attachment->ID, true );
			}

		}

	}

	/**
	 * Delete test data terms.
	 *
	 * This function will search for all terms of a particular taxonomy ($slug)
	 * and delete them all using a particular term_meta flag that we set when creating
	 * the posts. Validates the user first.
	 *
	 * @access private
	 *
	 * @see WP_Query, wp_delete_post
	 *
	 * @param string $slug a custom post type ID.
	 */
	public function delete_terms( $slug, $echo = false ){

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