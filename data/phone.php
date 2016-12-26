<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Phone extends Abstracts\Data {
	protected function data( $field ) {
		$data = [
			'7203893101',
			'303-555-1251',
			'(720) 895 0969',
			'(303)-278-2078',
			'1-907-486-1102',
			'011-44-871-789-3642',
			'1-800-437-7950',
			'1-503-254-1000',
			'1-845-354-9912',
			'+1 253-243-3381',
			'+43 780 0047112',
		];

		return $this->random( $data );
	}
}
