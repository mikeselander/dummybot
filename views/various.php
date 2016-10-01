<?php
namespace DummyPress\Views;
use DummyPress\Abstracts as Abs;

/**
 * Generate view for other various test content action
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Mike Selander
 */
class Various extends Abs\View {

	public function __construct() {

		$this->title	= __( 'Various', 'dummybot' );
		$this->type		= 'all';
		$this->priority	= 10;

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

		$html .= "<div class='test-data-cpt'>";

			$html .= "<h3>";

				$html .= "<span class='label'>" . esc_html__( 'Clean Site', 'dummybot' ) . "</span>";
				$html .= $this->build_button( 'delete', 'all', __( 'Delete All Test Data', 'dummybot' ) );

			$html .= "</h3>";

		$html .= "</div>";

		return $html;
	}


	/**
	 * We don't need any options on this page, so returning it empty
	 *
	 * @access protected
	 *
	 * @param string $html Existing HTML content.
	 * @return string HTML section content.
	 */
	protected function options_section( $html = '' ) {
		return $html;
	}

}
