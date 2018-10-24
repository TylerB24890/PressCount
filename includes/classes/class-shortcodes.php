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

  public function share_count_sc( $atts ) {
    global $post;

    // extract shortcode attributes
    extract(
      shortcode_atts(
        array(
          'text' => false,
          'url' => get_the_permalink(),
          'id' => $post->ID
        ),
        $atts, 'share_count'
      )
    );

    include( PRESSCOUNT_INC . 'partials/ajax-script.php' );
  }
}

new \Elexicon\PressCount\Social\Shortcodes();
