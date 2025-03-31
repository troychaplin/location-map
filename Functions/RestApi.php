<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing

namespace Location_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Location Map REST API Class
 */
class RestApi {
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
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
}
