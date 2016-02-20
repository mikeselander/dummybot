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
	public function hooks(){

		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		add_action( 'wp_ajax_handle_test_data', array( $this, 'handle_test_data_callback' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );

	}


	/**
	 * Add the admin-side menu item for creating & deleting test data.
	 *
	 * @see add_submenu_page
	 */
	public function add_menu_item() {

		add_submenu_page(
			'tools.php',
			__( 'Create Test Data', 'evans-mu' ),
			__( 'Test Data', 'evans-mu' ),
			'manage_options',
			'create-test-data',
			array( $this, 'admin_page' )
		);

	}


	/**
	 * Load our script in the admin section and serve in data.
	 *
	 * @param string $hook Specific hook for the admin page that we're dealing with.
	 */
	public function load_scripts( $hook ){

		wp_enqueue_script( 'test-content-js', plugins_url( 'assets/admin.js' , dirname( __FILE__ ) ) );

		$data = array(
			'nonce'	=> wp_create_nonce( 'handle-test-data' )
		);

		wp_localize_script( 'test-content-js', 'test_content', $data );

	}


	/**
	 * Ajax callback function for triggering the creation & deletion of test data.
	 *
	 * @see wp_ajax filter, $this->add_menu_item
	 */
	public function handle_test_data_callback() {

		$cptslug	= $_REQUEST['cptslug'];
		$action		= $_REQUEST['todo'];
		$nonce		= $_REQUEST['nonce'];

		// Verify that we have a proper logged in user and it's the right person
		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'handle-test-data' ) ){
			return;
		}

		$create_content = new Create;
		$delete_content = new Delete;

		if ( $action == 'delete' ){

			$delete_content->delete_test_content( $cptslug, true );

		} elseif ( $action == 'create' ){

			$create_content->create_post_type_content( $cptslug, true, 1 );

		}

		die();

	}


	/**
	 * Print out our admin page to control test data.
	 */
	public function admin_page(){

		$html = "";

		$html .= '<div class="wrap" id="options_editor">' . "\n";

			$html .= '<h2>' . __( 'Create Test Data' , 'evans-mu' ) . '</h2>' . "\n";

			// Loop through all other cpts
			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			foreach ( $post_types as $post_type ) {

				// Skip Attachments
				if ( $post_type->name == 'attachment' ){
					continue;
				}

				$html .= "<div class='test-data-cpt'>";

					$html .= "<h3>";

						$html .= "<span style='width: 20%; display: inline-block;'>" . $post_type->labels->name . "</span>";
						$html .= " <a href='javascript:void(0);' data-cpt='".$post_type->name."' data-todo='create' class='button-primary handle-test-data' /><span class='dashicons dashicons-plus' style='margin-top: 6px; font-size: 1.2em'></span> Create Test Data</a>";
						$html .= " <a href='javascript:void(0);' data-cpt='".$post_type->name."' data-todo='delete' class='button-primary handle-test-data' /><span class='dashicons dashicons-trash' style='margin-top: 4px; font-size: 1.2em'></span> Delete Test Data</a>";

					$html .= "</h3>";

				$html .= "</div>";

			}

			$html .= "<pre style='display: block; width:95%; height:300px; overflow-y: scroll; background: #fff; padding: 10px;' id='status-updates'></pre>";

		$html .= "</div>";

		echo $html;

	}

}

$admin_page = new AdminPage;
$admin_page->hooks();