<?php
namespace DummyPress\Views;
use DummyPress\Abstracts as Abs;

/**
 * Generate view for creating and deleting terms.
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Old Town Media
 */
class Terms extends Abs\View{

	public function __construct() {

		$this->title	= __( 'Terms', 'dummybot' );
		$this->type		= 'term';
		$this->priority	= 2;

	}


	/**
	 * Our sections action block - button to create and delete.
	 *
	 * @access protected
	 *
	 * @return string HTML content.
	 */
	protected function actions_section() {
		$html = '';

		$taxonomies = get_taxonomies();

		foreach ( $taxonomies as $tax ) :

			$skipped_taxonomies = array(
				'post_format',				// We shouldn't be making random post format classes
				'product_shipping_class',	// These aren't used visually and are therefore skipped
				'nav_menu',					// Menus are handled seperately of taxonomies
			);

			// Skip banned taxonomies
			if ( in_array( $tax, $skipped_taxonomies ) ) {
				continue;
			}

			$taxonomy = get_taxonomy( $tax );

			$html .= "<div class='test-data-cpt'>";

				$html .= "<h3>";

				$html .= "<span class='label'>" . esc_html( $taxonomy->labels->name ) . "</span>";

				$html .= $this->build_button( 'create', $tax, __( 'Create', 'dummybot' ) . " " . $taxonomy->labels->name );
				$html .= $this->build_button( 'delete', $tax, __( 'Delete', 'dummybot' ) . " " . $taxonomy->labels->name );

				$html .= "</h3>";

			$html .= "</div>";

		endforeach;

		return $html;
	}

}
