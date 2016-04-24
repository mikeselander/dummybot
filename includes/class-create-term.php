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
	 * @param string $slug a custom post type ID.
	 * @param boolean $echo Whether or not to echo. Optional.
	 * @param int $num Optional. Number of posts to create.
	 */
	public function create_terms( $slug, $echo = false, $num = '' ){

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
		$title = TestContent::title();

		$return = wp_insert_term(
			$title,
			$slug,
			array(
				'description'=> TestContent::title(),
				'slug' => sanitize_title( $title ),
			)
		);

		// Then, set a test content flag on the new post for later deletion
		add_term_meta( $return['term_id'], 'evans_test_content', '__test__', true );

		// Check if we have errors and return them or created message
		if ( is_wp_error( $return ) ){
			error_log( $return->get_error_message() )''
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

}
