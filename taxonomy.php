<?php
/**
 * The template for displaying Taxonomy pages.
 *
 * Used to display taxonomy-type pages
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package  WordPress
 * @subpackage  Timber
 */

use Timber\PostQuery;
use Timber\Timber;

$templates = [ 'taxonomy.twig' ];

$context             = Timber::get_context();
$context['taxonomy'] = get_queried_object();
$context['wp_title'] = $context['taxonomy']->name;

//$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$paged = 1;

$post_args = [
	'posts_per_page' => 10,
	'post_type'      => 'post',
	'paged'          => $paged,
	'p4-page-type'   => ['artikelen', 'publicaties', 'pers'],
];

$context['page_category']   = 'Post Type Page';
$context['dummy_thumbnail'] = get_template_directory_uri() . '/images/dummy-thumbnail.png';

$pagetype_posts   = new PostQuery( $post_args, 'P4_Post' );
$context['posts'] = $pagetype_posts;
Timber::render( $templates, $context );


$pagination = get_the_posts_pagination( array(
	'mid_size' => 2,
	'prev_text' => __( 'Newer', 'textdomain' ),
	'next_text' => __( 'Older', 'textdomain' ),
) );
