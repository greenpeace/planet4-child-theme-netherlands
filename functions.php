<?php
/**
 * Include the child css.
 */

function enqueue_child_styles() {
	$plugin_version = wp_get_theme()->get( 'Version' );

	wp_enqueue_style( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css', array(), '4.1.1' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [ 'bootstrap', 'parent-style', 'plugin-blocks' ], $plugin_version );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 1 );

function enqueue_child_scripts() {

	wp_register_script( 'jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js', [], '3.4.1', true );
	wp_enqueue_script( 'jquery' );
	wp_register_script( 'popper', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"', [], '1.14.7', true );
	wp_enqueue_script( 'popper' );
	wp_register_script( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js', [ 'jquery', 'popper' ], '4.1.1', true );
	wp_enqueue_script( 'bootstrap' );

	wp_register_script( 'navigation-bar', get_stylesheet_directory_uri() . '/assets/js/navigation-bar.js', [ 'jquery' ], '3.1.3', false);
	wp_enqueue_script( 'navigation-bar' );
	wp_register_script( 'donation', get_stylesheet_directory_uri() . '/assets/js/dontrans.js', [ 'jquery' ], '4.0.9', true );
	wp_enqueue_script( 'donation' );
	// Pass options to frontend code
	wp_localize_script(
		'navigation-bar',
		'p4nl_vars',
		[
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		]
	);

}
add_action( 'wp_enqueue_scripts', 'enqueue_child_scripts' );

function remove_custom_css_from_customizer() {
	if ( ! current_user_can( 'administrator' ) ) {
		global $wp_customize;
		$wp_customize->remove_section( 'custom_css' );
	}
}
// Remove the ability for editors to add custom css
 add_action( 'customize_register', 'remove_custom_css_from_customizer', 11 );

/**
 * This will change the title placeholders for the different 'post' types.
 */
function change_title_placeholders() {
	$screen = get_current_screen();

	if ( 'post' === $screen->post_type ) {
		$title = 'Posttitel toevoegen';
	} else {
		$title = 'Paginatitel toevoegen (voor de URL)';
	}
	return $title;
}

add_filter( 'enter_title_here', 'change_title_placeholders' );
//
// **
// * Default template for pages.
// */
function set_page_template() {

	$post_type_object           = get_post_type_object( 'page' );
	$post_type_object->template = array(
		array( 'planet4-gpnl-blocks/hero-image' ),
		array( 'core/paragraph' ),
	);
}
 add_action( 'init', 'set_page_template' );

// Removes the canonical redirection
remove_filter( 'template_redirect', 'redirect_canonical' );

/**
 * Fix pagination on archive pages
 * After adding a rewrite rule, go to Settings > Permalinks and click Save to flush the rules cache
 */
function news_pagination() {
	add_rewrite_rule('^nieuws/?([0-9]{1,})/?$', 'index.php?pagename=nieuws&paged=$matches[1]', 'top');
}
add_action('init', 'news_pagination');
add_action( 'after_switch_theme', 'flush_rewrite_rules' );

/**
 * Instantiate the GPNL child theme.
 */
require_once __DIR__ . '/classes/class-p4nl-loader.php';
P4NL_Theme_Loader::get_instance();

// Disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php and add p4 sanitization.
remove_filter( 'pre_user_description', 'wp_filter_kses' );
add_filter( 'pre_user_description', 'wp_filter_post_kses' );
