<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.wecodeart.com/
 * @since      1.0.0
 *
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\I18N
 */

namespace WCA\EXT\Google;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\I18N
 * @author     Bican Marian Valeriu <marianvaleriubican@gmail.com>
 */
class I18N {

	use \WeCodeArt\Singleton;
	
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'wca-google', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}
}
