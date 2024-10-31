<?php

/**
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              mauwen.com
 * @since             1.2.2.8
 * @package           Protect WordPress Uploads
 *
 * @wordpress-plugin
 * Plugin Name:       Protect WordPress Uploads 
 * Plugin URI:        mauwen.com
 * Description:       This plugin makes it possible to upload and protect WordPress uploads and keep them safe for non-registered users.
 * Version:           1.2.2.8
 * Author:            Stephan Csorba
 * Author URI:        https://www.linkedin.com/in/skcsorba/
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       protect-wordpress-files
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include plugin actication
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

// The plugins folder path
define('PWPF_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('PWPF_BASE', plugin_basename( __FILE__ ));
define('PWPF_ASSETS_DIR', plugins_url('assets/', __FILE__));
define('PWPF_MINIFY', true); // set to false if you want to change javascript/css
define('PWPF_CSS_JS_VERSION', '1.6');
define('PWPF_SUPPORT', 'pwpf-support');
define('PWPF_INTRODUCTION', 'pwpf-introduction');
define('PWPF_SETTINGS', 'pwpf-settings');
define('PWPF_DOCS', 'https://www.mauwen.com/docs/protect-wordpress-uploads/getting-started/installation-activation/');

// The custom uploads folder
define('PWPF_UPLOAD_DIR', 'private');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/pwpf-init.php';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/private-media-activator.php
 */
function PWPF_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/pwpf-activator.php';
	PWPF_Activator::activate();
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/private-media-activator.php
 */
function PWPF_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/pwpf-deactivator.php';
	PWPF_Deactivator::deactivate();
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.1
 */
function PWPF_run() {

	$plugin = new PWPF_init();
	$plugin->run();

}
register_activation_hook( __FILE__, 'PWPF_activate');
register_deactivation_hook( __FILE__, 'PWPF_deactivate' );
PWPF_run();