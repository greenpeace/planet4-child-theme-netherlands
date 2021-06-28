<?php
/**
 * Assorted tweaks
 *
 * @package GPNL\Theme
 */

namespace GPNL\Theme;


class Tweaks
{
	public function __construct()
	{
		// GPNL specific added tweaks
		add_action('init', [ $this, 'set_page_template']);
		add_action('init', [ $this, 'datawrapper_oembed_provider']);
		add_action('init', [ $this, 'check_demopage']);
		add_filter('avatar_defaults', [ $this, 'wpb_new_gravatar']);

		// Modified behaviour
		add_action('p4_action_tag_page_redirect', [ $this, 'p4_child_theme_tag_page_redirect']);
		add_filter('pre_get_posts', [ $this, 'p4_child_theme_set_post_order_in_admin'], 1);
		$this->allow_tags_in_author_bio();
		add_theme_support( 'disable-custom-font-sizes' );
		add_theme_support( 'disable-custom-colors' );
		add_theme_support( 'disable-custom-gradients' );
		add_theme_support( 'editor-styles' );

		// Localization
		add_action('init', [ $this, 'rename_rewrite_base' ]);
		add_filter('enter_title_here', [ $this, 'change_title_placeholders']);
	}

	/**
	 * Change the title placeholders for the posts and pages.
	 */
	public static function change_title_placeholders(): string
	{
		$screen = get_current_screen();

		if ('post' === $screen->post_type) {
			$title = 'Posttitel toevoegen';
		} else {
			$title = 'Paginatitel toevoegen (voor de URL)';
		}
		return $title;
	}

	/**
	 * Default template for page (if GPNL plugin is activated).
	 */
	public static function set_page_template(): void
	{
		if (is_plugin_active('planet4-gpnl-plugin-gutenberg-blocks/planet4-gutenberg-blocks.php')) {
			$post_type_object = get_post_type_object('page');
			$post_type_object->template = [['planet4-gpnl-blocks/hero-image'], ['core/paragraph'],];
		}
	}

	/**
	 * Localize base for pagination and author urls
	 */
	public static function rename_rewrite_base(): void
	{
		global $wp_rewrite;
		$wp_rewrite->author_base = 'auteur';
		$wp_rewrite->pagination_base = 'pagina';
		flush_rewrite_rules();
	}

	/**
	 * Add the green "G" as the default avatar for authors
	 */
	public static function wpb_new_gravatar($avatar_defaults): array
	{
		$default_gravatar = 'https://storage.googleapis.com/planet4-netherlands-stateless/2020/06/7c8213f7-letterg_2019_greenbackground.png';
		$avatar_defaults[$default_gravatar] = "Default Gravatar";
		return $avatar_defaults;
	}

	/**
	 * Modify the behavior of tag pages when a redirect is set. The master theme will just load the content of the page,
	 * we'll redirect instead.
	 *
	 * @param $redirect_page
	 */
	public static function p4_child_theme_tag_page_redirect($redirect_page): void
	{
		$permalink = get_permalink($redirect_page);

		if ($permalink !== false) {
			wp_safe_redirect($permalink, 301);
			exit;
		}
	}

	/**
	 * Change default sort order of pages in Wordpress admin
	 */
	public static function p4_child_theme_set_post_order_in_admin($wp_query)
	{
		global $pagenow;

		if (is_admin() && 'edit.php' === $pagenow && !isset($_GET['orderby'])) {
			$wp_query->set('orderby', 'post_modified');
			$wp_query->set('order', 'desc');
			return $wp_query;
		}
	}

	/**
	 * Add support for datawrapper embeds
	 */
	public static function datawrapper_oembed_provider(): void
	{
		wp_oembed_add_provider('https://datawrapper.dwcdn.net/*', 'https://api.datawrapper.de/v3/oembed', false);
	}

	/**
	 * Make sure the demo page is always published in non-prod environments
	 */
	public static function check_demopage(): void
	{
		$hostname = $_SERVER['HOSTNAME'];
		$demopage_status = get_post_status(43617);
		if (($hostname !== "www.greenpeace.org") && $demopage_status !== false && $demopage_status !== 'publish') {
			wp_publish_post(43617);
		}
	}

	/**
	 * Disable WordPress sanitization to allow more than just $allowedtags from /wp-includes/kses.php and add p4 sanitization.
	 */
	public function allow_tags_in_author_bio(): void
	{
		remove_filter('pre_user_description', 'wp_filter_kses');
		add_filter('pre_user_description', 'wp_filter_post_kses');
	}

}
