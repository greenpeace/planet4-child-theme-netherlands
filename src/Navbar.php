<?php
namespace GPNL\Theme;
/**
 * Navbar customization
 *
 * @package GPNL\Theme
 */
use Timber\Menu as TimberMenu;
if ( ! class_exists( 'Navbar' ) ) {
	/**
	 * Class Navbar
	 */

	class Navbar {

		/**
		 * Constructor
		 */
		public function __construct() {
			$this->hooks();
		}

		/**
		 * Initiate our hooks
		 */
		public function hooks() {
			register_nav_menu( 'donation-menu', __( 'P4NL Donation Menu', 'planet4-master-theme-backend' ) );
			$this->add_to_timbercontext( 'donation_navbar_menu', new TimberMenu( 'donation_menu' ) );
		}

		/**
		 * Add new items to the general Timber context
		 *
		 * @param mixed $key The key with which to store data.
		 * @param mixed $val The data to be stored.
		 */
		private function add_to_timbercontext( $key, $val ) {
			add_filter(
				'timber_context',
				function ( $context ) {
					global $timber_context;
					$timber_context = is_array( $timber_context ) ? $timber_context : array();
					$context        = is_array( $context ) ? $context : array();
					return array_merge( $timber_context, $context );
				}
			);

			global $timber_context;
			$timber_context[ $key ] = $val;
		}
	}
}
