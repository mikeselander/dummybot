<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Email extends Abstracts\Data {
	protected function data( $field ) {
		$data = [
			'mike@gmail.com',
			'me@me.com',
			'joe@smith.org+15',
			'jane@janedoe.com',
			'help@github.com',
			'brian_roberts@comcast.com',
			'inigo@iaminigomontoyayoukilledmyfatherpreparetodie.com',
			'witch@theyellowbrickroad.com',
		];

		return sanitize_email( $this->random( $data ) );
	}

	protected function superrandom( $field ) {
		$user = $domain = '';

			$tlds = [
				'com',
				'net',
				'gov',
				'org',
				'edu',
				'biz',
				'info',
			];

			$char = '0123456789abcdefghijklmnopqrstuvwxyz';

			$user_length   = mt_rand( 5, 20 );
		    $domain_length = mt_rand( 7, 12 );

			for ( $i = 1; $i <= $user_length; $i++ ) {
				$user .= substr( $char, mt_rand( 0, strlen( $char ) ), 1 );
			}

			for ( $i = 1; $i <= $domain_length; $i++ ) {
				$domain .= substr( $char, mt_rand( 0, strlen( $char ) ), 1 );
			}

			$tld = $tlds[ mt_rand( 0, ( sizeof( $tlds ) - 1 ) ) ];

			$email = $user . "@" . $domain . '.' . $tld;

			return sanitize_email( $email );
	}
}
