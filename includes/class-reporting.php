<?php
namespace DummyPress;

/**
 * Reporting wrapper class
 *
 * Class to standardize the reporting and status functionality of the plugin.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Mike Selander
 */
class Reporting {

	public function create_report( $data ) {

		$cleaned = json_encode( $this->parse_data( $data ) );
		return $cleaned;

	}

	private function parse_data( $data ) {

		return $data;

	}

}
