<?php

/**
 * Requests data from social APIs
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Social;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Requests {

  /**
   * Post URL
   * @var string
   */
  private $url;

  /**
   * Facebook API Endpoint
   * @var string
   */
  private $fb_endpoint;

  /**
   * LinkedIn API Endpoint
   * @var string
   */
  private $linkedin_endpoint;

  /**
   * Pinterest API Endpoint
   * @var string
   */
  private $pinterest_endpoint;

  /**
   * Constructs the request variables & urls
   *
   * @param string $url URL for the post
   */
  public function __construct( $url ) {
    $this->url = $url;

    $this->fb_endpoint = 'http://graph.facebook.com/?id=' . $this->url;
    $this->linkedin_endpoint = 'http://www.linkedin.com/countserv/count/share?url=' . $this->url . '&format=json';
    $this->pinterest_endpoint = 'http://api.pinterest.com/v1/urls/count.json?url=' . $this->url;
  }

  /**
   * Get number of shares on Facebook
   *
   * @return int
   */
  public function get_fb() {

    $resp = wp_remote_get( $this->fb_endpoint );

    if( is_array( $resp ) ) {
      $json_string = $resp['body'];

      $json = json_decode( $json_string, true );

      return isset( $json['share']['share_count'] ) ? intval( $json['share']['share_count'] ) : 0;
    }

    return false;
  }

  /**
   * Get number of shares on linkedin
   *
   * @return int
   */
  public function get_linkedin() {

    $resp = wp_remote_get( $this->linkedin_endpoint );

    if( is_array( $resp ) ) {
      $json = json_decode( $resp['body'], true );

      return isset( $json['count'] ) ? intval( $json['count'] ) : 0;
    }

    return false;
  }

  /**
   * Get number of shares on Pinterest
   *
   * @return int
   */
  public function get_pinterest() {

    $resp = wp_remote_get( $this->pinterest_endpoint );

    if( is_array( $resp ) ) {
      $json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $resp['body'] );
      $json = json_decode( $json_string, true );

      return isset( $json['count'] ) ? intval( $json['count'] ) : 0;
    }

    return false;
  }

  /**
   * Calculate all shares together
   *
   * Uses `apply_filters` to allow user to set custom transient expiration time
   * 'presscount_expire' will change transient expiration time (Default: 3600 === 1 hour)
   *
   * @return int
   */
  public function get_all_shares() {

    $pid = url_to_postid( $this->url );

    if(get_transient( $pid . "_post_shares" ) ) {
      return get_transient( $pid . "_post_shares" );
    }

    $share_base = 0;

    $total_shares += $this->get_fb();
    $total_shares += $this->get_linkedin();
    $total_shares += $this->get_pinterest();

    $total_shares = $share_base + $total_shares;

    // Save total share count to database
    set_transient( $pid . "_post_shares", $total_shares, apply_filters( 'presscount_expire', 3600 ) );
    update_post_meta($pid, '_post_shares', $total_shares);

    return $total_shares;
  }
}
