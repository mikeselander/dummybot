<?php
namespace testContent;

/**
 * Class to build test data for terms.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class CreateTerm{

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
	 * @param string $cptslug a custom post type ID.
	 * @param boolean $echo Whether or not to echo. Optional.
	 * @param int $num Optional. Number of posts to create.
	 */
	public function create_terms( $tax_slug, $echo = false, $num = '' ){

		// If we're missing a custom post type id - don't do anything
		if ( empty( $cptslug ) ){
			return;
		}

		// If we forgot to put in a quantity, make one for us
		if ( empty( $num ) ){
			$num = rand( 5, 30 );
		}

		// Create test posts
		for( $i = 0; $i < $num; $i++ ){

			$return = $this->create_test_object( $tax_slug );

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
	 * @param string $cptslug a custom post type ID.
	 * @param array $supports Features that the post type supports.
	 * @param array $supports All CMB2 metaboxes attached to the post type.
	 */
	private function create_test_object( $tax_slug ){
		$return = '';

		// Get a random title
		$title = TestContent::title();

		$return = wp_insert_term(
			$title,
			$tax_slug,
			array(
				'description'=> TestContent::title(),
				'slug' => sanitize_title( $title ),
			)
		);

		// Then, set a test content flag on the new post for later deletion
		add_term_meta( $return['term_id'], 'evans_test_content', '__test__', true );


		// Check if we have errors and return them or created message
		if ( is_wp_error( $return ) ){
			return $return;
		} else {
			return array(
				'type'		=> 'created',
				'tid'		=> $post_id,
				'taxonomy'	=> $tax_slug,
				'link'		=> admin_url( '/post.php?post='.$post_id.'&action=edit' )
			);
		}

	}

}