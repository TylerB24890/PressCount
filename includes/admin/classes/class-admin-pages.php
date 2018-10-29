<?php

/**
 * Registers the PressCount Admin Pages
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Admin;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Admin_Pages {

  public function __construct() {
    add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );
  }

  public function add_admin_pages() {
    add_menu_page(
      __( 'PressCount', 'presscount' ),
      __( 'PressCount', 'presscount' ),
      'manage_options',
      'presscount',
      array( $this, 'render_dashboard' )
    );
  }

  public function render_dashboard() {
    require_once PRESSCOUNT_INC . 'admin/partials/dashboard.php';
  }
}

new \Elexicon\PressCount\Admin\Admin_Pages();
