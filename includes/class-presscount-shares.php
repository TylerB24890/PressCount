<?php

/**
 * This is where the magic happens.
 *
 *
 * @link       http://elexicon.com
 * @since      0.5.2
 *
 * @package    PressCount
 * @subpackage presscount/includes
 */

/**
 * The cURL requests to social network APIs.
 *
 * @since      0.5.2
 * @package    PressCount
 * @subpackage presscount/includes
 * @author     Tyler Bailey <tylerb.media@gmail.com>
 */

if(!class_exists('PressCount_Shares')) {
	class PressCount_Shares {
		
		private $timeout, $new_shares, $total_shares_count;
		public $url;
		
		/**
		 * Construct Class
		 *
		 * @since      0.5.2
		 * @return    string 
		 */
		public function __construct($timeout = 15) {
			$this->timeout = $timeout;
	
			$max_requests = 12;
			$curl_options = array(
				CURLOPT_SSL_VERIFYPEER => FALSE,
				CURLOPT_SSL_VERIFYHOST => FALSE,
			);
		}
		
		
		/**
		 * Get current post URL
		 *
		 * @since      0.5.2
		 * @return    string 	Post/Page URL
		 */
		public function get_post_url() {
	
			if (defined('DOING_AJAX') && DOING_AJAX && isset($_GET['url'])) {
				$this->url = $_GET['url'];
			} else {
				$this->url = get_permalink();
			}	
	
			return $this->url;
		}
		
		
		/**
		 * Get number of shares on twitter
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		public function get_tweets($url = null, $id = null) {
			
			// Post/Page URL
			$url == null ? $this->url = $this->get_post_url() : $this->url = $url;
			
			// Post/Page ID
			(isset($_GET['id']) && $id == null ? $pid = $_GET['id'] : $pid = url_to_postid($this->url));

			$json_string = $this->file_get_contents_curl('http://urls.api.twitter.com/1/urls/count.json?url=' . $this->url);
	
			if($json_string === false || isset($json_string['error'])) return 0;
	
			$json = json_decode($json_string, true);
			
			return isset($json['count']) ? intval($json['count']) : 0;
		}
		
	
		/**
		 * Get number of shares on linkedin
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		public function get_linkedin($url = null, $id = null) {
			
			// Post/Page URL
			$url == null ? $this->url = $this->get_post_url() : $this->url = $url;
			
			// Post/Page ID
			(isset($_GET['id']) && $id == null ? $pid = $_GET['id'] : $pid = url_to_postid($this->url));

			$json_string = $this->file_get_contents_curl('http://www.linkedin.com/countserv/count/share?url='.$this->url.'&format=json');
	
			if($json_string === false || isset($json_string['error'])) return 0;
	
			$json = json_decode($json_string, true);
			
			return isset($json['count']) ? intval($json['count']) : 0;
		}
	
	
	
		/**
		 * Get number of shares on Facebook
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		public function get_fb($url = null, $id = null) {
			
			// Post/Page URL
			$url == null ? $this->url = $this->get_post_url() : $this->url = $url;
			
			// Post/Page ID
			(isset($_GET['id']) && $id == null ? $pid = $_GET['id'] : $pid = url_to_postid($this->url));
			
			$json_string = $this->file_get_contents_curl('http://graph.facebook.com/?id='.$this->url);
			
			if($json_string === false || isset($json_string['error'])) return 0;
		
			$json = json_decode($json_string, true);
			
			return isset($json['shares']) ? intval($json['shares']) : 0;			
		}
	
	
	
		/**
		 * Get number of shares on Google Plus
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		public function get_plusones($url = null, $id = null) {
			
			// Post/Page URL
			$url == null ? $this->url = $this->get_post_url() : $this->url = $url;
			
			// Post/Page ID
			(isset($_GET['id']) && $id == null ? $pid = $_GET['id'] : $pid = url_to_postid($this->url));
			
			$curl = curl_init();
	
			curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.rawurldecode($this->url).'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
	
			$curl_results = curl_exec($curl);
	
			curl_close($curl);
	
			if($curl_results === false) return 0;
	
			$json = json_decode($curl_results, true);
				
			return isset($json[0]['result']['metadata']['globalCounts']['count']) ? intval( $json[0]['result']['metadata']['globalCounts']['count'] ) : 0;
		}
	
	
	
		/**
		 * Get number of shares on Pinterest
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		public function get_pinterest($url = null, $id = null) {
			
			// Post/Page URL
			$url == null ? $this->url = $this->get_post_url() : $this->url = $url;
			
			// Post/Page ID
			(isset($_GET['id']) && $id == null ? $pid = $_GET['id'] : $pid = url_to_postid($this->url));

			$return_data = $this->file_get_contents_curl('http://api.pinterest.com/v1/urls/count.json?url='.$this->url);

			if($return_data === false || isset($return_data['error'])) return 0;
	
			$json_string = preg_replace('/^receiveCount\((.*)\)$/', "\\1", $return_data);
			$json = json_decode($json_string, true);
			
			return isset($json['count']) ? intval($json['count']) : 0;
		}
			
		
		/**
		 * Calculate all shares together
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		public function get_all_shares($url = null, $id = null) {
			
			// Post/Page URL
			$url == null ? $this->url = $this->get_post_url() : $this->url = $url;
			
			// Post/Page ID
			(isset($_GET['id']) && $id == null ? $pid = $_GET['id'] : $pid = url_to_postid($this->url));
			
			if(get_transient($pid . "_post_shares")) {
				return get_transient($pid . "_post_shares");
			}	

			$this->new_shares = 0;
			$this->total_shares_count = 0;
		
			if(function_exists('curl_version')) {
				$version = curl_version();
				$bitfields = array(
					'CURL_VERSION_IPV6',
					'CURLOPT_IPRESOLVE'
				);
		
				foreach($bitfields as $feature) {
					if($version['features'] & constant($feature)) {
						
						$this->total_shares_count += $this->get_tweets($url);
						$this->total_shares_count += $this->get_fb($url);
						$this->total_shares_count += $this->get_linkedin($url);
						$this->total_shares_count += $this->get_plusones($url);
						$this->total_shares_count += $this->get_pinterest($url);
						break;
					}
				}
			}
		
			$total_shares = $this->new_shares + $this->total_shares_count;
	
			// Save total share count to database
			update_post_meta($pid, "_post_shares", $total_shares);
			set_transient($pid . "_post_shares", $total_shares, 3600);

			return $total_shares;
		}
			
			
		/**
		 * General cURL request
		 *
		 * @since      0.5.2
		 * @return    int
		 */
		private function file_get_contents_curl($url) {
	
			$ch = curl_init();
	
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
			curl_setopt($ch, CURLOPT_FAILONERROR, 1);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $this->timeout);
			if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4'))
				curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	
			$cont = curl_exec($ch);
	
			if(curl_errno($ch)) {
				//die(curl_error($ch));
				$c_err['curl_message'] = curl_error($ch);
				$c_err['error'] = "0";
				return $c_err;
			}
	
			curl_close($ch);
			return $cont;
		}
		
	} // end class
} // end class_exists