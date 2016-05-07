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
	 * plugin
	 * Access to plugin definitions.
	 *
	 * @var Plugin
	 * @access private
	 */
	private $plugin;

	/**
	 * definitions
	 * Easy way to access all of our defined paths & info.
	 *
	 * @var object
	 * @access private
	 */
	private $definitions;

	/**
	 * connected
	 * Whether or not we're successfully connected to the Internet.
	 *
	 * @var boolean
	 * @access private
	 */
	private $connected;


	/**
	 * Hooks function.
	 *
	 * This function is used to avoid loading any unnecessary functions/code.
	 *
	 * @see admin_menu, wp_ajax actions
	 */
	public function hooks(){

		$connection = new ConnectionTest;
		$this->definitions	= $this->plugin->get_definitions();
		$this->connected	= $connection->test();

		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );
		add_action( 'wp_ajax_handle_test_data', array( $this, 'handle_test_data_callback' ) );
		add_filter( 'plugin_action_links_' . $this->definitions->basename , array( $this, 'add_settings_link' ) );
		add_action( 'admin_notices', array( $this, 'internet_connected_admin_notice' ) );

	}


	/**
	 * Set a reference to the main plugin instance.
	 *
	 * @param Plugin $plugin Main plugin instance.
	 */
	public function set_plugin( $plugin ) {

		$this->plugin = $plugin;
		return $this;

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
		if ( $this->connected ) {
			// We got a response so early return
			return;
		} else {
			// We didn't get a reponse so print the notice out
			echo '<div class="notice notice-error">';
		        echo '<p>'.__( 'WordPress could not connect to Splashbase and therefore images will not pull into metaboxes/thumbnails. Turn Airplane Mode off or reconnect to the Internet to get images when creating test data.', 'otm-test-content' ).'</p>';
		    echo '</div>';
		}

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
			$create_content->create_post_type_content( $data['slug'], $data['connection'], true, 1 );

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

		} elseif ( $data['type'] == 'all' ){

			$delete_content->delete_all_test_data( true );

		}

	}


	/**
	 * Print out our admin page to control test data.
	 */
	public function admin_page(){
		echo '<div class="wrap" id="options_editor">' . "\n";

			echo '<h2>' . __( 'Create Test Data' , 'otm-test-content' ) . '</h2>' . "\n";

			echo "<div class='nav-tab-wrapper'>";

				do_action( 'tc-admin-tabs', '' );

			echo "</div>";

			echo "";

				do_action( 'tc-admin-sections', '' );

			echo "";

			echo "<input type='hidden' id='connection-status' value='".$this->connected."'>";

			echo "<pre class='test-data-status-box' id='status-updates'></pre>";

		echo "</div>";

	}

}
