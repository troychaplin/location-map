<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing

namespace Location_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Location Map REST API
 *
 * @package LocationMap
 */
class RestApi {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Register REST API routes
	 */
	public function register_routes() {
		register_rest_route(
			'wp/v2/location-map',
			'/settings',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'get_settings' ),
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}

	/**
	 * Get settings
	 *
	 * @return WP_REST_Response
	 */
	public function get_settings() {
		$options = get_option( 'location_map_settings' );
		return rest_ensure_response(
			array(
				'google_maps_api_key' => isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '',
			)
		);
	}

	/**
	 * Enqueue frontend scripts and localize data
	 */
	public function enqueue_scripts() {
		// Get the API key from settings
		$options = get_option( 'location_map_settings' );
		$api_key = isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';

		// Only enqueue if we have an API key
		if ( ! empty( $api_key ) ) {
			// Enqueue the view script
			wp_enqueue_script(
				'location-map-view',
				plugins_url( 'build/view.js', dirname( __FILE__ ) ),
				array(),
				filemtime( plugin_dir_path( dirname( __FILE__ ) ) . 'build/view.js' ),
				true
			);

			// Localize the script with the API key
			wp_localize_script(
				'location-map-view',
				'locationMapSettings',
				array(
					'apiKey' => $api_key,
				)
			);
		}
	}
}
