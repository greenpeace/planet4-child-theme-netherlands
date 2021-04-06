<?php

const DEV_ASSET_PATH = 'http://localhost:3003/public/build/';

function is_dev(): bool
{
	$file_path = get_theme_file_path();
	return count(glob($file_path . '/public/build/*')) === 0;
}

function enqueue_assets_from_entry($name, $script_dependencies = [], $style_dependencies = [])
{
	// Check to see if there are files in the build directory, If not (in develop mode) the in-memory JS file from webpack is used.
	if (is_dev()) {
		enqueue_dev_assets($name, $script_dependencies);
	} else {
		enqueue_prod_assets($name, $script_dependencies, $style_dependencies);
	}
}

function enqueue_dev_assets($name, $script_dependencies)
{
	wp_enqueue_script($name, DEV_ASSET_PATH . $name . '.js', $script_dependencies, null, true);
}

function enqueue_prod_assets($name, $script_dependencies, $style_dependencies)
{
	$manifest = json_decode(file_get_contents(get_theme_file_path() . '/public/build/manifest.json'), true);
	$script_glob = glob(get_theme_file_path() . '/public/build/' . $name . '.js');
	$script_version = $manifest[basename($script_glob[0])];
	$style_glob = glob(get_theme_file_path() . '/public/build/' . $name . '.css');
	$style_version = $manifest[basename($style_glob[0])];

	!empty($script_glob) ? wp_enqueue_script($name, get_stylesheet_directory_uri() . '/public/build/' . basename($script_glob[0]), $script_dependencies, $script_version, true) : null;
	!empty($script_glob) ? wp_enqueue_style($name, get_stylesheet_directory_uri() . '/public/build/' . basename($style_glob[0]), $style_dependencies, $style_version) : null;
}


function enqueue_assets()
{
	// Runtime.js is required in dev only to run the rest of the scripts.
	if (is_dev()) {
		wp_enqueue_script('child-theme-runtime', DEV_ASSET_PATH . 'runtime.js', [], null, true);
	}
	enqueue_assets_from_entry('bootstrap', ['jquery']);
	enqueue_assets_from_entry('child-theme-main', ['bootstrap'], ['bootstrap', 'parent-style', 'planet4-blocks-style']);
}
add_action('wp_enqueue_scripts', 'enqueue_assets', 3);


function enqueue_admin_assets()
{
	// Runtime.js is required in dev only to run the rest of the scripts.
	if (is_dev()) {
		wp_enqueue_script('child-theme-runtime', DEV_ASSET_PATH . 'runtime.js', [], null, true);
	}
	enqueue_assets_from_entry('bootstrap', ['jquery']);
	enqueue_assets_from_entry('child-theme-main', ['bootstrap'], ['bootstrap']);
	enqueue_assets_from_entry('child-theme-editor');
}

add_action('enqueue_block_editor_assets', 'enqueue_admin_assets', 1);

/**
 * Change the title placeholders for the posts and pages.
 */
function change_title_placeholders()
{
	$screen = get_current_screen();

	if ('post' === $screen->post_type) {
		$title = 'Posttitel toevoegen';
	} else {
		$title = 'Paginatitel toevoegen (voor de URL)';
	}
	return $title;
}

add_filter('enter_title_here', 'change_title_placeholders');


/**
 * Default template for page (if GPNL plugin is activated).
 */
function set_page_template()
{
	if (is_plugin_active('planet4-gpnl-plugin-gutenberg-blocks/planet4-gutenberg-blocks.php')) {
		$post_type_object = get_post_type_object('page');
		$post_type_object->template = array(array('planet4-gpnl-blocks/hero-image'), array('core/paragraph'),);
	}
}

add_action('init', 'set_page_template');

// Removes the canonical redirection
remove_filter('template_redirect', 'redirect_canonical');

/**
 * Fix pagination on archive pages
 * After adding a rewrite rule, go to Settings > Permalinks and click Save to flush the rules cache
 */

add_filter( 'redirect_canonical', 'pick_event_year_redirect', 10 );
function pick_event_year_redirect( $redirect_url ) {
	if ( is_tax( 'event_category' ) && is_year() ) {
		return '';
	}

	return $redirect_url;
}
function news_pagination()
{
	add_rewrite_rule('^nieuws/?([0-9]{1,})/?$', 'index.php?pagename=nieuws&paged=$matches[1]', 'top');
}

add_action('init', 'news_pagination');
add_action('after_switch_theme', 'flush_rewrite_rules');


/**
 * Instantiate the GPNL child theme.
 */
require_once __DIR__ . '/classes/class-p4nl-loader.php';
P4NL_Theme_Loader::get_instance();

// Disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php and add p4 sanitization.
remove_filter('pre_user_description', 'wp_filter_kses');
add_filter('pre_user_description', 'wp_filter_post_kses');

// Set green "G" as default gravatar (profile picture)
add_filter('avatar_defaults', 'wpb_new_gravatar');
function wpb_new_gravatar($avatar_defaults)
{
	$default_gravatar = 'https://storage.googleapis.com/planet4-netherlands-stateless/2020/06/7c8213f7-letterg_2019_greenbackground.png';
	$avatar_defaults[$default_gravatar] = "Default Gravatar";
	return $avatar_defaults;
}

$options = get_option('planet4nl_options');
$notification = $options['gpnl_sf_notification'];
$system_status = $options['gpnl_system_status'];

if ('charibase' != $system_status) {
	add_filter('the_content', 'filter_charibaselinks');
	function filter_charibaselinks($content)
	{
		$options = get_option('planet4nl_options');
		$notification = nl2br($options['gpnl_sf_notification']);
		$notification = '<div class="gpnl-notification"><p>' . $notification . '</p></div>';

		$content = preg_replace('/(<iframe).*(greenpeace\.nl).*>.*(<\/iframe>)/', "$notification", $content);
		return $content;
	}
}


/**
 * Modify the behavior of tag pages when a redirect is set. The master theme will just load the content of the page,
 * we'll redirect instead.
 *
 * @param $redirect_page
 */
function p4_child_theme_tag_page_redirect($redirect_page)
{
	$permalink = get_permalink($redirect_page);

	if ($permalink !== false) {
		wp_safe_redirect($permalink, 301);
		exit;
	}
}

add_action('p4_action_tag_page_redirect', 'p4_child_theme_tag_page_redirect');


/**
 * Change default sort order of pages in Wordpress admin
 */
function p4_child_theme_set_post_order_in_admin($wp_query)
{
	global $pagenow;

	if (is_admin() && 'edit.php' == $pagenow && array_key_exists('post_type', $_GET) && $_GET['post_type'] == 'page' && !isset($_GET['orderby'])) {
		$wp_query->set('orderby', 'post_modified');
		$wp_query->set('order', 'DESC');
	}
}

add_filter('pre_get_posts', 'p4_child_theme_set_post_order_in_admin', 5);

function datawrapper_oembed_provider()
{
	wp_oembed_add_provider('https://datawrapper.dwcdn.net/*', 'https://api.datawrapper.de/v3/oembed', false);
}

add_action('init', 'datawrapper_oembed_provider');

add_action('init', 'check_demopage');
function check_demopage(){
	// demo page should only be published in non-prod environments
	$hostname= $_SERVER['HOSTNAME'];
	$demopage_status = get_post_status(43617);
	if (($hostname !== "www.greenpeace.org") && $demopage_status !== false && $demopage_status !== 'publish') {
		wp_publish_post(43617);
	}
}
