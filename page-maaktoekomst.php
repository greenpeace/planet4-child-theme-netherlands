<?php
/**
 * Template Name: Maak Toekomst
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use GPNL\Theme\AssetHelper;
use P4\MasterTheme\Context;
use P4\MasterTheme\Post;
use Timber\Timber;

$context        = Timber::get_context();
$post           = new Post(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

/*
 * This is all we need for this template, all the assets coming from the single entrypoint.
 */
$assetHelper = new AssetHelper();
$assetHelper->enqueue_assets_from_entry('maak-toekomst');

$page_meta_data = get_post_meta( $post->ID );
$page_meta_data = array_map( 'reset', $page_meta_data );

// Set Navigation Issues links.
$post->set_issues_links();

// Get Navigation Campaigns links.
$page_tags = wp_get_post_tags( $post->ID );
$tags      = [];

if ( is_array( $page_tags ) && $page_tags ) {
	foreach ( $page_tags as $page_tag ) {
		$tags[] = [
			'name' => $page_tag->name,
			'link' => get_tag_link( $page_tag ),
		];
	}
	$context['campaigns'] = $tags;
}

// Set GTM Data Layer values.
$post->set_data_layer();
$data_layer = $post->get_data_layer();

Context::set_header( $context, $page_meta_data, $post->title );
Context::set_background_image( $context );
Context::set_og_meta_fields( $context, $post );
Context::set_campaign_datalayer( $context, $page_meta_data );

$context['post']                = $post;
$context['social_accounts']     = $post->get_social_accounts( $context['footer_social_menu'] );
$context['page_category']       = $data_layer['page_category'];
$context['post_tags']           = implode( ', ', $post->tags() );
$context['custom_body_classes'] = 'brown-bg ';

if ( post_password_required( $post->ID ) ) {

	// Hide the page title from links to the extra feeds.
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	$context['login_url'] = wp_login_url();

	Timber::render( 'single-page.twig', $context );
} else {
	Timber::render( [ 'page-maaktoekomst.twig', 'page-' . $post->post_name . '.twig', 'page.twig' ], $context );
}
