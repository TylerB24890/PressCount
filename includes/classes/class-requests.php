<?php

/**
 * Requests data from social APIs
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Social;
use \Elexicon\PressCount\Core\Cache as Cache;

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
   * Cache Object
   * @var object
   */
  private $cache;

  /**
   * Constructs the request variables & urls
   * Initializes the PressCount Cache Object
   *
   * @param string $url URL for the post
   */
  public function __construct( $url ) {
    $this->url = $url;

    $this->fb_endpoint = 'http://graph.facebook.com/?id=' . $this->url;
    $this->linkedin_endpoint = 'http://www.linkedin.com/countserv/count/share?url=' . $this->url . '&format=json';
    $this->pinterest_endpoint = 'http://api.pinterest.com/v1/urls/count.json?url=' . $this->url;

    $this->cache = new Cache();
  }

  /**
   * Get number of shares on Facebook
   *
   * @return int
   */
  public function get_fb() {

    $share_count = $this->cache->get_cached_shares( $this->url, 'facebook' );

    if( ! $share_count ) {
      $resp = wp_remote_get( $this->fb_endpoint );

      if( is_array( $resp ) ) {
        $json_string = $resp['body'];

        $json = json_decode( $json_string, true );

        $share_count = ( isset( $json['share']['share_count'] ) ? intval( $json['share']['share_count'] ) : 0 );

        $this->cache->cache_shares( $this->url, $share_count, 'facebook' );
      }
    }

    return $share_count;
  }

  /**
   * Get number of shares on linkedin
   *
   * @return int
   */
  public function get_linkedin() {

    $share_count = $this->cache->get_cached_shares( $this->url, 'linkedin' );

    if( ! $share_count ) {
      $resp = wp_remote_get( $this->linkedin_endpoint );

      if( is_array( $resp ) ) {
        $json = json_decode( $resp['body'], true );

        $share_count = ( isset( $json['count'] ) ? intval( $json['count'] ) : 0 );

        $this->cache->cache_shares( $this->url, $share_count, 'linkedin' );
      }
    }

    return $share_count;
  }

  /**
   * Get number of shares on Pinterest
   *
   * @return int
   */
  public function get_pinterest() {

    $share_count = $this->cache->get_cached_shares( $this->url, 'pinterest' );

    if( ! $share_count ) {
      $resp = wp_remote_get( $this->pinterest_endpoint );

      if( is_array( $resp ) ) {
        $json_string = preg_replace( '/^receiveCount\((.*)\)$/', "\\1", $resp['body'] );
        $json = json_decode( $json_string, true );

        $share_count = ( isset( $json['count'] ) ? intval( $json['count'] ) : 0 );

        $this->cache->cache_shares( $this->url, $share_count, 'pinterest' );
      }
    }

    return $share_count;
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

    // Give the user an option to set a "starting" share amount
    $share_base = apply_filters( 'presscount_share_base', 0 );

    if( get_option( 'presscount_facebook' ) === 'true' ) {
      $total_shares += $this->get_fb();
    }

    if( get_option( 'presscount_linkedin' ) === 'true' ) {
      $total_shares += $this->get_linkedin();
    }

    if( get_option( 'presscount_pinterest' ) === 'true' ) {
      $total_shares += $this->get_pinterest();
    }

    $total_shares = $share_base + $total_shares;

    return $total_shares;
  }
}
