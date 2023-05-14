<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wecodeart.com/
 * @since      1.0.0
 *
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\Admin
 */

namespace WCA\EXT\Google;

use WeCodeArt\Admin\Notifications;
use WeCodeArt\Admin\Notifications\Notification;
use function WeCodeArt\Functions\get_prop;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\Admin
 * @author     Bican Marian Valeriu <marianvaleriubican@gmail.com>
 */
class Admin {

	const NOTICE_ID = 'wecodeart/extension/google';

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
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
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
	 * @param	string    $version    	The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $config ) {
		$this->plugin_name	= $plugin_name;
		$this->version 		= $version;
		$this->config 		= $config;
	}
    
    /**
	 * Check if active
	 *
	 * @since    1.0.0
	 */
	public function if_active() {
		$notification = new Notification(
			sprintf(
				'<h3 class="notice__heading" style="margin-bottom:0px">%1$s</h3>
				<div class="notice__content">
					<p>%2$s</p>
					<p>
						<a href="%3$s" class="button button-primary">%4$s</a>
					</p>
				</div>',
				esc_html__( 'Awesome, WCA: Google Tools extension is activated!', 'wca-google' ),
				esc_html__( 'Go to Theme Options in order to setup your Google services.', 'wca-google' ),
				esc_url( admin_url( '/themes.php?page=wecodeart&tab=extensions' ) ),
				esc_html__( 'Awesome, show me the options!', 'wca-google' )
			),
			[
				'id'			=> self::NOTICE_ID,
				'type'     		=> Notification::INFO,
				'priority' 		=> 1,
				'class'			=> 'notice is-dismissible',
				'capabilities' 	=> 'activate_plugins',
			]
		);

		if( get_user_option( self::NOTICE_ID ) === 'seen' ) {
			Notifications::get_instance()->remove_notification( $notification );
			set_transient( self::NOTICE_ID, true, WEEK_IN_SECONDS );
			return;
		}

		if( get_transient( self::NOTICE_ID ) === false ) {
			Notifications::get_instance()->add_notification( $notification );
		}
	}

	/**
	 * Admin Assets
	 *
	 * @since	2.0.0
	 * @version	2.0.2
	 */
	public function assets() {
		if( ! current_user_can( 'administrator' ) ) return;

		$path = wecodeart_if( 'is_dev_mode' ) ? 'unminified' : 'minified';
		$name = wecodeart_if( 'is_dev_mode' ) ? 'admin' : 'admin.min';
		$data = [
			'version' 		=> $this->version,
			'dependencies'	=> [
				'wp-i18n',
				'wp-hooks',
				'wp-element',
				'wp-components',
			],
		];

		wp_register_script( 
			$this->make_handle(),
			wp_normalize_path( sprintf( '%s/assets/%s/js/%s.js', plugin_dir_url( __DIR__ ), $path, $name ) ),
			$data['dependencies'], 
			$data['version'], 
			true 
		);

		wp_enqueue_script( $this->make_handle() );

		wp_set_script_translations( $this->make_handle(), 'wca-google', WCA_GOOGLE_EXT_DIR );

		// Merge config with defaults
		$config = wp_parse_args( get_prop( $this->config, 'fields', [] ), self::get_defaults() );
		
		// Get only required data.
		$config = array_map( function( $item ) {
			return wp_array_slice_assoc( $item, [ 'id', 'label', 'externalUrl', 'placeholder' ] );
		}, $config );

		wp_localize_script( $this->make_handle(), 'wecodeartGoogleExtension', [
			'fields' => $config
		] );
	}

	/**
	 * Make a script handle from the classname
	 *
	 * @since	2.0.1
 	 * @version	2.0.1
	 *
	 * @param	string 	$name
	 *
	 * @return 	string
	 */
	protected function make_handle( $name = '', $namespace = __CLASS__ ) {
		$handle = strtolower( str_replace( '\\', '-', $namespace ) );
		$handle = $name ? $handle . '-' . $name : $handle;
		
		return sanitize_html_class( $handle );
	}

	/**
	 * Get defaults
	 *
	 * @since	2.0.2
 	 * @version	2.0.2
	 *
	 * @return 	array
	 */
	public static function get_defaults(): array {
		return [
			[
				'id' 			=> 'google_webmasters',
				'label' 		=> 'Webmasters',
				'externalUrl' 	=> '//support.google.com/webmasters/answer/9008080?hl=en',
				'placeholder' 	=> 'xIzf4xC9psNX-fKBobS9AFzpK7__umVaw3eH9T-1WCA',
				'hook' 			=> 'wp_head',
				'callback' 		=> '<meta name="google-site-verification" content="%s" />'
			],
			[
				'id' 			=> 'google_analytics',
				'label' 		=> 'Analytics',
				'externalUrl' 	=> '//support.google.com/analytics/answer/9539598?hl=en',
				'placeholder' 	=> 'UA-XXXXXXXX-X | G-XXXXXXXXXX',
				'hook' 			=> 'wp_enqueue_scripts',
				'callback' 		=> function( $value ) {
					$script = '';
					$script .= 'window.dataLayer = window.dataLayer || [];';
					$script .= 'function gtag(){dataLayer.push(arguments);}';
					$script .= "gtag('js',new Date());";
					$script .= sprintf( "gtag('config','%s');", esc_attr( $value ) );
				
					$google = add_query_arg( [ 'id' => esc_attr( $value ) ], 'https://www.googletagmanager.com/gtag/js' );

					wp_register_script( 'google-tag', $google, [], null, true );
					wp_enqueue_script( 'google-tag' );

					wp_add_inline_script( 'google-tag', $script, 'after' );
				},
			],
			[
				'id' 			=> 'google_publisher',
				'label' 		=> 'Adsense',
				'externalUrl' 	=> '//support.google.com/adsense/answer/105516?hl=en',
				'placeholder' 	=> 'ca-pub-1234567891234567',
				'hook' 			=> 'wp_enqueue_scripts',
				'callback' 		=> function( $value ) {
					$callback = function( $tag, $handle ) use ( $value ) {
						if ( $handle !== 'google-adsense' ) {
							return $tag;
						}
						
						$tag = str_replace( ' src', ' async data-ad-client="' . esc_attr( $value ) . '" src', $tag );
						$tag = str_replace( ' type=\'text/javascript\'', '', $tag );

						remove_filter( current_filter(), __FUNCTION__ );
				
						return $tag;
					};
			
					add_filter( 'script_loader_tag', $callback, 10, 2 );
			
					wp_register_script( 'google-adsense', 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js', [], null );
					wp_enqueue_script( 'google-adsense' );
				},
			]
		];
	}
}
