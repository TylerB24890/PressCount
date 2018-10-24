<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://elexicon.com
 * @since             1.0.0
 * @package           PressCount
 *
 * @wordpress-plugin
 * Plugin Name:       PressCount
 * Plugin URI:        http://elexicon.com
 * Description:       Like Mashables share count with social share analytics directly in the dashboard.
 * Version:           1.0.0
 * Author:            Elexicon
 * Author URI:        http://elexicon.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       presscount
 */



// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die("Sneaky sneaky...");
}

// Useful global constants
define( 'PRESSCOUNT_VERSION', '1.0.0' );
define( 'PRESSCOUNT_URL', plugin_dir_url( __FILE__ ) );
define( 'PRESSCOUNT_PATH', dirname( __FILE__ ) . '/' );
define( 'PRESSCOUNT_INC', PRESSCOUNT_PATH . 'includes/' );

// Include files
require_once PRESSCOUNT_INC . 'functions/setup.php';

// Activation/Deactivation
register_activation_hook( __FILE__, '\Elexicon\PressCount\Core\activate' );
register_deactivation_hook( __FILE__, '\Elexicon\PressCount\Core\deactivate' );

// Bootstrap
\Elexicon\PressCount\Core\setup();
