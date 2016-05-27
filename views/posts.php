<?php
namespace testContent\Views;
use testContent\Abstracts as Abs;

/**
 * Generate view for creating and deleting posts.
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Old Town Media
 */
class Posts extends Abs\View{

	public function __construct(){

		$this->title	= __( 'Posts', 'otm-test-content' );
		$this->type		= 'post';
		$this->priority	= 1;

	}

	/**
	 * Our sections action block - button to create and delete.
	 *
	 * @access protected
	 *
	 * @return string HTML content.
	 */
	protected function actions_section(){
		$html = '';

		// Loop through every post type available on the site
		$post_types = get_post_types( array( 'public' => true ), 'objects' );

		foreach ( $post_types as $post_type ) :

			$skipped_cpts = array(
				'attachment'
			);

			// Skip banned cpts
			if ( in_array( $post_type->name, $skipped_cpts ) ){
				continue;
			}

			$html .= "<div class='test-data-cpt'>";

				$html .= "<h3>";

					$html .= "<span class='label'>" . $post_type->labels->name . "</span>";
					$html .= $this->build_button( 'create', $post_type->name, __( 'Create Test Data', 'otm-test-content' ) );
					$html .= $this->build_button( 'delete', $post_type->name, __( 'Delete Test Data', 'otm-test-content' ) );

				$html .= "</h3>";

			$html .= "</div>";

		endforeach;

		return $html;
	}

}
