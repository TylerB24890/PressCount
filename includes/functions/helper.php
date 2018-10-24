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
    $url = ( isset( $_GET['url'] ) ? $_GET['url'] : get_the_permalink() );
  }

  return $url;
}
