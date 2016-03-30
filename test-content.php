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

// Define a list of subfolders to poke through for files
$dirs = array(
	'includes'
);

/*
 * Pseudo-autoload all necessary files
 *
 * Loop through our directory array and require any PHP files without individual calls.
 */
foreach ( $dirs as $dir ){
	foreach ( glob( plugin_dir_path( __FILE__ ) . "/$dir/*.php", GLOB_NOSORT ) as $filename ){
	    require_once $filename;
	}
}

$admin_page = new testContent\AdminPage;
$admin_page->hooks( __FILE__ );