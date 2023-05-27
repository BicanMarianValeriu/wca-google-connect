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
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       wca-google
 * Domain Path:       /languages
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */
namespace WCA\EXT\Google;

// If this file is called directly, abort.
defined( 'WPINC' ) || die;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WCA_GOOGLE_EXT', 		__FILE__ );
define( 'WCA_GOOGLE_EXT_VER', 	get_file_data( WCA_GOOGLE_EXT, [ 'Version' ] )[0] ); // phpcs:ignore
define( 'WCA_GOOGLE_EXT_DIR', 	plugin_dir_path( WCA_GOOGLE_EXT ) );
define( 'WCA_GOOGLE_EXT_URL', 	plugin_dir_url( WCA_GOOGLE_EXT ) );
define( 'WCA_GOOGLE_EXT_BASE', 	plugin_basename( WCA_GOOGLE_EXT ) );

require_once( WCA_GOOGLE_EXT_DIR . '/includes/class-autoloader.php' );

new Autoloader( 'WCA\EXT\Google', WCA_GOOGLE_EXT_DIR . '/includes' );
new Autoloader( 'WCA\EXT\Google', WCA_GOOGLE_EXT_DIR . '/frontend' );
new Autoloader( 'WCA\EXT\Google', WCA_GOOGLE_EXT_DIR . '/admin' );

// Activation/Deactivation Hooks
register_activation_hook( WCA_GOOGLE_EXT, [ Activator::class, 'run' ] );
register_deactivation_hook( WCA_GOOGLE_EXT, [ Deactivator::class, 'run' ] );

/**
 * Hook the extension after WeCodeArt is Loaded
 */
add_action( 'wecodeart/theme/loaded', fn() => wecodeart( 'integrations' )->register( 'plugin/google', __NAMESPACE__ ) );