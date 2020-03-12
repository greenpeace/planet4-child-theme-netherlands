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
$context = Timber::context();
$args = [
	'posts_per_page' => 10,
	'post_type'      => 'post',
	'paged'          => $paged,
	'p4-page-type'   => ['artikelen', 'publicaties', 'pers'],
];
$context['posts'] = new PostQuery( $args );
Timber::render('news.twig', $context);
