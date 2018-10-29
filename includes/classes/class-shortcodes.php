<?php

/**
 * Registers the plugin shortcodes with WP
 *
 * @package presscount
 */


namespace Elexicon\PressCount\Social;

if ( ! defined( 'ABSPATH' ) ) exit(); // No direct access

class Shortcodes {

  public function __construct() {
    add_shortcode( 'share_count', array( $this, 'share_count_sc' ) );
  }

  /**
   * Process the PressCount share_count Shortcode
   *
   * Uses apply_filters to allow users to set custom text appending share count
   * 'presscount_single_share_text' will change the single share text (Default: Share)
   * 'presscount_multiple_share_text' will change the multiple shares text (Default: Shares)
   *
   * @param  array $atts Array of shortcode attributes
   * @return string      HTML markup/script for PressCount AJAX
   */
  public function share_count_sc( $atts ) {
    global $post;

    // extract shortcode attributes
    extract(
      shortcode_atts(
        array(
          'text' => false,
          'url' => presscount_post_url(),
          'id' => $post->ID
        ),
        $atts, 'share_count'
      )
    );

    $text_single = apply_filters( 'presscount_single_share_text', __('Share', 'presscount') );
    $text_multiple = apply_filters( 'presscount_multiple_share_text', __( 'Shares', 'presscount' ) );

    include( PRESSCOUNT_INC . 'partials/ajax-script.php' );
  }
}

new \Elexicon\PressCount\Social\Shortcodes();
