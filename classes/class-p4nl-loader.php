<?php
/**
 * P4NL Theme Loader Class
 *
 * @package P4NL_CT
 */

/**
 * Class P4NL_Theme_Loader.
 * Loads all necessary classes for Planet4 Netherlands Child Theme.
 */
final class P4NL_Theme_Loader {
	/**
	 * A static instance of Loader.
	 *
	 * @var P4NL_Theme_Loader $instance
	 */
	private static $instance;
	/**
	 * Indexed array of all the classes/services that are needed.
	 *
	 * @var array $services
	 */
	private $services;
	/**
	 * Indexed array of all the classes/services that are used by Planet4.
	 *
	 * @var array $default_services
	 */
	private $default_services;

	/**
	 * Singleton creational pattern.
	 * Makes sure there is only one instance at all times.
	 *
	 * @param array $services The Controller services to inject.
	 *
	 * @return P4NL_Theme_Loader
	 */
	public static function get_instance( $services = [] ) : P4NL_Theme_Loader {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self( $services );
		}
		return self::$instance;
	}

	/**
	 * P4NL_Loader constructor.
	 *
	 * @param array $services The dependencies to inject.
	 */
	private function __construct( $services ) {
		$this->load_files();
		$this->load_services( $services );
	}

	/**
	 * Load required files.
	 */
	private function load_files() {
		try {
			// Class names need to be prefixed with P4 and should use capitalized words separated by underscores. Any acronyms should be all upper case.
			spl_autoload_register(
				function ( $class_name ) {
					if ( strpos( $class_name, 'P4NL_Theme' ) !== false ) {
						$file_name = 'class-' . str_ireplace( [ 'p4nl\\', '_' ], [ '', '-' ], strtolower( $class_name ) );
						require_once __DIR__ . '/' . $file_name . '.php';
					}
				}
			);
		} catch ( \Exception $e ) {
			echo esc_html( $e->getMessage() );
		}
	}

	/**
	 * Inject dependencies.
	 *
	 * @param array $services The dependencies to inject.
	 */
	private function load_services( $services ) {

		$this->default_services = [
			'P4NL_Theme_Settings',
			'P4NL_Theme_MediaLibrary',
			'P4NL_Theme_Navbar',
			'P4NL_Theme_Metabox_Register',
		];

		if ( is_admin() ) {
			global $pagenow;

		}

		$services = array_merge( $services, $this->default_services );
		if ( $services ) {
			foreach ( $services as $service ) {
				$this->services[ $service ] = new $service();
			}
		}
	}

	/**
	 * Gets the loaded services.
	 *
	 * @return array The loaded services.
	 */
	public function get_services() : array {
		return $this->services;
	}
}
