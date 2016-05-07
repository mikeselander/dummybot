<?php
namespace testContent\Views;


/**
 * Class to generate a view for the admin page.
 *
 * @abstract
 * @package    WordPress
 * @subpackage Test Content
 * @author     Old Town Media
 */
abstract class View{

	/**
	 * title
	 * Title of the tab.
	 *
	 * @var string
	 * @access protected
	 */
	protected $title;

	/**
	 * type
	 * Type of objects we'll be dealing with i.e.: post or term.
	 *
	 * @var string
	 * @access protected
	 */
	protected $type;

	/**
	 * priority
	 * SPriority to pass into the actions.
	 *
	 * @var int
	 * @access protected
	 */
	protected $priority;

	/**
	 * Registers our view with appropriate actions.
	 *
	 * @see tab, view
	 */
	public function register_view(){

		add_action( 'tc-admin-tabs', array( $this, 'tab' ), $this->priority );
		add_action( 'tc-admin-sections', array( $this, 'view' ), $this->priority );

	}


	/**
	 * Builf the HTML for our tab navigation item.
	 *
	 * Each view has a tab and tab navigation - this function compiles our
	 * navigation tab. Rarely extended.
	 */
	public function tab(){
		$html = "";

		$html .= "<a class='nav-tab' href='javascript:void(0)'>";
			$html .= $this->title;
		$html .= "</a>";

		echo $html;

	}


	/**
	 * Build the HTML for the actual tab content.
	 *
	 * Each view has a tab and tab navigation - this function compiles our
	 * tab content. Rarely extended
	 *
	 * @see actions_section, options_section
	 */
	public function view(){
		$html = '';

		$html .= "<section class='test-content-tab'>";
			$html .= $this->actions_section();
			$html .= $this->options_section();
		$html .= "</section>";

		echo $html;

	}


	/**
	 * Holder function to build the tab main content. Extend this with your content.
	 *
	 * @access protected
	 *
	 * @return string HTML content.
	 */
	protected function actions_section(){
		$html = '';
		return $html;
	}


	/**
	 * Starter for an options section for the view.
	 *
	 * Options are where you could add various options and triggers such as author,
	 * quantity, or any other customization of the created/deleted data.
	 *
	 * @access protected
	 *
	 * @param string $html Existing HTML content.
	 * @return string HTML section content.
	 */
	protected function options_section( $html = '' ){
		$html .= "<div class='test-data-cpt'>";
			$html .= "<h3>";
				$html .= "<span class='label'>".__( 'Quantity', 'otm-test-content' )."</span>";
				$html .= "<input type='number' value='0' class='quantity-adjustment' for='".$this->type."' placeholder='".__( '', 'otm-test-content' )."'> ";
			$html .= "</h3>";
		$html .= "</div>";

		return $html;
	}


	/**
	 * Builds action buttons for creating or deleting content.
	 *
	 * @access protected
	 *
	 * @param string $action Type of action to take - i.e.: create or delete.
	 * @param string $slug Slug ID of the object to create i.e.: page or category.
	 * @param string $text Text to display in the button.
	 * @return string HTML.
	 */
	protected function build_button( $action, $slug, $text ){
		$html = $dashicon = '';

		if ( $action == 'create' ){
			$dashicon = 'dashicons-plus';
		} elseif ( $action == 'delete' ){
			$dashicon = 'dashicons-trash';
		}

		$html .= "<a href='javascript:void(0);' ";
			$html .= " data-type='" . $this->type . "'";
			$html .= " data-slug='" . $slug . "'";
			$html .= " data-todo='" . $action . "'";
			$html .= " class='button-primary handle-test-data'";
		$html .= "/>";
			$html .= "<span class='dashicons " . $dashicon . "'></span>";
			$html .= $text;
		$html .= "</a>";

		return $html;

	}

}
