<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://elexicon.com
 * @since      0.5.2
 *
 * @package    presscount
 * @subpackage presscount/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.5.2
 * @package    presscount
 * @subpackage presscount/includes
 * @author     Tyler Bailey <tylerb.media@gmail.com>
 */
 
if(!class_exists('PressCount')) {
	class PressCount {
	
		/**
		 * The unique identifier of this plugin.
		 *
		 * @since      0.5.2
		 * @access   protected
		 * @var      string    $presscount    The string used to uniquely identify this plugin.
		 */
		protected $presscount;
	
		/**
		 * The current version of the plugin.
		 *
		 * @since      0.5.2
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;
		
		/**
		 * PressCount_Shares istantiation
		 *
		 * @since      0.5.2
		 * @access   protected
		 * @var      object    $shares 		Share class istantiation
		 */
		protected $shares;
		
		
		/**
		 * PressCount_Admin istantiation
		 *
		 * @since      0.5.2
		 * @access   protected
		 * @var      object    $admin 		Admin class istantiation
		 */
		protected $admin;
	
		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since      0.5.2
		 */
		public function __construct() {
	
			$this->presscount = 'presscount';
			$this->version = '1.0.0';
			
			add_action( 'wp_ajax_get_all_shares', array($this, 'presscount_total_shares') );
			add_action( 'wp_ajax_nopriv_get_all_shares', array($this, 'presscount_total_shares') );
			
			add_action( 'wp_ajax_get_fb_shares', array($this, 'presscount_fb_shares') );
			add_action( 'wp_ajax_nopriv_get_fb_shares', array($this, 'presscount_fb_shares') );
			
			add_action( 'wp_ajax_get_twitter_shares', array($this, 'presscount_twitter_shares') );
			add_action( 'wp_ajax_nopriv_get_twitter_shares', array($this, 'presscount_twitter_shares') );
			
			add_action( 'wp_ajax_get_linkedin_shares', array($this, 'presscount_linkedin_shares') );
			add_action( 'wp_ajax_nopriv_get_linkedin_shares', array($this, 'presscount_linkedin_shares') );
			
			add_action( 'wp_ajax_get_google_shares', array($this, 'presscount_google_shares') );
			add_action( 'wp_ajax_nopriv_get_google_shares', array($this, 'presscount_google_shares') );
			
			add_action( 'wp_ajax_get_pinterest_shares', array($this, 'presscount_pinterest_shares') );
			add_action( 'wp_ajax_nopriv_get_pinterest_shares', array($this, 'presscount_pinterest_shares') );
			
			// Share Count Shortcode
			add_shortcode('share_count', array($this,'share_count_sc'));
			
			$this->load_dependencies();
			
			$this->shares = new PressCount_Shares();
			
			if(is_admin()) 
				$this->admin = new PressCount_Admin($this->version);
		}
		
		
		/**
		 * Load plugin dependencies
		 *
		 * @since      0.5.2
		 * @return    null
		 */
		public function load_dependencies() {
			require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/class-presscount-shares.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . '/includes/admin/class-presscount-admin.php';
		}
	
	
		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since      0.5.2
		 * @return    string    The name of the plugin.
		 */
		public function get_presscount() {
			return $this->presscount;
		}
	
	
		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since      0.5.2
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}
		
		
		/**
		 * Retrieve total share count via ajax
		 *
		 * @since      0.5.2
		 * @return    int		Total share count
		 */
		public function presscount_total_shares($url = null) {
	
			echo $this->shares->get_all_shares($url);
			die();
		}
		
		
		/**
		 * Retrieve facebook count via ajax
		 *
		 * @since      0.5.2
		 * @return    int		Number of Facebook shares
		 */
		public function presscount_fb_shares($url = null) {
			
			echo $this->shares->get_fb($url);
			die();
		}
		
		
		/**
		 * Retrieve twitter count via ajax
		 *
		 * @since      0.5.2
		 * @return    int		Number of Twitter shares
		 */
		public function presscount_twitter_shares($url = null) {
			
			echo $this->shares->get_tweets($url);
			die();
		}
		
		
		/**
		 * Retrieve linkedin count via ajax
		 *
		 * @since      0.5.2
		 * @return    int		Number of LinkedIn shares
		 */
		public function presscount_linkedin_shares($url = null) {
			
			echo $this->shares->get_linkedin($url);
			die();
		}
		
		
		/**
		 * Retrieve Google Plus count via ajax
		 *
		 * @since      0.5.2
		 * @return    int		Number of Google Plus shares
		 */
		public function presscount_google_shares($url = null) {
			
			echo $this->shares->get_plusones($url);
			die();
		}
		
		
		/**
		 * Retrieve Pinterest count via ajax
		 *
		 * @since      0.5.2
		 * @return    int		Number of Pinterest shares
		 */
		public function presscount_pinterest_shares($url = null) {
			
			echo $this->shares->get_pinterest($url);
			die();
		}
		
		
		/**
		 * Inject AJAX JS with Shortcode
		 *
		 * @since      0.5.2
		 * @return    string	Shortcode AJAX script
		 */
		public function share_count_sc($atts) {
			
			global $post;
			
			// extract shortcode attributes
			extract( shortcode_atts( array(
	        	'text' => false,
	        	'url' => get_the_permalink(),
	        	'id' => $post->ID
	    	), $atts, 'share_count' ) );
			
			if($text) :
				
		?>
				<script>
					jQuery(document).ready(function() {
						var sc = jQuery("span#<?php echo $id; ?>");
						jQuery.get(ajaxurl, {
							action: "get_all_shares",
							url: "<?php echo $url; ?>",
							id: "<?php echo $id; ?>"
						}).done(function(data) {
							var shares = Number(data);
							if(!isNaN(shares)) {
								if(parseInt(shares) > 1 || parseInt(shares) == 0) {
									sc.html(data + " Shares");
								} else if(parseInt(shares) == 1) {
									sc.html(shares + " Share");
								}	
							} else {
								sc.html("0 Shares");
							}	
						});
					});
				</script>
				
				<span id="<?php echo $id; ?>" class="shares"></span>
		<?php else: ?>
				<script>
					jQuery(document).ready(function() {
						var sc = jQuery("span#<?php echo $id; ?>");
						jQuery.get(ajaxurl, {
							action: "get_all_shares",
							url: "<?php echo $url; ?>",
							id: "<?php echo $id; ?>"
						}).done(function(data) {
							var shares = Number(data);
							if(!isNaN(shares)) {
								sc.html(shares);
							} else {
								sc.html("0");
							}
						});
					});
				</script>
				<span id="<?php echo $id; ?>" class="shares"></span>
		<?php
			endif;
	
		}
	} // end class
} // end class_exists