<?php
namespace GPNL\Theme;
/**
 * P4NL Theme Loader Class
 *
 * @package P4NL_CT
 */

/**
 * Class Loader.
 * Loads all necessary classes for Planet4 Netherlands Child Theme.
 */
final class Loader {
	/**
	 * A static instance of Loader.
	 *
	 * @var Loader $instance
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
	 * @return Loader
	 */
	public static function get_instance( $services = [] ) : Loader {
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

		$this->load_services( $services );
	}

	/**
	 * Inject dependencies.
	 *
	 * @param array $services The dependencies to inject.
	 */
	private function load_services( $services ) {

		$this->default_services = [
			Settings::class,
			StructuredData::class,
			Navbar::class,
			Metabox::class,
		];


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
