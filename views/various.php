<?php
namespace testContent\Views;
use testContent\Abstracts as Abs;

/**
 * Generate view for other various test content action
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Old Town Media
 */
class Various extends Abs\View{

	protected $title	= 'Various';
	protected $type		= 'all';
	protected $priority	= 10;


	/**
	 * Our sections action block - button to create and delete.
	 *
	 * @access protected
	 *
	 * @return string HTML content.
	 */
	protected function actions_section(){
		$html = '';

		$html .= "<div class='test-data-cpt'>";

			$html .= "<h3>";

				$html .= "<span class='label'>" . __( 'Clean Site', 'otm-test-content' ) . "</span>";
				$html .= $this->build_button( 'delete', 'all', __( 'Delete All Test Data', 'otm-test-content' ) );

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
	protected function options_section( $html = '' ){
		return $html;
	}

}
