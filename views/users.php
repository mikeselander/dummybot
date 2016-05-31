<?php
namespace testContent\Views;
use testContent\Abstracts as Abs;
use testContent\Types as Type;

/**
 * Generate view for creating and deleting posts.
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Old Town Media
 */
class Users extends Abs\View{

	public function __construct(){

		$this->title	= __( 'Users', 'dummybot' );
		$this->type		= 'users';
		$this->priority	= 4;

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

		$user_class = new Type\User;
		$roles = $user_class->get_roles();

		foreach ( $roles as $role ) :

			$html .= "<div class='test-data-cpt'>";

				$html .= "<h3>";

					$html .= "<span class='label'>" . $role['name'] . "</span>";
					$html .= $this->build_button( 'create', $role['slug'], __( 'Create Users', 'dummybot' ) );
					$html .= $this->build_button( 'delete', $role['slug'], __( 'Delete Users', 'dummybot' ) );

				$html .= "</h3>";

			$html .= "</div>";

		endforeach;

		return $html;
	}

}
