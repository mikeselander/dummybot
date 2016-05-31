<?php

use DummyPress as test;

// If uninstall is not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

// Check if the current user has priveledges to run this method
if ( ! current_user_can( 'activate_plugins' ) ){
    return;
}

require dirname( __FILE__ ) . '/includes/class-delete.php';
$delete = new test\Delete;

// Delete all the things
$delete->delete_all_test_data();
