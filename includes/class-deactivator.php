<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.wecodeart.com/
 * @since      1.0.0
 *
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\DeActivator
 */

namespace WCA\EXT\Google;

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    WCA\EXT\Google
 * @subpackage WCA\EXT\Google\DeActivator
 * @author     Bican Marian Valeriu <marianvaleriubican@gmail.com>
 */
class Deactivator {
	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		delete_transient( Admin::NOTICE_ID );
	}
}
