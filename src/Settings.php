<?php
namespace GPNL\Theme;
/**
 * Settings Class
 *
 * @package GPNL\Theme
 */

if ( ! class_exists( 'Settings' ) ) {
	/**
	 * Class Settings
	 */
	class Settings {

		/**
		 * Option key, and option page slug
		 *
		 * @var string
		 */
		private $key = 'planet4nl_options';

		/**
		 * Array of metaboxes/fields
		 *
		 * @var array
		 */
		protected $option_metabox = [];

		/**
		 * Options Page title
		 *
		 * @var string
		 */
		protected $title = '';

		/**
		 * Options Page hook
		 *
		 * @var string
		 */
		protected $options_page = '';

		/**
		 * Constructor
		 */
		public function __construct() {

			// Set our title.
			$this->title = __( 'Planet4 NL', 'planet4NL-master-theme-backend' );

			$this->fields = [
				[
					'name'    => __( 'Disco Status?', 'planet4-master-theme-backend' ),
					'id'      => 'gpnl_system_status',
					'type'    => 'select',
					'default' => 'charibase',
					'options' => [
							'charibase'    => 'Charibase',
							'systemfreeze' => 'Systemfreeze',
							'salesforce'   => 'Salesforce',
					],
				],
				[
					'name' => __( 'SystemFreeze Notif', 'planet4NL-master-theme-backend' ),
					'id'   => 'gpnl_sf_notification',
					'type' => 'textarea',
				],
				[
					'name' => __( 'VMKEPLER db host', 'planet4NL-master-theme-backend' ),
					'id'   => 'gpnl_db_host',
					'type' => 'text',
				],
				[
					'name' => __( 'VMKEPLER db', 'planet4NL-master-theme-backend' ),
					'id'   => 'gpnl_db',
					'type' => 'text',
				],
				[
					'name' => __( 'VMKEPLER user', 'planet4NL-master-theme-backend' ),
					'id'   => 'gpnl_db_user',
					'type' => 'text',
				],
				[
					'name' => __( 'VMKEPLER pass', 'planet4NL-master-theme-backend' ),
					'id'   => 'gpnl_db_pass',
					'type' => 'text',
				],
			];
			$this->hooks();
		}

		/**
		 * Initiate our hooks
		 */
		public function hooks() {
			add_action( 'admin_init', [ $this, 'init' ] );
			add_action( 'admin_menu', [ $this, 'add_options_page' ] );
		}

		/**
		 * Register our setting to WP.
		 */
		public function init() {
			register_setting( $this->key, $this->key );

			$options = get_option('planet4nl_options');
		}

		/**
		 * Add menu options page.
		 */
		public function add_options_page() {
			$this->options_page = add_options_page( $this->title, $this->title, 'manage_options', $this->key, [ $this, 'admin_page_display' ] );
		}

		/**
		 * Admin page markup. Mostly handled by CMB2.
		 */
		public function admin_page_display() {
			?>
			<div class="wrap <?php echo $this->key; ?>">
				<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
				<?php cmb2_metabox_form( $this->option_metabox(), $this->key ); ?>
			</div>
			<?php
		}

		/**
		 * Defines the theme option metabox and field configuration.
		 *
		 * @return array
		 */
		public function option_metabox() {
			return [
				'id'         => 'option_metabox',
				'show_on'    => [
					'key'   => 'options-page',
					'value' => [
						$this->key,
					],
				],
				'show_names' => true,
				'fields'     => $this->fields,
			];
		}

	}
}
