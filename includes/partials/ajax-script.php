<?php
/**
 * AJAX Script & HTML markup for shortcode
 *
 * Uses 'apply_filters' to change the output if share count = 0
 * 'presscount_zero_shares' will change the 0 to custom text (Default: 0)
 */

// Is a single post
if( is_single() ) :
  $requests = new \Elexicon\PressCount\Social\Requests( get_the_permalink( $post->ID ) );
  $shares = $requests->get_all_shares();

  $share_display = $shares;

  if( $text ) {
    if( intval( $shares ) == 1 ) {
      $share_display = $shares . ' ' . $text_single;
    } else {
      $share_display = $shares . ' ' . $text_multiple;
    }
  }
?>
  <span id="presscount_<?php echo $post->ID ?>"><?php echo $share_display; ?></span>
<?php
// Not a single post (inside a loop)
else :
?>
  <span id="presscount_<?php echo $id;  ?>"></span>

  <script type="text/javascript">
    jQuery( document ).ready( function() {
      var sc = jQuery( "span#presscount_<?php echo $id; ?>" );
      var markup = '';

      jQuery.get(presscount_ajax_url, {
        action: "get_all_shares",
        url: "<?php echo $url; ?>",
        id: "<?php echo $id; ?>"
      }).done(function( data ) {
        var shares = Number( data );

        if( shares == 0 || isNaN( shares ) ) {
          shares = '<?php echo apply_filters('presscount_zero_shares', '0'); ?> ';
        }

        markup = shares;

        <?php if($text) : ?>
          markup = markup + ( parseInt( shares ) == 1 ? ' <?php echo $text_single; ?>' : ' <?php echo $text_multiple; ?>' );
        <?php endif; ?>

        sc.html( markup );
      });
    });
  </script>
<?php endif; ?>
