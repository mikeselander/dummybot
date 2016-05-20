<?php
namespace testContent;

/**
 * Reporting wrapper class
 *
 * Class to standardize the reporting and status functionality of the plugin.
 *
 * @package    WordPress
 * @subpackage Evans
 * @author     Old Town Media
 */
class Reporting{

	public function create_report( $data ){

		$cleaned = json_encode( $this->parse_data( $data ) );
		return $cleaned;

	}

	private function parse_data( $data ){

		return $data;

	}

	// none of the reports should echo
	// return arrays of everything

}
