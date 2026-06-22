<?php
/**
 * Plugin Name: Plugin Dev Lab Sample
 * Description: A tiny scaffold plugin for testing hooks, admin notices, REST routes, and debug logs in WordPress Playground.
 * Version: 0.1.0
 * Author: WP Blueprints
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PDLS_VERSION', '0.1.0' );
define( 'PDLS_PLUGIN_FILE', __FILE__ );
define( 'PDLS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

require_once PDLS_PLUGIN_DIR . 'includes/class-plugin-dev-lab-sample.php';

add_action( 'plugins_loaded', array( 'Plugin_Dev_Lab_Sample', 'init' ) );
