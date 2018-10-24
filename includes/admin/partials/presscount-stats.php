<div class="wrap">
	
	<h2>PressCount Stats</h2>
	
	<div class="analytic-options">
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab<?php echo(!isset($_GET['type']) || $_GET['type'] == 'recent') ? ' nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( 'type', 'recent', get_permalink() ); ?>">Most Recent</a>
			
			<a class="nav-tab<?php echo(isset($_GET['type']) && $_GET['type'] == 'most') ? ' nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( 'type', 'most', get_permalink() ); ?>">Most Shared</a>
			
			<a class="nav-tab<?php echo(isset($_GET['type']) && $_GET['type'] == 'least') ? ' nav-tab-active' : ''; ?>" href="<?php echo add_query_arg( 'type', 'least', get_permalink() ); ?>">Least Shared</a>
		</h2>
	</div>
	
	<div class="postbox">
		<!-- Post Date Filter -->
		<form name="filter_social" id="filter_social">
			<input type="date" class="datepicker" id="start_date" name="start_date">
			TO: 
			<input type="date" class="datepicker" id="end_date" name="end_date">	
			<input type="hidden" id="page_url" name="page_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
			<input type="submit" value="Filter">
		</form>
		
		<!-- Post Keyword Search -->
		<form name="s" id="searchform" method="get" action="">
			<p>
				<label>Search Terms:</label>
				<input type="search" name="s" id="post-search" placeholder="Search Posts">
			</p>
			
			<input type="hidden" name="post_type[]" value="post">
			<input type="hidden" name="post_type[]" value="videos">
			<input type="hidden" name="post_type[]" value="infographics">
			<input type="hidden" id="page_url" name="page_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
			
			<p><input type="submit" value="Search"></p>
		</form>
	</div>
	
	<div class="postbox">
		<!-- Post Display -->
		<table width="100%" id="presscount-stats">
			<thead>
				<tr>
					<td id="post-info">Rank</td>
					<td id="post-info">Post</td>
					<td>Facebook</td>
					<td>Twitter</td>
					<td>LinkedIn</td>
					<td>Google Plus</td>
					<td>Pinterest</td>
					<td>Total Shares</td>
				</tr>
			</thead>
			<tbody>
				<?php
					
					if(isset($_GET['type']) && !isset($_GET['keywords'])) {
						
						$type = $_GET['type'];
						
						// Return posts based on 'type' query
						switch($type) {
							case "recent" :
								$posts = $this->get_recent_posts(10);
								break;
							case "most" :
								$posts = $this->get_posts_by_shares(10);
								break;
							case "least" :
								$posts = $this->get_posts_by_shares(10, "least");
								break;
							case "date" :
								if(isset($_GET['start'])) {
									$start_date = $_GET['start'];
									$end_date = $_GET['end'];
									$posts = $this->get_posts_by_date($start_date, $end_date);	
								} else {
									$posts = $this->get_recent_posts(10);
								}
								
								break;
						}
					} elseif(isset($_GET['keywords'])) {
						
						// Return posts based on search terms
						$keywords = sanitize_text_field($_GET['keywords']);
						$posts = $this->get_posts_by_keywords($keywords);
					} else {
						
						// If no 'type' or 'keywords' are set, return most recent posts
						$posts = $this->get_recent_posts();
					}
					
					$post_count = 0;
					
					// Loop through returned posts	
					foreach($posts as $post_data) :
						$post_count++;
						
						// Get post title
						$postTitle = $post_data->post_title;
						
						// Get post URL
						$postURL = get_the_permalink($post_data->ID);
						
						// Get all post shares
						$facebook = $this->get_fb($postURL);
						$twitter = $this->get_tweets($postURL);
						$linkedin = $this->get_linkedin($postURL);
						$google = $this->get_plusones($postURL);
						$pinterest = $this->get_pinterest($postURL);
						
						// Add all shares together to get total share count
						$all_shares = $this->get_all_shares($postURL);
				?>
				
						<tr>
							<td><?php echo $post_count; ?></td>
							<td id="post-info">
								<a href="<?php echo $postURL; ?>"><?php echo $postTitle ?></a>
							</td>
							<td><?php echo $facebook ?></td>
							<td><?php echo $twitter; ?></td>
							<td><?php echo $linkedin; ?></td>
							<td><?php echo $google; ?></td>
							<td><?php echo $pinterest; ?></td>
							<td><?php echo $all_shares; ?></td>
						</tr>
				
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	
	<?php include_once('presscount-copyright.php'); ?>
	
	<script>
	
		// Post "date" Filter
		jQuery('#filter_social').on('submit', function(e) {
			e.preventDefault();
			
			var filter_data = jQuery(this).serialize();
			

			jQuery.ajax({
		    	type: "POST",
		    	url: ajaxurl,
		    	dataType: 'json',
		    	data: {"action": "user_redirect", filter_data},
		    	success: function(data) {
		    		if(data.status == 1) {
		    			window.location.href = data.redirect_url;
		    		} else {
		    			alert("Oops, an error occurred. Please try again or contact the plugin developer (Tyler Bailey <tyler@elexicon.com>).");
		    			console.log(data);
		    		}
		    	}
		    });
		});
		
		// Post keyword search
		jQuery('#searchform').on('submit', function(e) {
			e.preventDefault();
			
			var search_data = jQuery(this).serialize();

			jQuery.ajax({
		    	type: "POST",
		    	url: ajaxurl,
		    	dataType: 'json',
		    	data: {"action": "user_search", search_data},
		    	success: function(data) {
		    		if(data.status == 1) {
		    			window.location.href = data.redirect_url;
		    			//console.log(data);
		    		} else {
		    			alert("Oops, an error occurred. Please try again or contact the plugin developer (Tyler Bailey <tyler@elexicon.com>).");
		    			console.log(data);
		    		}
		    	}
		    });
		});
	</script>
</div>
