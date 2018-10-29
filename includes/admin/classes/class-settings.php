<?php

/**
 * Setups and initializes the PressCount Admin Dashboard
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Admin;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Settings {

  public function __construct() {

  }

  public function register_settings() {

  }

  public function sanitize_settings( $value ) {
    $new_values = array();

    
  }
}

new \Elexicon\PressCount\Admin\Settings();
