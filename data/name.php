<?php

namespace DummyBot\Data;

use DummyBot\Abstracts;

class name extends Abstracts\Data {
	protected function data( $field ) {
		return $this->first( $field ) . ' ' . $this->last( $field );
	}

	protected function first( $field ) {
		$data = [
			'Jacqui',
			'Buffy',
			'Teddy',
			'Cindie',
			'Carroll',
			'Karly',
			'Maricela',
			'Kittie',
			'Jetta',
			'Denise',
			'Guillermo',
			'Domingo',
			'Benjamin',
			'Olga',
			'Shane',
			'Bessie',
			'Jose',
			'Damon',
			'Rodolfo',
			'George',
		];

		return $this->random( $data );
	}

	protected function last( $field ) {
		$data = [
			'Henley',
			'Trask',
			'Dick',
			'Irby',
			'Raley',
			'Bland',
			'Rossi',
			'Gunther',
			'Mchenry',
			'Isaacs',
			'Romero',
			'Mcbride',
			'Armstrong',
			'Mccoy',
			'Evans',
			'Dennis',
			'Swanson',
			'Estrada',
			'Johnston',
			'Graves',
		];

		return $this->random( $data );
	}
}
