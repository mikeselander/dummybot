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

		$roles = $this->get_roles();

		foreach ( $roles as $role ) :

			$html .= "<div class='test-data-cpt'>";

				$html .= "<h3>";

					$html .= "<span class='label'>" . $role['name'] . "</span>";
					$html .= $this->build_button( 'create', $role['slug'], __( 'Create Users', 'otm-test-content' ) );
					$html .= $this->build_button( 'delete', $role['slug'], __( 'Delete Users', 'otm-test-content' ) );

				$html .= "</h3>";

			$html .= "</div>";

		endforeach;

		return $html;
	}


	/**
	 * Get all roles and set a cleaner array.
	 *
	 * @see get_editable_roles
	 *
	 * @global object $wp_roles WP Roles obbject
	 *
	 * @return array Array of roles for use in creation and deletion
	 */
	public function get_roles(){
		global $wp_roles;
		$clean_roles = array();

	    $role_names = $wp_roles->get_names();
		$flipped = array_flip( $role_names );

		// Loop through all available roles
		$roles = get_editable_roles();

		$skipped_roles = array(
			'Administrator'
		);

		foreach ( $roles as $role ){

			if ( in_array( $role['name'], $skipped_roles ) ){
				continue;
			}

			$clean_roles[] = array(
				'name'	=> $role['name'],
				'slug'	=> $flipped[ $role['name'] ]
			);

		}

		return $clean_roles;

	}

}
