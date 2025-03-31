<?php // phpcs:ignore Squiz.Commenting.FileComment.Missing

namespace Location_Map;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Location Map Settings Class
 */
class Settings {
	/**
	 * Option name in wp_options table
	 *
	 * @var string
	 */
	private $option_name = 'location_map_settings';

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Add settings page to WordPress admin menu
	 */
	public function add_settings_page() {
		add_options_page(
			__( 'Location Map Settings', 'location-map' ),
			__( 'Location Map', 'location-map' ),
			'manage_options',
			'location-map-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting(
			'location_map_settings',
			$this->option_name,
			array( $this, 'sanitize_settings' )
		);

		add_settings_section(
			'location_map_main_section',
			__( 'Google Maps Settings', 'location-map' ),
			array( $this, 'render_section_description' ),
			'location-map-settings'
		);

		add_settings_field(
			'google_maps_api_key',
			__( 'Google Maps API Key', 'location-map' ),
			array( $this, 'render_api_key_field' ),
			'location-map-settings',
			'location_map_main_section'
		);
	}

	/**
	 * Sanitize settings
	 *
	 * @param array $input Input array.
	 * @return array Sanitized input.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		if ( isset( $input['google_maps_api_key'] ) ) {
			$sanitized['google_maps_api_key'] = sanitize_text_field( $input['google_maps_api_key'] );
		}

		return $sanitized;
	}

	/**
	 * Render section description
	 */
	public function render_section_description() {
		echo '<p>' . esc_html__( 'Enter your Google Maps API key below. You can get an API key from the Google Cloud Console.', 'location-map' ) . '</p>';
	}

	/**
	 * Render API key field
	 */
	public function render_api_key_field() {
		$options = get_option( $this->option_name );
		$api_key = isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';
		?>
		<input type="text" 
				name="<?php echo esc_attr( $this->option_name . '[google_maps_api_key]' ); ?>" 
				value="<?php echo esc_attr( $api_key ); ?>" 
				class="regular-text"
		/>
		<?php
	}

	/**
	 * Render settings page
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'location_map_settings' );
				do_settings_sections( 'location-map-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Get Google Maps API key
	 *
	 * @return string
	 */
	public static function get_api_key() {
		$options = get_option( 'location_map_settings' );
		return isset( $options['google_maps_api_key'] ) ? $options['google_maps_api_key'] : '';
	}
}
