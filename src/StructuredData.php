<?php
namespace GPNL\Theme;
/**
 * Structured data customization
 *
 * @package P4NL_CT
 */

use P4\MasterTheme\User;
if ( ! class_exists( 'StructuredData' ) ) {
	/**
	 * Class P4NL_Theme_Structured data
	 */

	class StructuredData {

		/**
		 * Indexed array of relevant data for structured data
		 *
		 * @var array $data
		 */
		private $data = [];

		/**
		 * The Wordpress Post Object
		 *
		 * @var object $post
		 */
		private $post = [];


		/**
		 * Constructor
		 */
		public function __construct() {
			$this->hooks();
		}

		/**
		 * Initiate our hooks
		 */
		public function hooks(): void
		{
			add_action('the_post', [$this, 'enhanced_structured_data']);
		}

		/**
		 * Add new items to the general Timber context
		 *
		 * @param mixed $key The key with which to store data.
		 * @param mixed $val The data to be stored.
		 */
		private function add_to_timbercontext( $key, $val ): void
		{
			add_filter(
				'timber_context',
				function ( $context ) {
					global $timber_context;
					$timber_context = is_array( $timber_context ) ? $timber_context : [];
					$context        = is_array( $context ) ? $context : [];
					return array_merge( $timber_context, $context );
				}
			);

			global $timber_context;
			$timber_context[ $key ] = $val;
		}

		public function enhanced_structured_data(): void
		{
			global $post,$wp;
			if (!is_object($post)){return;}
			$this->post = $post;

			$this->data = [
				"wp_category"=>get_the_category()[0]->name ?? "Greenpeace",
				'wp_excerpt'=>get_the_excerpt(),
				'wp_title'=>$this->post->post_title,
				'wp_datetime_publish'=>get_the_date('c'),
				'wp_datetime_modified'=>get_the_modified_date('c'),
				'current_url'=>home_url( $wp->request ),
				'site_url'=>get_site_url(),
//				'post_type'=>$post->post_type,
			];

			$this->data['image_url'] =$this->get_image();



			$this->add_to_timbercontext('enhanced_structured_data', $this->data);
			$this->add_to_timbercontext('structured_breadcrumbs', $this->get_breadcrumbs());
			$this->add_to_timbercontext('structured_organization', $this->get_organization(0));

			//	Can be removed once more images have been enabled
			$has_image = is_string($this->get_image());
			if($has_image){
				$this->add_to_timbercontext('structured_page_data', $this->get_page_data());
			}
		}

		private function get_breadcrumbs(): array
		{
			global $post, $wp;
			$tags=get_the_tags();
			$tag_bool= is_array($tags);

			$breadcrumbs = [
				[
					'@type'=> 'ListItem',
					'position'=> 1,
					'name'=> $this->data['wp_category'],
					'item'=> strtolower($this->data['site_url'].'/'.$this->data['wp_category'])
				],
				[
					'@type'=> 'ListItem',
					'position'=> $tag_bool ? 3 : 2,
					'name'=> get_the_title()
				]
			];

			if ($tag_bool){
				$breadcrumb_tag=[[
					'@type'=> 'ListItem',
					'position'=> 2,
					'name'=> $tags[0]->name,
					'item'=> get_tag_link($tags[0]),
				]];
				array_splice( $breadcrumbs, 1, 0, $breadcrumb_tag );
			}

			return [
				'@context'=> 'https://schema.org',
				'@type'=> 'BreadcrumbList',
				'@id'=> $this->data['wp_category'].'#breadcrumbs',
				'name'=> 'Breadcrumbs',
				'itemListElement'=> [$breadcrumbs]
			];
		}

		private function get_author(): array
		{
			global $wp;
			$author_id = $this->post->post_author;
			$author = new User($author_id);
			$current_url=home_url( $wp->request );
			$organization_bool_username = $author->display_name === 'Greenpeace Nederland';

			if ($organization_bool_username){ return $this->get_organization(0); }

			return [
				'@context'=> 'https://schema.org',
				'@type'=> 'Person',
				'@id'=> $current_url.'/'.'#author',
				'name'=> get_the_author_meta('display_name', $author_id),
				'image'=> get_avatar_url($author_id),
				'description' => get_the_author_meta('description', $author_id),
			];
		}

		private function get_organization($publisher): array
		{
			global $wp;
			$author_id = 20;
			$current_url=home_url( $wp->request );

			if ($publisher){
				return [
					'@context'=> 'https://schema.org',
					'@type'=> 'Organization',
					'@id'=> $this->data['site_url'].'/'.'#organization',
					'name'=> get_the_author_meta('display_name', $author_id),
					'logo'=> [
						'@type' => "ImageObject",
						'url' => $this->data['site_url'].'/wp-content/themes/planet4-master-theme/images/Greenpeace-logo.png',
					]
				];
			}

			return [
				'@context'=> 'https://schema.org',
				'@type'=> 'Organization',
				'@id'=> $current_url.'/'.'#author',
				'name'=> get_the_author_meta('display_name', $author_id),
				'image'=> get_avatar_url($author_id),
				'description' => get_the_author_meta('description', $author_id),
			];
		}

		private function get_page_data(): array
		{
			// Article  ✓
			// NewsArticle
			// JobPosting
			// General page
			$article_info = [
				'@context'=> 'https://schema.org',
				'@type'=> 'Article',
				'headline'=> $this->data['wp_title'],
				'abstract'=> $this->data['wp_excerpt'],
				'image'=> $this->data['image_url'],
				'dateModified'=> $this->data['wp_datetime_modified'],
				'datePublished'=> $this->data['wp_datetime_publish'],
				'author'=> $this->get_author(),
				'publisher'=> $this->get_organization(1)
			];

			return $article_info;
		}

		private function get_image()
		{
			// Primarily return OG Image
			if (is_array($this->post->custom) && isset($this->post->custom['p4_og_image'])) {
				return $this->post->custom['p4_og_image'];
			}
			// Otherwise Thumbnail
			$has_thumbnail = is_string(get_the_post_thumbnail_url($this->post->ID, 'full'));
			if ($has_thumbnail) { return get_the_post_thumbnail_url($this->post->ID, 'full'); }

			// Or Background image
			if (is_array($this->post->custom) && isset($this->post->custom['p4_background_image_override'])) {
				return $this->post->custom['p4_background_image_override'];
			}

			// If all else fails first image in post
			$regex = '/<!-- wp:image {"id":(\d*),.*-->/m';
			if (preg_match($regex, $this->post->post_content, $matches, PREG_OFFSET_CAPTURE, 0)){
				$image_id = $matches[1][0];
				$image_data = wp_get_attachment_image_src( $image_id, 'full' );
				if ( $image_data ) {
					return $image_data[0];
				}
			}

			// or give up and just return the logo
			return $this->data['site_url'].'/wp-content/themes/planet4-master-theme/images/Greenpeace-logo.png';
		}
	}
}
