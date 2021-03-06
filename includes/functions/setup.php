<?php
/**
 * Plugin initialization and setup
 *
 * @package presscount
 */

namespace Elexicon\PressCount\Core;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

/**
 * Default setup routine
 *
 * @return void
 */
function setup() {

	$n = function( $function ) {
		return __NAMESPACE__ . "\\$function";
	};

	add_action( 'init', $n( 'init' ) );
	add_action( 'plugins_loaded', $n( 'i18n' ) );
}

/**
 * Initializes the plugin
 *
 * @return void
 */
function init() {
	require PRESSCOUNT_INC . 'classes/class-init.php';
	new Init;
}

/**
 * Activate the plugin
 *
 * @return void
 */
function activate() {
	return;
}

/**
 * Deactivate the plugin
 *
 * Uninstall routines should be in uninstall.php
 *
 * @return void
 */
function deactivate() {
  return;
}

/**
 * Registers the default textdomain.
 *
 * @return void
 */
function i18n() {
	$locale = apply_filters( 'plugin_locale', get_locale(), 'presscount' );
	load_textdomain( 'presscount', WP_LANG_DIR . '/presscount/presscount-' . $locale . '.mo' );
	load_plugin_textdomain( 'presscount', false, plugin_basename( PRESSCOUNT_PATH ) . '/languages/' );
}
