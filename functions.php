<?php
/**
 * Include the child css.
 */
add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 1 );
function enqueue_child_styles() {
	$plugin_version = wp_get_theme()->get( 'Version' );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', ['bootstrap', 'parent-style'], $plugin_version );
}


/*
* Include the styles required for the editor on the backend.
*/
function enqueue_editor_styles() {

	// add twitter bootstrap
	wp_enqueue_style( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css', array(), '4.1.1' );

	$plugin_version        = wp_get_theme()->get( 'Version' );
	$parent_plugin_version = filectime( get_template_directory() . '/style.css' );

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', [], $parent_plugin_version );
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $plugin_version );

}

add_action( 'enqueue_block_editor_assets', 'enqueue_editor_styles' );

// Remove the ability for editors to add custom css
function remove_custom_css_from_customizer() {
	global $wp_customize;
	$wp_customize->remove_section( 'custom_css' );
}
// add_action( 'customize_register', 'remove_custom_css_from_customizer', 11 );

function enqueue_child_scripts() {
	wp_register_script( 'navigation-bar', get_stylesheet_directory_uri() . '/assets/js/navigation-bar.js', [ 'jquery' ], '3.1.3', true );
	wp_enqueue_script( 'navigation-bar' );
	wp_register_script( 'donation', get_stylesheet_directory_uri() . '/assets/js/dontrans.js', [ 'jquery' ], '3.2.0', true );
	wp_enqueue_script( 'donation' );
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_scripts' );


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
 * Instantiate the GPNL settings menu.
 */
require_once __DIR__ . '/classes/class-p4nl-loader.php';
P4NL_Theme_Loader::get_instance();
