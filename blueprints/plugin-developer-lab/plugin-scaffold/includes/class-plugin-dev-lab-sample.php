<?php
/**
 * Main sample plugin class.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin_Dev_Lab_Sample {
	public static function init() {
		add_action( 'admin_notices', array( __CLASS__, 'render_admin_notice' ) );
		add_action( 'rest_api_init', array( __CLASS__, 'register_rest_routes' ) );
		add_action( 'init', array( __CLASS__, 'write_debug_marker' ) );
	}

	public static function render_admin_notice() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		echo '<div class="notice notice-info"><p><strong>Plugin Dev Lab Sample:</strong> scaffold active. Try Query Monitor, Debug Bar, REST route <code>/wp-json/plugin-dev-lab/v1/status</code>, and the debug log.</p></div>';
	}

	public static function register_rest_routes() {
		register_rest_route(
			'plugin-dev-lab/v1',
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( __CLASS__, 'get_status' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	public static function get_status() {
		return rest_ensure_response(
			array(
				'plugin'  => 'Plugin Dev Lab Sample',
				'version' => PDLS_VERSION,
				'debug'   => defined( 'WP_DEBUG' ) && WP_DEBUG,
				'time'    => current_time( 'mysql' ),
			)
		);
	}

	public static function write_debug_marker() {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( 'Plugin Dev Lab Sample loaded at ' . current_time( 'mysql' ) );
		}
	}
}
