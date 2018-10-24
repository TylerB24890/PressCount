<span id="presscount_<?php echo $id;  ?>"></span>
<script>
  jQuery(document).ready(function() {
    var sc = jQuery("span#presscount_<?php echo $id; ?>");
    var markup = '';

    jQuery.get(presscount_ajax_url, {
      action: "get_all_shares",
      url: "<?php echo $url; ?>",
      id: "<?php echo $id; ?>"
    }).done(function(data) {
      var shares = Number(data);

      markup = (!isNaN(shares) ? shares : '0');

      <?php if($text) : ?>
        markup = markup + (parseInt(shares) == 1 ? ' <?php _e( 'Share', 'presscount' ); ?>' : ' <?php _e( 'Shares', 'presscount' ); ?>');
      <?php endif; ?>

      sc.html(markup);
    });
  });
</script>
