<?php
namespace GPNL\Theme;
/**
 * Class P4_Metabox_Register
 *
 * This creates extra meta-data that can be set to a page, post or campaign.
 */
class Metabox {

	/**
	 * Meta box prefix.
	 *
	 * @var string $prefix
	 */
	private $prefix = 'p4nl_';


	/**
	 * P4_Metabox_Register constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Class hooks.
	 */
	private function hooks() {
		add_action( 'cmb2_admin_init', [ $this, 'register_p4nl_meta_box' ] );
	}

	/**
	 * Register P4 meta box.
	 */
	public function register_p4nl_meta_box() {
		$this->register_meta_box_internal_comments();
	}

	/**
	 * Register internal comment box.
	 *
	 */
	public function register_meta_box_internal_comments() {

		$internal_comments = new_cmb2_box(
			[
				'id'           => $this->prefix . 'metabox_p4nl',
				'title'        => 'Intern Commentaar (alleen zichtbaar voor editors)',
				'object_types' => [ 'page', 'post', 'campaign' ],
				'closed'       => true,  // Keep the metabox closed by default.
			]
		);

		$internal_comments->add_field(
			[
				'desc'    => 'Vul alsjeblief voor je commentaar je naam en de datum in, bijvoorbeeld: Dirk Faber (18/12/19): Dit is mijn commentaar.',
				'id'      => $this->prefix . 'internal_comments',
				'type'    => 'textarea',
				'attributes' => [
					'style' => 'width: 100%'
				],
			]
		);

	}

}
