<?php

namespace DummyBot;

class Master {

	public static $plugin;

	public static function set_plugin( $plugin_info ) {
		self::$plugin = $plugin_info;
	}

	public function types() {
		return [
			'post',
		];
	}

	public function providers() {
		return [
			//'acf'   => 'ACF'
			'cmb1'  => 'CMB1',
			'cmb2'  => 'CMB2',
			'hmcmb' => 'HMCMB',
		];
	}
}
