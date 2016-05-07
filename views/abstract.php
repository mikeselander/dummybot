<?php
namespace testContent\Views;

abstract class View{

	protected $title;
	protected $type;
	protected $priority;

	public function register_view(){

		add_filter( 'tc-admin-tabs', array( $this, 'tab' ), $priority );
		add_filter( 'tc-admin-sections', array( $this, 'view' ), $priority );

	}

	protected function tab(){
		$html .= "";

		$html .= "<a class='nav-tab' href='javascript:void(0)'>";
			$html .= $this->title;
		$html .= "</a>";

		return $html;

	}

	protected function view(){
		$html = '';

		$html .= "<section>";
			$html .= $this->actions_section();
			$html .= $this->options_section();
		$html .= "</section>";

		return $html;

	}

	protected function actions_section(){
		$html = '';
		return $html;
	}

	protected function options_section(){
		$html = '';

		$html .= "<div class='test-data-cpt'>";
			$html .= "<h3>";
				$html .= "<span class='label'>".__( 'Quantity', 'otm-test-content' )."</span>";
				$html .= "<input type='number' value='0' id='quantity-adjustment'> <small><i>".__( 'Set to 0 to keep random', 'otm-test-content' )."</i></small>";
			$html .= "</h3>";
		$html .= "</div>";

		$html .= "<input type='hidden' id='connection-status' value='".$this->connected."'>";

		return $html;
	}

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
