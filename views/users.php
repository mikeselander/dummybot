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
class Users extends Abs\View{

	protected $title	= 'Users';
	protected $type		= 'user';
	protected $priority	= 4;

	/**
	 * Our sections action block - button to create and delete.
	 *
	 * @access protected
	 *
	 * @return string HTML content.
	 */
	protected function actions_section(){
		$html = '';

		global $wp_roles;
	    $role_names = $wp_roles->get_names();
		$flipped = array_flip( $role_names );

		// Loop through all available roles
		$roles = get_editable_roles();

		foreach ( $roles as $role ) :

			$proper_name = $flipped[ $role['name'] ];

			$skipped_roles = array(
				'Administrator'
			);

			// Skip banned cpts
			if ( in_array( $role['name'], $skipped_roles ) ){
				continue;
			}

			$html .= "<div class='test-data-cpt'>";

				$html .= "<h3>";

					$html .= "<span class='label'>" . $role['name'] . "</span>";
					$html .= $this->build_button( 'create', $proper_name, __( 'Create Users', 'otm-test-content' ) );
					$html .= $this->build_button( 'delete', $proper_name, __( 'Delete Users', 'otm-test-content' ) );

				$html .= "</h3>";

			$html .= "</div>";

		endforeach;

		return $html;
	}

}
