<?php

/**
 * Requests data from social APIs
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Admin;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Admin_Init {

  public function __construct() {

    if( ! is_admin() ) {
      return;
    }

    $this->load_admin_dependencies();
  }

  private function load_admin_dependencies() {
    require_once PRESSCOUNT_INC . 'admin/classes/class-admin-pages.php';
  }
}

new \Elexicon\PressCount\Admin\Admin_Init();
