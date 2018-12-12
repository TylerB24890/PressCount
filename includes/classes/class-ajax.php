<?php

/**
 * Returns data requested via AJAX
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Social;
use \Elexicon\PressCount\Social\Requests as Requests;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Ajax {

  /**
   * The Requests class object
   *
   * @var object
   */
  private $request;

  /**
   * URL of post/page
   *
   * @var string
   */
  private $url;

  /**
   * Register the AJAX functions with WP
   */
  public function __construct( $url = null ) {

    add_action( 'wp_ajax_get_all_shares', array( $this, 'total_shares' ) );
    add_action( 'wp_ajax_nopriv_get_all_shares', array( $this, 'total_shares' ) );

    add_action( 'wp_ajax_get_fb_shares', array( $this, 'fb_shares' ) );
    add_action( 'wp_ajax_nopriv_get_fb_shares', array( $this, 'fb_shares' ) );

    add_action( 'wp_ajax_get_linkedin_shares', array( $this, 'linkedin_shares' ) );
    add_action( 'wp_ajax_nopriv_get_linkedin_shares', array( $this, 'linkedin_shares' ) );

    add_action( 'wp_ajax_get_pinterest_shares', array( $this, 'pinterest_shares' ) );
    add_action( 'wp_ajax_nopriv_get_pinterest_shares', array( $this, 'pinterest_shares' ) );

    $this->url = ( $url === null ? presscount_post_url() : $url );
    $this->request = new Requests( $this->url );
  }

  /**
   * Retrieve total share count via ajax
   *
   * @return int		Total share count
   */
  public function total_shares() {
    echo $this->request->get_all_shares( $this->url );
    die();
  }


  /**
   * Retrieve facebook count via ajax
   *
   * @return int		Number of Facebook shares
   */
  public function fb_shares() {
    echo $this->request->get_fb( $this->url );
    die();
  }

  /**
   * Retrieve linkedin count via ajax
   *
   * @return int		Number of LinkedIn shares
   */
  public function linkedin_shares() {
    echo $this->request->get_linkedin( $this->url );
    die();
  }

  /**
   * Retrieve Pinterest count via ajax
   *
   * @return int		Number of Pinterest shares
   */
  public function pinterest_shares() {
    echo $this->request->get_pinterest( $this->url );
    die();
  }
}

new \Elexicon\PressCount\Social\Ajax();
