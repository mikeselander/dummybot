<?php
namespace testContent;

/**
 * Class to build test data for custom post types.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class CreatePost{

	/**
	 * metaboxes
	 * Easy access for the Metaboxes class.
	 *
	 * @var string
	 * @access private
	 */
	private $metaboxes;


	/**
	 * Constructor to load in the Metaboxes class.
	 *
	 * @see Metaboxes
	 */
	public function __construct(){

		$this->metaboxes = new Metaboxes;

	}

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
	public function create_post_type_content( $slug, $connection, $echo = false, $num = '' ){

		// If we're missing a custom post type id - don't do anything
		if ( empty( $slug ) ){
			return;
		}

		// Gather the necessary data to create the posts
		$supports 	= $this->get_cpt_supports( $slug );
		$metaboxes	= $this->metaboxes->get_metaboxes( $slug );

		// Set our connection status for the rest of the methods
		$this->connected = $connection;

		// If we forgot to put in a quantity, make one for us
		if ( empty( $num ) ){
			$num = rand( 5, 30 );
		}

		// Create test posts
		for( $i = 0; $i < $num; $i++ ){

			$return = $this->create_test_object( $slug, $supports, $metaboxes );

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
	private function create_test_object( $slug, $supports, $metaboxes ){
		$return = '';

		// Get a random title
		$title = TestContent::title();

		// First, insert our post
		$post = array(
		  'post_name'      => sanitize_title( $title ),
		  'post_status'    => 'publish',
		  'post_type'      => $slug,
		  'ping_status'    => 'closed',
		  'comment_status' => 'closed',
		);

		// Add title if supported
		if ( $supports['title'] === true ){
			$post['post_title'] = $title;
		}

		// Add main content if supported
		if ( $supports['editor'] === true ){
			$post['post_content'] = TestContent::paragraphs();
		}

		// Add excerpt content if supported
		if ( $supports['excerpt'] === true ){
			$post['post_excerpt'] = TestContent::plain_text();
		}

		// Insert then post object
		$post_id = wp_insert_post( $post );

		// Then, set a test content flag on the new post for later deletion
		add_post_meta( $post_id, 'evans_test_content', '__test__', true );

		// Add thumbnail if supported
		if ( $this->connected == true && ( $supports['thumbnail'] === true || in_array( $slug, array( 'post', 'page' ) ) ) ){
			 update_post_meta( $post_id, '_thumbnail_id', TestContent::image( $post_id ) );
		}

		$taxonomies = get_object_taxonomies( $slug );

		// Assign the post to terms
		if ( !empty( $taxonomies ) ){
			$return .= $this->assign_terms( $post_id, $taxonomies );
		}

		// Spin up metaboxes
		if ( !empty( $metaboxes ) ){
			foreach ( $metaboxes as $cmb ) :
				$return .= $this->metaboxes->random_metabox_content( $post_id, $cmb, $this->connected );
			endforeach;
		}

		// Check if we have errors and return them or created message
		if ( is_wp_error( $return ) ){
			error_log( $return->get_error_message() );
			return $return;
		} else {
			return array(
				'type'		=> 'created',
				'object'	=> 'post',
				'pid'		=> $post_id,
				'post_type'	=> get_post_type( $post_id ),
				'link_edit'	=> admin_url( '/post.php?post='.$post_id.'&action=edit' ),
				'link_view'	=> get_permalink( $post_id ),
			);
		}

	}


	/**
	 * Assemble supports statements for a particular post type.
	 *
	 * @access private
	 *
	 * @see post_type_supports
	 *
	 * @param string $slug a custom post type ID.
	 * @return array Array of necessary supports booleans.
	 */
	private function get_cpt_supports( $slug ){

		$supports = array(
			'title'		=> post_type_supports( $slug, 'title' ),
			'editor'	=> post_type_supports( $slug, 'editor' ),
			'excerpt'	=> post_type_supports( $slug, 'excerpt' ),
			'thumbnail'	=> post_type_supports( $slug, 'thumbnail' )
		);

		return $supports;

	}


	/**
	 * Assigns taxonomies to the new post.
	 *
	 * Loop through every taxonomy type associated with a custom post type &
	 * assign the post to a random item out of each taxonomy. Taxonomies must
	 * have at least one term in them for this to work.
	 *
	 * @access private
	 *
	 * @param int $post_id a custom post type ID.
	 * @param array $taxonomies taxonomies assigned to this cpt.
	 * @return object WP Error if there is one.
	 */
	private function assign_terms( $post_id, $taxonomies ){

		// Make sure it's an array & has items
		if ( empty( $taxonomies ) || !is_array( $taxonomies ) ){
			return;
		}

		foreach ( $taxonomies as $tax ){

			// Get the individual terms already existing
			$terms = get_terms( $tax, array( 'hide_empty'	=> false ) );
			$count = count( $terms ) - 1;

			// If there are no terms, skip to the next taxonomy
			if ( empty( $terms ) ){
				continue;
			}

			// Get a random index to use
			$index = rand( 0, $count );

			// Initialize our array
			$post_data = array(
				'ID'	=> $post_id
			);

			// Set the term data to update
			$post_data['tax_input'][ $tax ] = array( $terms[$index]->term_id );

			// Update the post with the taxonomy info
			$return = wp_update_post( $post_data );

			// Return the error if it exists
			if ( is_wp_error( $return ) ){
				error_log( $return->get_error_messages() );
				return $return->get_error_messages();
			}

		}

	}

}
