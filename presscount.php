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
 * @since             0.5.2
 * @package           PressCount
 *
 * @wordpress-plugin
 * Plugin Name:       PressCount
 * Plugin URI:        http://elexicon.com
 * Description:       Like Mashables share count with social share analytics directly in the dashboard.
 * Version:           0.5.2
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


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-presscount-activator.php
 */
function activate_presscount() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-presscount-activator.php';
	PressCount_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_presscount' );



/**
 * Redirect user to admin overview page on plugin activation
 */
add_action( 'activated_plugin', 'presscount_activation_redirect' );
function presscount_activation_redirect( $plugin ) {
    if( $plugin == plugin_basename( __FILE__ ) ) {
        exit(wp_redirect(admin_url('admin.php?page=presscount')));
    }
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-presscount-deactivator.php
 */
function deactivate_presscount() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-presscount-deactivator.php';
	PressCount_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_presscount' );




/**
 * Initiate a global variable to access the plugin directories
 */
global $presscount_global_url;
$presscount_global_url  = plugin_dir_url( __FILE__ );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-presscount.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
if(!function_exists('run_presscount')) {
	function run_presscount() {
		$plugin = new PressCount();
	}
	run_presscount();
}