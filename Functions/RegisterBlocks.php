<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing

namespace Location_Map;

use Location_Map\PluginPaths;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class RegisterBlocks
 *
 * This class is responsible for registering custom Gutenberg blocks for the plugin.
 *
 * @package Location_Map
 */
class RegisterBlocks {

	/**
	 * Cache for manifest data.
	 *
	 * @var array|null
	 */
	private static $manifest_cache = null;

	/**
	 * Constructor for the class.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'location_map_register_block' ) );
	}

	/**
	 * Registers the 'location-map' block with WordPress.
	 *
	 * This function initializes the custom Gutenberg block for the location map.
	 * It handles the registration of the block's assets, such as JavaScript and CSS,
	 * and ensures the block is available for use in the editor.
	 *
	 * @return void
	 */
	function location_map_register_block() {
		$plugin_path = PluginPaths::plugin_path();
		$build_path  = $plugin_path . '/build';

		// Check if build directory exists.
		if ( ! is_dir( $build_path ) ) {
			error_log( 'Location Map: Build directory not found at ' . $build_path );
			return;
		}

		// Load manifest with caching.
		if ( null === self::$manifest_cache ) {
			$manifest_file = $build_path . '/blocks-manifest.php';
			if ( file_exists( $manifest_file ) ) {
				self::$manifest_cache = require $manifest_file;
			} else {
				error_log( 'Location Map: Blocks manifest file not found at ' . $manifest_file );
				return;
			}
		}

		try {
			if ( function_exists( 'wp_register_block_types_from_metadata_collection' ) ) {
				wp_register_block_types_from_metadata_collection( $build_path, $build_path . '/blocks-manifest.php' );
			} else {
				if ( function_exists( 'wp_register_block_metadata_collection' ) ) {
					wp_register_block_metadata_collection( $build_path, $build_path . '/blocks-manifest.php' );
				}
				foreach ( array_keys( self::$manifest_cache ) as $block_type ) {
					$block_type = sanitize_key( $block_type );
					$block_path = $build_path . "/{$block_type}";
					if ( is_dir( $block_path ) ) {
						register_block_type( $block_path );
					} else {
						error_log( 'Location Map: Block directory not found at ' . $block_path );
					}
				}
			}
		} catch ( \Exception $e ) {
			error_log( 'Location Map: Error registering blocks: ' . $e->getMessage() );
		}
	}
}
