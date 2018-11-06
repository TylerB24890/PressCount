<?php
/**
 * AJAX Script & HTML markup for shortcode
 *
 * Uses 'apply_filters' to change the output if share count = 0
 * 'presscount_zero_shares' will change the 0 to custom text (Default: 0)
 */
?>

<?php
$cache = new \Elexicon\PressCount\Core\Cache();
$cached_shares = $cache->get_cached_shares(get_the_permalink());
$share_text = ($cached_shares && $cached_shares == 1 ? $text_single : $text_multiple);
?>
<span id="presscount_<?php echo $id;  ?>"><?php echo($cached_shares ? $cached_shares . ($text ? ' ' . $share_text : '') : ''); ?></span>

<?php if( ! $cached_shares ) : ?>

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
