<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing

namespace Location_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class PluginPaths
 *
 * This class provides methods to handle and retrieve various paths related to the plugin.
 *
 * @package Location_Map
 */
class PluginPaths {

	/**
	 * Cache for plugin URL
	 *
	 * @var string|null
	 */
	private static $plugin_url_cache = null;

	/**
	 * Cache for plugin path
	 *
	 * @var string|null
	 */
	private static $plugin_path_cache = null;

	/**
	 * Cache for plugin version
	 *
	 * @var string|null
	 */
	private static $plugin_version_cache = null;

	/**
	 * Get the URL to the plugin directory.
	 *
	 * @return string The URL to the plugin directory.
	 */
	public static function plugin_url() {
		if ( null === self::$plugin_url_cache ) {
			self::$plugin_url_cache = defined( 'LOCATION_MAP_URL' ) ? LOCATION_MAP_URL : '';
			if ( empty( self::$plugin_url_cache ) ) {
				error_log( 'Location Map: LOCATION_MAP_URL constant is not defined.' );
			}
		}
		return self::$plugin_url_cache;
	}

	/**
	 * Get the path to the plugin directory.
	 *
	 * @return string The path to the plugin directory.
	 */
	public static function plugin_path() {
		if ( null === self::$plugin_path_cache ) {
			self::$plugin_path_cache = defined( 'LOCATION_MAP_PATH' ) ? LOCATION_MAP_PATH : '';
			if ( empty( self::$plugin_path_cache ) ) {
				error_log( 'Location Map: LOCATION_MAP_PATH constant is not defined.' );
			}
		}
		return self::$plugin_path_cache;
	}

	/**
	 * Get the version of the plugin.
	 *
	 * @return string The version of the plugin.
	 */
	public static function plugin_version() {
		if ( null === self::$plugin_version_cache ) {
			self::$plugin_version_cache = defined( 'LOCATION_MAP_VERSION' ) ? LOCATION_MAP_VERSION : '';
			if ( empty( self::$plugin_version_cache ) ) {
				error_log( 'Location Map: LOCATION_MAP_VERSION constant is not defined.' );
			}
		}
		return self::$plugin_version_cache;
	}
}
