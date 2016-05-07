<?php
namespace testContent\Views;

class Various extends View{

	protected $title	= 'Various';
	protected $type		= 'all';
	protected $priority	= 3;

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

}
