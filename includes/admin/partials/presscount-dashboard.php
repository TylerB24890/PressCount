<?php global $presscount_global_url; ?>

<div class="wrap">
	
	<h2>PressCount Overview</h2>
	
	<h3>Congratulations! PressCount is now active and your post stats are now being gathered.</h3>
	
	<p>To show the share count of each post, simply add the <code>[share_count]</code> shortcode to your loop to return the total number of shares for each post. The shortcode returns a single number for you to style how you wish, or you can add the <code>text=true</code> parameter to add 'Shares' to your returned post count.</p>
	
	<p>The shortcode will inject the necessary AJAX script into your loop and return the share counts without disrupting your page load time.</p>
	
	<p><b>NOTE:</b> Share counts are cached on the frontend for 1 hour. When viewed through the <a href="<?php echo admin_url('admin.php?page=presscount'); ?>">admin dashboard</a> they are real-time sharing stats.</p>
	
	<div class="postbox">
		<h3>Shortcode <code>[share_count]</code> - no parameters set</h3>
		<p>Adding the shortcode with no parameters will just return the total share count number for you to do with as you please.</p>
		<img src="<?php echo $presscount_global_url ?>includes/admin/img/presscount-shortcode-example1.jpg">
	</div>

	
	<div class="postbox">
		<h3>Shortcode <code>[share_count text=true]</code> - parameter set</h3>
		<p>Adding the <code>text=true</code> parameter to the shortcode will return the share count with "Share" (1 share) or "Shares" (multiple shares).</p>
		<img src="<?php echo $presscount_global_url ?>includes/admin/img/presscount-shortcode-example2.jpg">
	</div>
	
	<?php include_once('presscount-copyright.php'); ?>
</div>
