<?php

/**
 * PressCount Caching
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Core;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Cache {

  /**
   * PressCount Transient Prefix
   * @var string
   */
  protected $prefix;

  /**
   * Initializes the PressCount Cache
   */
  public function __construct() {
    $this->prefix = '_presscount_shares_';
  }

  /**
   * Return cached share counts by URL
   *
   * @param  string $url URL to get cached share counts for
   * @return int         The number of shares from cache
   */
  public function get_cached_shares( $url ) {
    $cache_key = md5( $url );

    return get_transient( $this->prefix . $cache_key );
  }

  /**
   * Save the share count to WP transients
   *
   * @param  string $url    URL that was shared
   * @param  int $shares    The number of times that URL was shared
   * @return void
   */
  public function cache_shares( $url, $shares ) {
    if( ! empty( $url ) ) {
      $cache_key = md5( $url );
      set_transient( $this->prefix . $cache_key, $shares, apply_filters( 'presscount_expire', 3600 ) );
    }

    return true;
  }

  /**
   * Delete PressCount Transients/Cache
   *
   * @param  boolean $url URL to clear of cache (default false for all)
   * @return array        Array of cleared cached data
   */
  public function clear_cache( $url = false ) {
    if( ! $url ) {
      $cache_key = md5( $url );
      delete_transient( $this->prefix . $cache_key );

      return true;
      
    } else {
      $transients = $this->get_transients_by_prefix();

      if( ! $transients ) {
        return false;
      }

      if( is_string( $transients ) ) {
        $transients = array( array( 'option_name' => $transients ) );
      }

      if( ! is_array( $transients ) ) {
        return false;
      }

      $results = array();

      foreach( $transients as $transient ) {
        if( is_array( $transient ) ) {
          $transient = current( $transient );
        }

        $results[ $transient ] = delete_transient( str_replace( '_transient_', '', $transient ) );
      }

      return array(
        'total' => count( $results ),
        'deleted' => array_sum( $results )
      );
    }
  }

  /**
   * Get all transients set by PressCount from database
   *
   * @return array Returns an array of transients to delete
   */
  private function get_transients_by_prefix() {
    global $wpdb;

    $trans_prefix = $wpdb->esc_like( '_transient_' . $this->prefix . '_' );

    $sql = "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%s'";

    $transients = $wpdb->get_results( $wpdb->prepare( $sql, $trans_prefix ), ARRAY_A );

    if( $transients && ! is_wp_error( $transients ) ) {
      return $transients;
    }

    return false;
  }
}

new \Elexicon\PressCount\Core\Cache();
