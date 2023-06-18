<?php

/**
 * The frontend-specific functionality of the plugin.
 *
 * @link       https://www.wecodeart.com/
 * @since      1.0.0
 *
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\Frontend
 */

namespace WCA\EXT\Google;

use WCA\EXT\Google;
use function WeCodeArt\Functions\get_prop;

/**
 * The frontend-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the frontend-specific stylesheet and JavaScript.
 *
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\Frontend
 * @author     Bican Marian Valeriu <marianvaleriubican@gmail.com>
 */
class Frontend {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The config of this plugin.
	 *
	 * @since	1.0.0
	 * @access	private
	 * @var		mixed    $config    The config of this plugin.
	 */
	private $config;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param	string    $plugin_name	The name of this plugin.
	 * @param	string    $version		The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $config ) {
		$this->plugin_name	= $plugin_name;
		$this->version 		= $version;
		$this->config 		= $config;
	}

	/**
	 * Generate Output
	 *
	 * @since 	2.0.0
	 * @version	2.0.0
	 *
	 * @return 	void
	 */
	public function generate_output() {
		// Merge config with defaults
		$config = wp_parse_args( get_prop( $this->config, 'fields', [] ), Admin::get_defaults() );

		// Get only required data.
		$config = array_map( function( $item ) {
			return wp_array_slice_assoc( $item, [ 'id', 'hook', 'callback' ] );
		}, $config );

		$options = wecodeart_option( 'google', [] );

		// Generate output
		foreach( $config as $field ) {
			if( ! isset( $field['id'] ) || ! isset( $field['hook'] ) || ! isset( $field['callback'] ) ) continue;

			$callback	= $field['callback'];

			$value		= get_prop( $options, $field['id'] );

			if( empty( $value ) ) continue;
			
			add_action( $field['hook'], function() use ( $callback, $value ) {
				if( is_callable( $callback ) ) {
					return $callback( $value );
				}

				return printf( $callback . "\n", esc_attr( $value ) );
			} );
		}
	}
}
