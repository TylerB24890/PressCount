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
   * Allowed PressCount Networks
   * @var array
   */
  protected $networks;

  /**
   * Initializes the PressCount Cache
   */
  public function __construct() {
    // Base key
    $this->prefix = '_presscount_shares_';

    // Allowed networks
    $this->networks = array(
      'facebook',
      'linkedin',
      'pinterest'
    );

    add_action( 'wp_ajax_clear_presscount_cache', array( $this, 'ajax_clear_cache' ) );
    add_action( 'wp_ajax_nopriv_clear_presscount_cache', array( $this, 'ajax_clear_cache' ) );
  }

  /**
   * Return cached share counts by URL
   *
   * @param  string $url URL to get cached share counts for
   * @param  string $network Network to get share counts for
   * @return int         The number of shares from cache
   */
  public function get_cached_shares( $url = false, $network = false ) {
    $url = ( $url ? $url : presscount_post_url() );
    $cache_key = md5( $url );
    $shares = false;
    
    if ( ! $network ) {
      foreach( $this->networks as $service ) {
        if( get_option( 'presscount_' . $service ) === 'true' ) {
          $new_prefix = $this->network_cache_key( $service );
          $shares += get_transient( $new_prefix . $cache_key );
        }
      }
    } else {
      $this->prefix = $this->network_cache_key( $network );
      $shares = get_transient( $this->prefix . $cache_key );
    }

    return $shares;
  }

  /**
   * Save the share count to WP transients
   *
   * @param  string $url    URL that was shared
   * @param  int $shares    The number of times that URL was shared
   * @return void
   */
  public function cache_shares( $url, $shares, $network = false ) {
    if( ! empty( $url ) ) {
      $cache_key = md5( $url );

      $this->prefix = $this->network_cache_key( $network );

      set_transient( $this->prefix . $cache_key, $shares, apply_filters( 'presscount_expire', 3600 ) );
    }

    return true;
  }

  /**
   * Delete PressCount Transients/Cache via AJAX
   *
   * @return void
   */
  public function ajax_clear_cache() {
    $url = ( isset( $_POST['url'] ) ? $_POST['url'] : false );
    $network = ( isset( $_POST['network'] ) ? $_POST['network'] : false );

    $this->clear_cache( $url, $network );
    die();
  }

  /**
   * Delete PressCount Transients/Cache
   *
   * @param  boolean $url URL to clear of cache (default false for all)
   * @return array        Array of cleared cached data
   */
  private function clear_cache( $url = false, $network = false ) {
    if( $url ) {
      $cache_key = md5( $url );

      $this->prefix = $this->network_cache_key( $network );

      delete_transient( $this->prefix . $cache_key );

      return true;

    } else {
      $transients = $this->get_transients_by_prefix();

      if( ! $transients ) {
        return false;
      }

      $results = array();

      if( is_array( $transients ) ) {
        foreach( $transients as $transient ) {

          if( is_array( $transient ) ) {
            $transient = current( $transient );

            $trans = str_replace( '_transient_', '', $transient['option_name'] );
            $results[$trans] = delete_transient( $trans );
          }
        }

        return array(
          'total' => count( $results ),
          'deleted' => array_sum( $results )
        );
      }

      return false;
    }
  }

  /**
   * Get all transients set by PressCount from database
   *
   * @return array Returns an array of transients to delete
   */
  private function get_transients_by_prefix() {
    global $wpdb;

    $transients = array();

    foreach( $this->networks as $network ) {
      $trans_prefix = $wpdb->esc_like( '_transient_' . $this->network_cache_key( $network ) );

      $sql = "SELECT option_name FROM $wpdb->options WHERE option_name LIKE '%s'";

      $res = $wpdb->get_results( $wpdb->prepare( $sql, $trans_prefix . '%' ), ARRAY_A );

      if( is_wp_error( $res ) ) {
        return false;
      }

      $transients[] = $res;
    }

    if( ! is_array( $transients ) || empty( $transients ) ) {
      return false;
    }

    return $transients;
  }

  /**
   * Set the cache prefix for networks
   *
   * @param  string $network Network to retrieve cached shares for
   * @return string          The new cache prefix
   */
  private function network_cache_key( $network = false ) {
    if( $network === false ) {
      return $this->prefix;
    }

    return $this->prefix . $network . '_';
  }
}

new \Elexicon\PressCount\Core\Cache();
