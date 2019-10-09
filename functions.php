<?php
/**
 * Include the child css.
 */

function enqueue_child_styles() {
	$plugin_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [ 'bootstrap', 'parent-style' ], $plugin_version );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 1 );

function enqueue_child_scripts() {
	wp_register_script( 'navigation-bar', get_stylesheet_directory_uri() . '/assets/js/navigation-bar.js', [ 'jquery' ], '3.1.3', true );
	wp_enqueue_script( 'navigation-bar' );
	wp_register_script( 'donation', get_stylesheet_directory_uri() . '/assets/js/dontrans.js', [ 'jquery' ], '3.2.0', true );
	wp_enqueue_script( 'donation' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_scripts' );

function remove_custom_css_from_customizer() {
	if ( !current_user_can( 'administrator' ) ) {
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
		$title = 'Posttitel toevoegen (voor de URL)';
	} else {
		$title = 'Paginatitel toevoegen (voor de URL)';
	}
	return $title;
}

add_filter( 'enter_title_here', 'change_title_placeholders' );

/**
 * Instantiate the GPNL child theme.
 */
require_once __DIR__ . '/classes/class-p4nl-loader.php';
P4NL_Theme_Loader::get_instance();
