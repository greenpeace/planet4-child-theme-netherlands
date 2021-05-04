<?php

use GPNL\Theme\Loader;


const BUILD_DIR = '/public/build/';
if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} else {
	require_once __DIR__ . '/../../../../vendor/autoload.php';
}

$options = get_option('planet4nl_options');
$system_status = $options['gpnl_system_status'];

if ('charibase' != $system_status) {
	add_filter('the_content', 'filter_charibaselinks');
	function filter_charibaselinks($content)
	{
		$options = get_option('planet4nl_options');
		$notification = nl2br($options['gpnl_sf_notification']);
		$notification = '<div class="gpnl-notification"><p>' . $notification . '</p></div>';

		return preg_replace('/(<iframe).*(greenpeace\.nl).*>.*(<\/iframe>)/', "$notification", $content);
	}
}

/**
 * Instantiate the GPNL child theme.
 */
Loader::get_instance();
