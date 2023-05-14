<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.wecodeart.com/
 * @since             1.0.0
 * @package           WCA\EXT\Google
 *
 * @wordpress-plugin
 * Plugin Name:       WCA: Google Tools Extension
 * Plugin URI:        https://www.wecodeart.com/
 * Description:       WCA Google Tools extension for WeCodeArt Framework - Allows you to verify site ownership, add Google Analytics and connect Google Adsense Publisher account.
 * Version:           2.0.2
 * Author:            Bican Marian Valeriu
 * Author URI:        https://www.wecodeart.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wca-google
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WCA_GOOGLE_EXT', __FILE__ );
define( 'WCA_GOOGLE_EXT_VER', '2.0.2' );
define( 'WCA_GOOGLE_EXT_DIR', plugin_dir_path( WCA_GOOGLE_EXT ) );
define( 'WCA_GOOGLE_EXT_URL', plugin_dir_url( WCA_GOOGLE_EXT ) );

require_once( __DIR__ . '/includes/class-autoloader.php' );

new WCA\EXT\Google\Autoloader( 'WCA\EXT\Google', __DIR__ . '/includes' );
new WCA\EXT\Google\Autoloader( 'WCA\EXT\Google', __DIR__ . '/frontend' );
new WCA\EXT\Google\Autoloader( 'WCA\EXT\Google', __DIR__ . '/admin' );

/**
 * The code that runs during plugin activation.
 */
function activate_wca_google() {
	WCA\EXT\Google\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_wca_google() {
	WCA\EXT\Google\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wca_google' );
register_deactivation_hook( __FILE__, 'deactivate_wca_google' );

/**
 * Hook the extension after WeCodeArt is Loaded
 */
add_action( 'wecodeart/theme/loaded', function() {
	wecodeart( 'integrations' )->register( 'extension/google', WCA\EXT\Google::class );
} );