<div class="wrap">
  <h1><?php _e( 'PressCount Settings', 'presscount' ); ?></h1>

  <h3><?php _e( 'Select which networks to retrieve share counts from:', 'presscount' ); ?></h3>
  <form method="post" action="options.php">
	   <?php
     settings_fields( "presscount_settings" );
     do_settings_sections( "presscount" );
     submit_button();
    ?>
	</form>
</div>
