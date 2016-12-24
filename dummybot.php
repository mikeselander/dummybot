<?php
/**
 * Plugin Name: DummyBot
 * Plugin URI: https://dummybot.com
 * Description: Spin up test posts, pages, CPTs, and terms from an easy-to-use admin page.
 * Version: 1.0
 * Author: Mike Selander
 * Author URI: https://github.com/mikeselander/
 * Text Domain: dummybot
 * Domain Path: /languages
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

namespace DummyBot;

// Load in master class.
require_once dirname( __FILE__ ) . '/class-master.php';

// Trigger plugin master class to run.
Master::set_plugin(
	(object) [
		'basename'	=> plugin_basename( __FILE__ ),
		'directory'	=> plugin_dir_path( __FILE__ ),
		'file'		=> __FILE__,
		'slug' 		=> 'dummybot',
		'url'		=> plugin_dir_url( __FILE__ )
	]
);

/**
 * Autoloader callback.
 *
 * Converts a class name to a file path and requires it if it exists.
 *
 * @param string $class Class name.
 */
function dummybot_autoloader( $class ) {
	$namespace = explode( '\\', $class );

 	if ( __NAMESPACE__ !== $namespace[0] ) {
 		return;
 	}

    $class = str_replace( __NAMESPACE__ . '\\', '', $class );

	$nss = [
		'Abstracts',
		'Data',
		'Providers',
		'Types',
	];

	if ( in_array( $namespace[1], $nss ) ) {
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
 spl_autoload_register( __NAMESPACE__ . '\dummybot_autoloader' );
