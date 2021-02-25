<?php
/**
 * Structured data customization
 *
 * @package P4NL_CT
 */

use P4\MasterTheme\User;
if ( ! class_exists( 'P4NL_Theme_Structured_data' ) ) {
	/**
	 * Class P4NL_Theme_Structured data
	 */

	class P4NL_Theme_Structured_data {

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
			// thumbnail > og image > background override > first image
			$has_thumbnail = is_string(get_the_post_thumbnail_url($this->post->ID, 'full'));
			if(!$has_thumbnail){
				$regex = '/<!-- wp:image {"id":(\d*),.*-->/m';
				preg_match($regex, $post->post_content, $matches, PREG_OFFSET_CAPTURE, 0);
				$image_id = $matches[1][0];
				$image_url = wp_get_attachment_image_url($image_id);
//				echo "<pre>".xdebug_var_dump($image_id)."</pre>";
//				echo "<pre>".xdebug_var_dump($image_url)."</pre>";
			}
			else{
				$image_url = get_the_post_thumbnail_url($this->post->ID, 'full');
			}



			$this->data = [
				"wp_category"=>get_the_category()[0]->name ?? "Greenpeace",
				'wp_excerpt'=>get_the_excerpt(),
				'wp_title'=>$this->post->post_title,
				'wp_datetime_publish'=>get_the_date('c'),
				'wp_datetime_modified'=>get_the_modified_date('c'),
				'current_url'=>home_url( $wp->request ),
				'site_url'=>get_site_url(),
				'image_url'=>$image_url,
//				'post_type'=>$post->post_type,
			];



			$this->add_to_timbercontext('enhanced_structured_data', $this->data);
			$this->add_to_timbercontext('structured_breadcrumbs', $this->get_breadcrumbs());
			$this->add_to_timbercontext('structured_organization', $this->get_organization(0));
			if($has_thumbnail){
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
			$breadcrumb_tag=[[
				'@type'=> 'ListItem',
				'position'=> 2,
				'name'=> $tags[0]->name,
				'item'=> get_tag_link($tags[0]),
			]];

			if ($tag_bool){array_splice( $breadcrumbs, 1, 0, $breadcrumb_tag );}

			return [
				'@context'=> 'http://schema.org',
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
				'@context'=> 'http://schema.org',
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
					'@context'=> 'http://schema.org',
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
				'@context'=> 'http://schema.org',
				'@type'=> 'Organization',
				'@id'=> $current_url.'/'.'#author',
				'name'=> get_the_author_meta('display_name', $author_id),
				'image'=> get_avatar_url($author_id),
				'description' => get_the_author_meta('description', $author_id),
			];
		}

		private function get_page_data(): array
		{
			// Article
			// NewsArticle
			// JobPosting
			// General page
			$article_info = [
				'@context'=> 'http://schema.org',
				'@type'=> 'Article',
				'headline'=> $this->data['wp_title'],
				'abstract'=> $this->data['wp_excerpt'],
				'image'=> $this->data['image_url'],
				'dateModified'=> $this->data['wp_datetime_modified'],
				'datePublished'=> $this->data['wp_datetime_publish'],
				'author'=> $this->get_author(),
				'publisher'=> $this->get_organization(1)
			];

//			echo "<pre>".xdebug_var_dump($article_info)."</pre>";

			return $article_info;
		}
	}
}
