<?php
/**
 * Plugin helper functions
 *
 * @package presscount
 */

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

/**
 * Get the current post URL or post URL by ID
 *
 * @param  integer $id ID of the post to get URL for
 * @return string      URL of requested post
 */
function presscount_post_url( $id = 0 ) {
  $url = '';

  if( $id !== 0 ) {
    $url = get_the_permalink( $id );
  } else {
    $url = ( isset( $_GET['url'] ) ? esc_url_raw( $_GET['url'] ) : get_the_permalink() );
  }

  return $url;
}

/**
 * Return share counts
 *
 * @param  string $network The network to retrieve the shares from
 * @param  string $url     The URL of the post to retrieve share counts for
 * @return int             The returned share count
 */
function presscount_shares( $network = 'all', $url = null ) {

  $url = ( $url === null ? presscount_post_url() : esc_url_raw( $url ) );

  $requests = new \Elexicon\PressCount\Social\Requests( $url );

  switch( $network ) {
    case 'all' :
      return $requests->get_all_shares();
    break;
    case 'facebook' :
    case 'fb' :
      return $requests->get_fb();
    break;
    case 'linkedin' :
      return $requests->get_linkedin();
    break;
    case 'pinterest' :
      return $requests->get_pinterest();
    break;
  }

  return false;
}
