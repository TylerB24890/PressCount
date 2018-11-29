<div id="presscount" class="wrap">
  <h1><?php _e( 'PressCount Settings', 'presscount' ); ?></h1>

  <div class="presscount-networks">
    <h3><?php _e( 'Select which networks to retrieve share counts from:', 'presscount' ); ?></h3>
    <form method="post" action="options.php">
  	   <?php
       settings_fields( "presscount_settings" );
       do_settings_sections( "presscount" );
       submit_button();
      ?>
  	</form>
  </div>

  <div class="presscount-cache">
    <button id="presscount-clear-cache" class="button"><?php _e( 'Clear Cache', 'presscount' ); ?></button>
  </div>
</div>

<script>
jQuery('button#presscount-clear-cache').on('click', function(e) {
  e.preventDefault();

  jQuery.post({
    url: "<?php echo admin_url('admin-ajax.php'); ?>",
    action: "clear_presscount_cache"
  });
});
</script>
