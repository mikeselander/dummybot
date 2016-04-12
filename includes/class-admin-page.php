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
	 * file
	 * Parent file that calls this class.
	 *
	 * @var string
	 * @access private
	 */
	private $file;

	/**
	 * Hooks function.
	 *
	 * This function is used to avoid loading any unnecessary functions/code.
	 *
	 * @see admin_menu, wp_ajax actions
	 */
	public function hooks( $file ){

		$this->file = $file;

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		add_action( 'wp_ajax_handle_test_data', array( $this, 'handle_test_data_callback' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( $file ) , array( $this, 'add_settings_link' ) );
		add_action( 'admin_notices', array( $this, 'internet_connected_admin_notice' ) );

	}


	/**
	 * Load the textdomain for this plugin if translation is available
	 *
	 * @see load_plugin_textdomain
	 */
	public function load_textdomain() {
	    load_plugin_textdomain( 'otm-test-content', FALSE, basename( dirname( $this->file ) ) . '/languages/' );
	}


	/**
	 * Add the admin-side menu item for creating & deleting test data.
	 *
	 * @see add_submenu_page
	 */
	public function add_menu_item() {

		$page = add_submenu_page(
			'tools.php',
			__( 'Create Test Content', 'otm-test-content' ),
			__( 'Test Content', 'otm-test-content' ),
			'manage_options',
			'create-test-data',
			array( $this, 'admin_page' )
		);

		add_action( 'admin_print_styles-' . $page, array( $this, 'load_scripts' ) );

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
	 * Admin notice to notify a user that images won't work on test dat.
	 *
	 * Adds an admin notice that first checks if a user is connected to the
	 * Internet, and the test fails displays a notice informing the user that
	 * images will not pull into test data.
	 */
	public function internet_connected_admin_notice(){

		// Get the current admin screen & verify that we're on the right one
		// before continuing.
		$screen = get_current_screen();

		if ( $screen->base != 'tools_page_create-test-data' ){
			return;
		}

		// Check the response
		if ( $this->test_splashbase_api() ) {
			// We got a response so early return
			return;
		} else {
			// We didn't get a reponse so print the notice out
			echo '<div class="notice notice-error is-dismissible">';
		        echo '<p>'.__( 'WordPress could not connect to Splashbase and therefore images will not pull into metaboxes/thumbnails. Turn Airplane Mode off or reconnect to the Internet to get images when creating test data.', 'otm-test-content' ).'</p>';
		    echo '</div>';
		}

	}


	/**
	 * A more intelligent check to see if we can connect to Splashbase or not.
	 *
	 * This function checks whether or not we can connect to the Internet, and
	 * if we can, whether we can connect to Splashbase itself. This is used by
	 * our admin notice function to check whether or not we should display a notice
	 * to users warning them of issues with Splashbase.
	 *
	 * The purpose of this is to avoid useless bug-hunting when images don't work.
	 *
	 * @access private
	 *
	 * @see fsockopen, get_site_option, wp_remote_get
	 *
	 * @return boolean Status of connection to Splashbase.
	 */
	private function test_splashbase_api(){

		/*
		 * Test #1 - Check Internet connection in general
		 */
		// Attempt to open a socket connection to Google
		$connected = @fsockopen( "www.google.com", 80 );

		if ( !$connected ){
			return false;
		}

		// Close out our 1st test
		fclose( $connected );


		/*
		 * Test #2 - Check for Airplane Mode plugin status
		 */
		if ( class_exists( 'Airplane_Mode_Core' ) ){
			// Is airplane mode active?
			$airplane_mode = get_site_option( 'airplane-mode' );

			if ( $airplane_mode === 'on' ){
				return false;
			}
		}


		/*
		 * Test #3 - Check Splashbase itself
		 */
		$test_url = 'http://www.splashbase.co/api/v1/images/';
		$response = wp_remote_get( $test_url );

		if ( !is_array( $response ) ){
			return false;
		}

		// We've made it this far, looks like everything checks out OK!
		return true;

	}


	/**
	 * Load our script in the admin section and serve in data.
	 */
	public function load_scripts(){

		wp_enqueue_script( 'test-content-js', plugins_url( 'assets/admin.js' , dirname( __FILE__ ) ) );
		wp_enqueue_style( 'test-content-css', plugins_url( 'assets/admin.css' , dirname( __FILE__ ) ) );

		$data = array(
			'nonce'			=> wp_create_nonce( 'handle-test-data' ),
			'createdStr'	=> __( 'Created', 'otm-test-content' ),
			'deletedStr'	=> __( 'Deleting', 'otm-test-content' ),
			'creatingStr'	=> __( 'Creating', 'otm-test-content' ),
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

			$delete_content->delete_posts( $data['slug'], true );

		} elseif ( $data['type'] == 'term' ){

			$delete_content->delete_terms( $data['slug'], true );

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
					$html .= "<span class='label'>".__( 'Quantity', 'otm-test-content' )."</span>";
					$html .= "<input type='number' value='0' id='quantity-adjustment'> <small><i>".__( 'Set to 0 to keep random', 'otm-test-content' )."</i></small>";
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

						$html .= "<span class='label'>" . $post_type->labels->name . "</span>";
						$html .= " <a href='javascript:void(0);' data-type='post' data-slug='".$post_type->name."' data-todo='create' class='button-primary handle-test-data' /><span class='dashicons dashicons-plus'></span> ".__( 'Create Test Data', 'otm-test-content' )."</a>";
						$html .= " <a href='javascript:void(0);' data-type='post' data-slug='".$post_type->name."' data-todo='delete' class='button-primary handle-test-data' /><span class='dashicons dashicons-trash'></span> ".__( 'Delete Test Data', 'otm-test-content' )."</a>";

					$html .= "</h3>";

					// Create row for each taxonomy associated with the post/page/cpt
					$taxonomies = get_object_taxonomies( $post_type->name );

						if ( !empty( $taxonomies ) ){

							foreach( $taxonomies as $tax ){

								$html .= "<h3 class='term-box'>";

								$skipped_taxonomies = array(
									'post_format',				// We shouldn't be making random post format classes
									'product_shipping_class'	// These aren't used visually and are therefore skipped
								);

								// Skip banned taxonomies
								if ( in_array( $tax, $skipped_taxonomies ) ){
									continue;
								}

								$taxonomy = get_taxonomy( $tax );

								$html .= "<span class='label'>".$taxonomy->labels->name."</span>";

								$html .= " <a href='javascript:void(0);' data-type='term' data-slug='".$tax."' data-todo='create' class='button-primary handle-test-data' /><span class='dashicons dashicons-category'></span> ".__( 'Create', 'otm-test-content' )." ".$taxonomy->labels->name."</a>";

								$html .= " <a href='javascript:void(0);' data-type='term' data-slug='".$tax."' data-todo='delete' class='button-primary handle-test-data' /><span class='dashicons dashicons-trash'></span> ".__( 'Delete', 'otm-test-content' )." ".$taxonomy->labels->name."</a>";

								$html .= "</h3>";

							}
						}

				$html .= "</div>";

			}

			$html .= "<pre class='test-data-status-box' id='status-updates'></pre>";

		$html .= "</div>";

		echo $html;

	}

}
