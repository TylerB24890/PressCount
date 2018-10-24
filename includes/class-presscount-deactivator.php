<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://elexicon.com
 * @since      0.5.2
 *
 * @package    PressCount
 * @subpackage presscount/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.5.2
 * @package    PressCount
 * @subpackage presscount/includes
 * @author     Tyler Bailey <tylerb.media@gmail.com>
 */
 
if(!class_exists('PressCount_Deactivator')) {
	class PressCount_Deactivator {
	
		/**
		 * Clears all transients and plugin data
		 *
		 * Deletes all plugin meta data and transients
		 *
		 * @since      0.5.2
		 */
		public static function deactivate() {
			return;
		}
	
	}
}
