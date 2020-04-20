<?php
/**
 * Template Name: Nieuwspagina
 * The template for displaying evergreen pages.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

use Timber\Timber;
use Timber\PostQuery;

global $paged;
if (!isset($paged) || !$paged){
	$paged = 1;
}
$context         = Timber::context();
$post            = new P4_Post();
$context['post'] = $post;

$args = [
	'posts_per_page' => 10,
	'post_type'      => 'post',
	'paged'          => $paged,
	'p4-page-type'   => ['artikelen', 'publicaties', 'pers'],
];
$context['posts'] = new PostQuery( $args, 'P4_Post' );
global $wp;
$current_url = home_url( add_query_arg( array(), $wp->request ) );
$current_url = str_replace( "/".$paged, "", $current_url);
$prefs = [
	'base'     => $current_url."/%_%", // http://example.com/all_posts.php%_% : %_% is replaced by format (below)
	'format'   => '%#%', // ?page=%#% : %#% is replaced by the page number
	'current'  => $paged,
	'end_size' => 0,
	'mid_size' => 3,
];
$context['pagination'] = $context['posts']->pagination( $prefs );
$context["edge"]       = in_array($context['pagination']->current, range(5,$context['pagination']->total -4 ));
Timber::render('news.twig', $context);
