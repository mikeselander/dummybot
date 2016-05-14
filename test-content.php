<?php
/**
 * Plugin Name: Test Content Generator
 * Plugin URI: https://github.com/oldtownmedia/test-content-suite
 * Description: Spin up test posts, pages, CPTs, and terms from an easy-to-use admin page.
 * Version: 1.0
 * Author: Old Town Media
 * Author URI: https://github.com/oldtownmedia/
 * Text Domain: otm-test-content
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace testContent;

/**
 * Autoloader callback.
 *
 * Converts a class name to a file path and requires it if it exists.
 *
 * @param string $class Class name.
 */
function test_content_autoloader( $class ) {
	$namespace = explode( '\\', $class );

 	if ( __NAMESPACE__ !== $namespace[0] ){
 		return;
 	}

    $class = str_replace( __NAMESPACE__ . '\\', '', $class );

	$nss = array(
		'Abstracts',
		'Types',
		'Views'
	);

	if ( in_array( $namespace[1], $nss ) ){
        $class = strtolower( preg_replace( '/(?<!^)([A-Z])/', '/\1', $class ) );
        $class = str_replace( '\\', '', $class );
     	$file  = dirname( __FILE__ ) . '/' . $class . '.php';
    } else {
        $class = strtolower( preg_replace( '/(?<!^)([A-Z])/', '-\\1', $class ) );
     	$file  = dirname( __FILE__ ) . '/includes/class-' . $class . '.php';
    }

 	if ( is_readable( $file ) ) {
 		require_once( $file );
 	}
 }
 spl_autoload_register( __NAMESPACE__ . '\test_content_autoloader' );



 /**
  * Retrieve the plugin instance.
  *
  * @return object Plugin
  */
 function plugin() {
 	static $instance;

 	if ( null === $instance ) {
 		$instance = new Plugin();
 	}

 	return $instance;
 }

// Set our definitions for later use
 plugin()->set_definitions(
	(object) array(
		'basename'	=> plugin_basename( __FILE__ ),
		'directory'	=> plugin_dir_path( __FILE__ ),
		'file'		=> __FILE__,
		'slug' 		=> 'structure',
		'url'		=> plugin_dir_url( __FILE__ )
	)
);

 // Register hook providers and views.
 plugin()->register_hooks( new AdminPage() )
         ->register_view( new Views\Posts() )
         ->register_view( new Views\Terms() )
		 ->register_view( new Views\Users() )
		 ->register_view( new Views\Various() )
		 ->register_type( new Types\Post() )
		 ->register_type( new Types\Term() )
		 ->register_type( new Types\User() );

// Load textdomain hook
add_action( 'plugins_loaded', array( plugin(), 'load_textdomain' ) );
