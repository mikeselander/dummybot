<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class Video extends Abstracts\Data {
	protected function data( $field ) {
		$data = [
			'https://www.youtube.com/watch?v=tntOCGkgt98',
			'https://www.youtube.com/watch?v=O1KW3ZkLtuo',
			'https://www.youtube.com/watch?v=G8KpPw303PY',
			'https://www.youtube.com/watch?v=HxM46vRJMZs',
			'https://www.youtube.com/watch?v=nRzsgCp60YU',
			'https://www.youtube.com/watch?v=25OUFtdno8U',
			'https://www.youtube.com/watch?v=PHAc3_MEjgQ',
			'https://www.youtube.com/watch?v=9bZkp7q19f0',
			'https://www.youtube.com/watch?v=_OBlgSz8sSM',
			'https://vimeo.com/156161909',
			'https://vimeo.com/156045670',
			'https://vimeo.com/144698619',
			'https://vimeo.com/151799633',
			'https://vimeo.com/149224063',
			'https://vimeo.com/154915431',
			'https://vimeo.com/155404383',
			'https://vimeo.com/149478317',
			'https://vimeo.com/154698227',
		];

		return $this->random( $data );
	}
}
