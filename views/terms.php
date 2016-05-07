<?php
namespace testContent\Views;

class Terms extends View{

	protected $title	= 'Posts';
	protected $type		= 'post';
	protected $priority	= 1;

	protected function actions_section(){
		$html .= '';

		$taxonomies = get_taxonomies();

		foreach ( $taxonomies as $tax ) :

			$skipped_taxonomies = array(
				'post_format',				// We shouldn't be making random post format classes
				'product_shipping_class'	// These aren't used visually and are therefore skipped
			);

			// Skip banned taxonomies
			if ( in_array( $tax, $skipped_taxonomies ) ){
				continue;
			}

			$taxonomy = get_taxonomy( $tax );

			$html .= "<div class='test-data-cpt'>";

				$html .= "<h3>";

				$html .= "<span class='label'>".$taxonomy->labels->name."</span>";

				$html .= $this->build_button( 'create', $tax, __( 'Create', 'otm-test-content' )." ".$taxonomy->labels->name );
				$html .= $this->build_button( 'delete', $tax, __( 'Delete', 'otm-test-content' )." ".$taxonomy->labels->name );

				$html .= "</h3>";

			$html .= "</div>";

		endforeach;

		return $html;
	}

}
