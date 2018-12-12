<?php

/**
 * Loads PressCount Admin Assets
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Admin;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Assets {

  public function __construct() {

    if( ! is_admin() ) {
      return;
    }

    add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );
  }

  public function load_assets( $hook ) {
    if( $hook !== "settings_page_presscount" ) {
      return;
    }

    wp_enqueue_style( 'presscount-admin', PRESSCOUNT_URL . 'includes/admin/assets/styles/presscount.css' );
  }
}

new \Elexicon\PressCount\Admin\Assets();
