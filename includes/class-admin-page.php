<?php
namespace testContent;

/**
 * Class to build test data for custom post types.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class AdminPage{

	/**
	 * Hooks function.
	 *
	 * This function is used to avoid loading any unnecessary functions/code.
	 *
	 * @see admin_menu, wp_ajax actions
	 */
	public function hooks( $file ){

		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		add_action( 'wp_ajax_handle_test_data', array( $this, 'handle_test_data_callback' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $file ) , array( $this, 'add_settings_link' ) );

	}


	/**
	 * Add the admin-side menu item for creating & deleting test data.
	 *
	 * @see add_submenu_page
	 */
	public function add_menu_item() {

		add_submenu_page(
			'tools.php',
			__( 'Create Test Content', 'otm-test-content' ),
			__( 'Test Content', 'otm-test-content' ),
			'manage_options',
			'create-test-data',
			array( $this, 'admin_page' )
		);

	}

	/**
	 * Add 'build test content' link to plugin list table.
	 *
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link( $links ) {

		$settings_link = '<a href="tools.php?page=create-test-data">' . __( 'Build Test Content', 'otm-test-content' ) . '</a>';
  		array_push( $links, $settings_link );
  		return $links;

	}


	/**
	 * Load our script in the admin section and serve in data.
	 */
	public function load_scripts(){

		wp_enqueue_script( 'test-content-js', plugins_url( 'assets/admin.js' , dirname( __FILE__ ) ) );

		$data = array(
			'nonce'	=> wp_create_nonce( 'handle-test-data' )
		);

		wp_localize_script( 'test-content-js', 'test_content', $data );

	}


	/**
	 * Ajax callback function for triggering the creation & deletion of test data.
	 *
	 * @see wp_ajax filter, $this->add_menu_item, $this->creation_routing
	 */
	public function handle_test_data_callback() {

		$action		= $_REQUEST['todo'];
		$nonce		= $_REQUEST['nonce'];

		// Verify that we have a proper logged in user and it's the right person
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'handle-test-data' ) ){
			return;
		}

		if ( $action == 'delete' ){

			$this->deletion_routing( $_REQUEST );

		} elseif ( $action == 'create' ){

			$this->creation_routing( $_REQUEST );

		}

		die();

	}


	/**
	 * Choose which type of creation needs to be accomplished and route through
	 * the correct class.
	 */
	private function creation_routing( $data ){

		if ( $data['type'] == 'post' ){

			$create_content = new CreatePost;
			$create_content->create_post_type_content( $data['slug'], true, 1 );

		} elseif( $data['type'] == 'term' ){

			$create_content = new CreateTerm;
			$create_content->create_terms( $data['slug'], true, 1 );

		}

	}


	/**
	 * Choose which type of deletion needs to be accomplished and route through
	 * the correct method of Delete.
	 */
	private function deletion_routing( $data ){

		$delete_content = new Delete;

		if ( $data['type'] == 'post' ){

			$delete_content->delete_post( $data['slug'], true );

		}

	}


	/**
	 * Print out our admin page to control test data.
	 */
	public function admin_page(){

		$html = "";

		$html .= '<div class="wrap" id="options_editor">' . "\n";

			$html .= '<h2>' . __( 'Create Test Data' , 'otm-test-content' ) . '</h2>' . "\n";

			// Loop through all other cpts
			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$html .= "<div>";

			$html .= "<div class='test-data-cpt'>";
				$html .= "<h3>";
					$html .= "<span style='width: 20%; display: inline-block;'>Quantity</span>";
					$html .= "<input type='number' value='0' id='quantity-adjustment'> <small><i>Set to 0 to keep random</i></small>";
				$html .= "</h3>";
			$html .= "</div>";

			// Loop through every post type available on the site
			foreach ( $post_types as $post_type ) {

				$skipped_cpts = array(
					'attachment'
				);

				// Skip banned cpts
				if ( in_array( $post_type->name, $skipped_cpts ) ){
					continue;
				}

				$html .= "<div class='test-data-cpt'>";

					// Create row for the post/page/cpt
					$html .= "<h3>";

						$html .= "<span style='width: 20%; display: inline-block;'>" . $post_type->labels->name . "</span>";
						$html .= " <a href='javascript:void(0);' data-type='post' data-slug='".$post_type->name."' data-todo='create' class='button-primary handle-test-data' /><span class='dashicons dashicons-plus' style='margin-top: 6px; font-size: 1.2em'></span> ".__( 'Create Test Data', 'otm-test-content' )."</a>";
						$html .= " <a href='javascript:void(0);' data-type='post' data-slug='".$post_type->name."' data-todo='delete' class='button-primary handle-test-data' /><span class='dashicons dashicons-trash' style='margin-top: 4px; font-size: 1.2em'></span> ".__( 'Delete Test Data', 'otm-test-content' )."</a>";

					$html .= "</h3>";

					// Create row for each taxonomy associated with the post/page/cpt
					$taxonomies = get_object_taxonomies( $post_type->name );

						if ( !empty( $taxonomies ) ){

							foreach( $taxonomies as $tax ){

								$html .= "<h3>";

								$skipped_taxonomies = array(
									'post_format'
								);

								// Skip banned taxonomies
								if ( in_array( $tax, $skipped_taxonomies ) ){
									continue;
								}

								$taxonomy = get_taxonomy( $tax );

								$html .= "<span style='width: 20%; display: inline-block; font-size: .9em'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$taxonomy->labels->name."</span>";

								$html .= " <a href='javascript:void(0);' data-type='term' data-slug='".$tax."' data-todo='create' class='button-primary handle-test-data' /><span class='dashicons dashicons-category' style='margin-top: 4px; font-size: 1.2em'></span> ".__( 'Create', 'otm-test-content' )." ".$taxonomy->labels->name."</a>";

								$html .= "</h3>";

							}
						}

				$html .= "</div>";

			}

			$html .= "<pre style='display: block; width:95%; height:300px; overflow-y: scroll; background: #fff; padding: 10px;' id='status-updates'></pre>";

		$html .= "</div>";

		echo $html;

	}

}