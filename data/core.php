<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Core extends Abstracts\Data {
	protected function data( $field ) {

	}

	protected function post_status( $field ) {
		$statuses = [
			'publish',
			'pending',
			'draft',
			'trash',
		];
	}

	protected function dummybot_meta( $field ) {
		return '__dummydata__';
	}
}
