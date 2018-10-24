<?php
/**
 * Plugin initialization and setup
 *
 * @package presscount
 */

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

function presscount_post_url( $id = 0 ) {
  $url = '';

  if( $id !== 0 ) {
    $url = get_the_permalink( $id );
  } else {
    if( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_GET['url'] ) ) {
      $url = $_GET['url'];
    } else {
      $url = get_the_permalink();
    }
  }

  return $url;
}
