<?php

/**
 * Initializes plugin
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Core;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Init {

  public function __construct() {
    add_action( 'wp_head', array( $this, 'add_ajax_url' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'check_jquery' ) );

    $this->load_dependencies();
  }

  /**
   * Add the 'ajaxurl' variable to the site header
   *
   * @return HTML script tag
   */
  public function add_ajax_url() {
    echo '<script type="text/javascript">var presscount_ajax_url = "' . admin_url( 'admin-ajax.php' ) . '";</script>';
  }

  /**
   * Load plugin dependency files
   *
   * @return null
   */
  private function load_dependencies() {
    require_once PRESSCOUNT_INC . 'functions/helper.php';
    require_once PRESSCOUNT_INC . 'classes/class-cache.php';
    require_once PRESSCOUNT_INC . 'classes/class-requests.php';
    require_once PRESSCOUNT_INC . 'classes/class-ajax.php';
    require_once PRESSCOUNT_INC . 'classes/class-shortcodes.php';

    if( is_admin() ) {
      require_once PRESSCOUNT_INC . 'admin/classes/class-init.php';
    }
  }

  /**
   * Check that jQuery is enqueued
   * If not, enqueue it!
   *
   * @return null
   */
  public function check_jquery() {
    if( ! wp_script_is( 'jquery', 'enqueued' ) && ! is_admin() ) {
      wp_enqueue_script( 'jquery' );
    }
  }
}
