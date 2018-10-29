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
    add_action( 'admin_init', array( $this, 'register_settings' ) );
  }

  public function register_settings() {
    add_settings_section( 'presscount_settings', '', null, 'presscount' );

    add_settings_field( 'presscount_facebook', __( 'Facebook', 'presscount' ), array( $this, 'facebook_checkbox' ), 'presscount', 'presscount_settings' );
    add_settings_field( 'presscount_linkedin', __( 'LinkedIn', 'presscount' ), array( $this, 'linkedin_checkbox' ), 'presscount', 'presscount_settings' );
    add_settings_field( 'presscount_pinterest', __( 'Pinterest', 'presscount' ), array( $this, 'pinterest_checkbox' ), 'presscount', 'presscount_settings' );

    register_setting( 'presscount_settings', 'presscount_facebook' );
    register_setting( 'presscount_settings', 'presscount_linkedin' );
    register_setting( 'presscount_settings', 'presscount_pinterest' );
  }

  public function facebook_checkbox() {
    echo '<input type="checkbox" name="presscount_facebook" value="true" ' . checked( 'true', get_option( 'presscount_facebook', 'true' ), false ) . ' />';
  }

  public function linkedin_checkbox() {
    echo '<input type="checkbox" name="presscount_linkedin" value="true" ' . checked( 'true', get_option( 'presscount_linkedin', 'true' ), false ) . ' />';
  }

  public function pinterest_checkbox() {
    echo '<input type="checkbox" name="presscount_pinterest" value="true" ' . checked( 'true', get_option( 'presscount_pinterest', 'true' ), false ) . ' />';
  }
}

new \Elexicon\PressCount\Admin\Settings();
