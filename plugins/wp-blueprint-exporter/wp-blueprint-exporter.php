<?php
/**
 * Plugin Name: WP Blueprint Exporter
 * Description: Export a WordPress site's active theme, active plugins, and core site options as a WordPress Playground Blueprint.
 * Version: 0.1.0
 * Author: WP Blueprints
 * License: GPLv2 or later
 * Text Domain: wp-blueprint-exporter
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPBE_VERSION', '0.1.0' );
define( 'WPBE_PLUGIN_FILE', __FILE__ );
define( 'WPBE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once WPBE_PLUGIN_DIR . 'includes/class-wpbe-exporter.php';
require_once WPBE_PLUGIN_DIR . 'includes/class-wpbe-admin-page.php';

add_action( 'plugins_loaded', array( 'WPBE_Admin_Page', 'init' ) );
