<?php

/**
 * The file that defines the admin functions
 *
 *
 * @link       http://elexicon.com
 * @since      0.5.2
 *
 * @package    PressCount
 * @subpackage PressCount/includes
 */

/**
 * PressCount WP-Admin dashboard
 *
 *
 * @since      0.5.2
 * @package    PressCount
 * @subpackage presscount/includes/admin
 * @author     Tyler Bailey <tylerb.media@gmail.com>
 */
 
if(!class_exists('PressCount_Admin') && class_exists('PressCount_Shares')) {
	class PressCount_Admin extends PressCount_Shares {
		
		/**
		 * Construct Class
		 *
		 * @since      0.5.2
		 * @return    null
		 */
		public function __construct($version) {
			
			if(!is_admin())
				exit("You must be an administrator.");
			
			parent::__construct();
			
			$this->version = $version;
			
			add_action('admin_menu', array($this, 'create_menu_links'));
			
			add_action( 'wp_ajax_user_redirect', array($this, 'user_redirect_by_date') );
			add_action( 'wp_ajax_nopriv_user_redirect', array($this, 'user_redirect_by_date') );
			
			add_action( 'wp_ajax_user_search', array($this, 'user_redirect_by_search') );
			add_action( 'wp_ajax_nopriv_user_search', array($this, 'user_redirect_by_search') );
		}
		
		/**
		 * Enqueue admin specific styles
		 *
		 * @since      0.5.2
		 * @return    file
		 */
		public function enqueue_styles() {
			wp_enqueue_style(
				'presscount-admin',
				plugin_dir_url(__FILE__) . 'css/presscount-admin.css',
				array(),
				$this->version,
				FALSE
			);
		}
		
		
		/**
		 * Add 'PressCount Stats' menu item to dashboard
		 *
		 * @since      0.5.2
		 * @return    null 
		 */
		public function create_menu_links() {
			add_menu_page('PressCount', 'PressCount', 'manage_options', 'presscount', array($this, 'presscount_dash_init'), 'dashicons-networking');
			
			add_submenu_page('presscount', 'Overview', 'Overview', 'manage_options', 'presscount');
			
			add_submenu_page('presscount', 'PressCount Stats', 'Social Stats', 'manage_options', 'presscount-stats', array($this, 'presscount_stats_init'));
		}
		
		
		/**
		 * Require the PressCount Dashboard partial
		 *
		 * @since      0.5.2
		 * @return    file
		 */
		public function presscount_dash_init() {
			$this->enqueue_styles();
			require_once(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/presscount-dashboard.php');
		}
		
		
		/**
		 * Require the PressCount Stats partial
		 *
		 * @since      0.5.2
		 * @return    file
		 */
		public function presscount_stats_init() {
			$this->enqueue_styles();
			require_once(plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/presscount-stats.php');
		}
		
		
		
		
		/**
		 * Get most recent posts
		 *
		 * @since      0.5.2
		 * @return    object
		 */
		public function get_recent_posts($number = 10) {
			$recent_args = array(
				'posts_per_page' => $number,
				'post_status' => 'publish',
				'orderby' => 'date',
				'order' => 'DESC',
				'post_status' => 'publish',
			);
			
			$query = new WP_Query($recent_args);
			$posts = $query->get_posts();
			
			return $posts;
		}
		
		
		/**
		 * Get posts by most or least shared
		 *
		 * @since      0.5.2
		 * @return    object
		 */
		public function get_posts_by_shares($n = 10, $order = "most") {
			
			$order == "most" ? $sort = "DESC" : $sort = "ASC";
			
			$args = array(
				'posts_per_page' => $n,
				'meta_key' => '_post_shares',
				'orderby' => 'meta_value_num',
				'order' => $sort,
				'post_status' => 'publish',
			);
			
			$query = new WP_Query($args);
			$posts = $query->get_posts();
			
			return $posts;
		}
		
		
		
		/**
		 * Get posts by keywords
		 *
		 * @since      0.5.2
		 * @return    object
		 */
		public function get_posts_by_keywords($keyword) {
			
			$args = array(
				's' => $keyword,
				'posts_per_page' => '10',
				'post_type' => array('post', 'infographics', 'videos'),
			);
			$query = new WP_Query($args);
			$posts = $query->get_posts();
			
			return $posts;
		}
		
		
		/**
		 * Get posts by date
		 *
		 * @since      0.5.2
		 * @return    object
		 */
		public function get_posts_by_date($start_date, $end_date) {
			
			$date_arr = array();
			
			// Parse POST object and put into userDataArr
			parse_str($_POST['filter_data'], $date_arr);
			
			$start_arr = explode("-", $start_date);
			$start_year = $start_arr[0];
			$start_month = $start_arr[1];
			$start_day = $start_arr[2];
			
			if(strlen($end_date) > 1) {
				$end_arr = explode("-", $end_date);
				$end_year = $end_arr[0];
				$end_month = $end_arr[1];
				$end_day = $end_arr[2];
			} else {
				$today = getdate();
				$end_year = $today['year'];
				$end_month = $today['mon'];
				$end_day = $today['mday'];
			}
			
			$args = array(
				'date_query' => array(
					array(
						'after'     => $start_date,
						'before'    => array(
							'year'  => $end_year,
							'month' => $end_month,
							'day'   => $end_day,
						),
						'inclusive' => true,
					),
				),
				'posts_per_page' => -1,
				'post_status' => 'publish',
			);
			
			$query = new WP_Query($args);
			$posts = $query->get_posts();
			
			return $posts;
		}
		
		
		/**
		 * Redirect user & append date query to URL
		 *
		 * @since      0.5.2
		 * @return    json object
		 */
		public function user_redirect_by_date() {
			
			$url_arr = array();
			
			// Parse POST object and put into userDataArr
			parse_str($_POST['filter_data'], $url_arr);
			
			$return = array();
			
			$url = $url_arr['page_url'];
			$redirect_url = add_query_arg( array('type' => 'date', 'start' => $url_arr['start_date'], 'end' => $url_arr['end_date']),  $url);
			
			$return['status'] = 1;
			$return['redirect_url'] = $redirect_url;
			
			echo json_encode($return);
			die();
		}
		
		
		
		/**
		 * Redirect user & append search query to URL
		 *
		 * @since      0.5.2
		 * @return    json object
		 */
		public function user_redirect_by_search() {
			$search_arr = array();
			
			parse_str($_POST['search_data'], $search_arr);
			
			$return = array();
			$post_type_arr = array();
			$post_type_count = 0;
			foreach($search_arr['post_type'] as $post_type) {
				$post_type_count++;	
				$post_type_arr[$post_type_count] = $post_type;
			}
			
			$url = $search_arr['page_url'];
			
			$redirect_url = add_query_arg( array('post_type' => implode(",", $post_type_arr), 'keywords' => $search_arr['s']),  $search_arr['page_url']);
			
			$return['status'] = 1;
			$return['redirect_url'] = $redirect_url;
			
			echo json_encode($return);
			die();
		}
		
	} // end class
} // end class_exists