<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class OEmbed extends Abstracts\Data {
	protected function data( $field ) {
		$data = array(
			'https://www.youtube.com/watch?v=A85-YQsm6pY',
			'https://vimeo.com/140327103',
			'https://twitter.com/WordPress/status/664594697093009408',
			'https://embed-ssl.ted.com/talks/regina_hartley_why_the_best_hire_might_not_have_the_perfect_resume.html',
			'http://www.slideshare.net/laurengalanter/choose-your-own-career-adventure',
			'https://www.instagram.com/p/-eyLo0RMfX',
		);

		return $this->random( $data );
	}
}
