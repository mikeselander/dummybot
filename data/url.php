<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class URL extends Abstracts\Data {
	protected function data( $field ) {
		$data = [
			'http://google.com',
			'https://www.twitter.com',
			site_url( '/?iam=anextravariable' ),
			'github.com',
			'http://filebase.com',
			'http://facebook.com',
			'https://www.eff.org',
			'https://jalopnik.com',
		];

		return $this->random( $data );
	}
}
