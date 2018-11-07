<?php
/**
 * AJAX Script & HTML markup for shortcode
 *
 * Uses 'apply_filters' to change the output if share count = 0
 * 'presscount_zero_shares' will change the 0 to custom text (Default: 0)
 */
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
