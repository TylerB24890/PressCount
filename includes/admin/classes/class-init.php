<?php

/**
 * Setups and initializes the PressCount Admin Dashboard
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Admin;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Init {

  public function __construct() {

    if( ! is_admin() ) {
      return;
    }

    $this->load_admin_dependencies();
  }

  private function load_admin_dependencies() {
    require_once PRESSCOUNT_INC . 'admin/classes/class-assets.php';
    require_once PRESSCOUNT_INC . 'admin/classes/class-pages.php';
    require_once PRESSCOUNT_INC . 'admin/classes/class-settings.php';
  }
}

new \Elexicon\PressCount\Admin\Init();
