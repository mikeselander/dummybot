<?php
/**
 * Plugin Name: Test Content Generator
 * Plugin URI: https://github.com/oldtownmedia/test-content-suite
 * Description: A plugin to spin up test posts, pages & CPTs
 * Version: 1.0.0
 * Author: Old Town Media
 * Author URI: https://oldtownmediainc.com/
 * Text Domain: otm-test-content
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Define a list of subfolders to poke through for files
$dirs = array(
	'includes'
);

/*
 * Loop through our directory array and require any PHP files without individual calls.
 */
foreach ( $dirs as $dir ){
	foreach ( glob( plugin_dir_path( __FILE__ ) . "/$dir/*.php", GLOB_NOSORT ) as $filename ){
	    require_once $filename;
	}
}