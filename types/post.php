<?php

namespace DummyBot\Type;

use DummyBot\Abstracts;

class Post extends Abstracts\Type {

	public function get_core_fields( $post_type ) {
		// Extract what the custom post type supports.
		$supports 	 = $this->get_cpt_supports( $slug );
		$core_fields = [
			'post_status' => [ 'Core', 'post_status' ],
		];

		if ( $supports['title'] ) {
			$core_fields['post_title'] = 'Text';
		}

		if ( $supports['editor'] ) {
			$core_fields['post_content'] = 'WYSIWYG';
		}

		if ( $supports['exceprt'] ) {
			$core_fields['post_excerpt'] = 'Text';
		}

		if ( $supports['thumbnail'] ) {
			$core_fields['thumbnail'] = [ 'Image', 'id' ];
		}

		return $core_fields;
	};

	public function insert() {
		// wp_insert_post();
	};

	public function delete() {
		// wp_delete_post();
	};

	public function update() {

	};
}
