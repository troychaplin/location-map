<?php
/**
 * Plugin Name:       Location Map
 * Description:       A plugin that provides a map of locations.
 * Version:           0.1.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Troy Chaplin
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       location-map
 *
 * @package Location_Map
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
	exit( 'This plugin requires PHP 7.4 or higher.' );
}

// Check WordPress version.
if ( version_compare( get_bloginfo( 'version' ), '6.7', '<' ) ) {
	exit( 'This plugin requires WordPress 6.7 or higher.' );
}

// Define plugin constants.
define( 'LOCATION_MAP_VERSION', '0.1.0' );
define( 'LOCATION_MAP_PATH', plugin_dir_path( __FILE__ ) );
define( 'LOCATION_MAP_URL', plugin_dir_url( __FILE__ ) );

// Include Composer's autoload file.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Plugin activation hook.
 */
function location_map_activate() {
	// Clear any cached data.
	wp_cache_flush();

	// Add activation notice.
	add_option( 'location_map_activation_notice', true );
}
register_activation_hook( __FILE__, 'location_map_activate' );

/**
 * Plugin deactivation hook.
 */
function location_map_deactivate() {
	// Clear any cached data.
	wp_cache_flush();
}
register_deactivation_hook( __FILE__, 'location_map_deactivate' );

/**
 * Initialize the plugin.
 */
function location_map_init() {
	// Define core classes that are always needed.
	$core_classes = array(
		\Location_Map\PluginPaths::class,
		\Location_Map\RegisterBlocks::class,
	);

	// Instantiate all classes.
	foreach ( $core_classes as $class ) {
		new $class();
	}
}
add_action( 'plugins_loaded', 'location_map_init' );
