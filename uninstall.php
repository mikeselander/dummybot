<?php

use testContent as test;

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

// Loop through all post types and remove any test data
$post_types = get_post_types( array( 'public' => true ), 'objects' );
foreach ( $post_types as $post_type ) :

    $delete->delete_posts( $post_type->name );

endforeach;

// Loop through all taxonomies and remove any data
$taxonomies = get_taxonomies();
foreach ( $taxonomies as $tax ) :

    $delete->delete_terms( $tax );

endforeach;
