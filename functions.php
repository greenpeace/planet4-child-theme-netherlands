<?php

/**
 * Additional code for the child theme goes in here.
 */

add_action( 'wp_enqueue_scripts', 'enqueue_child_styles', 99 );

function enqueue_child_styles() {
	$css_creation = filectime( get_stylesheet_directory() . '/style.css' );

	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', [], $css_creation );
}

// register a new wpmenu for donation dropdown
function p4nl_donation_menu() {
	register_nav_menu( 'donation-menu', __( 'P4NL Donation Menu' ) );
}
add_action( 'init', 'p4nl_donation_menu' );


// filter for adding stuff to the timber context
add_filter(
	'timber_context', function ( $context ) {
		global $timber_context;
		$timber_context = is_array( $timber_context ) ? $timber_context : array();
		$context        = is_array( $context ) ? $context : array();
		$context        = array_merge( $timber_context, $context );

		return $context;
	}
);

// helperfunction to actually add stuff to the timber context
function add_to_timbercontext( $key, $val ) {
	global $timber_context;
	$timber_context[ $key ] = $val;
}

// add the P4NL donation menu to to the timbercontact
add_to_timbercontext( 'donation_navbar_menu', new TimberMenu( 'donation_menu' ) );
