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

use WeCodeArt\Admin\Notifications;

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
		Notifications::get_instance()->remove_notification_by_id( Admin::NOTICE_ID );
		
		delete_transient( Admin::NOTICE_ID );
	}
}
